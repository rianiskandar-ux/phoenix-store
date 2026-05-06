/**now
 * SNIPPET: My Add-ons - v8
 * v8: Fix badge threshold — Monthly H-7, Yearly H-30, Free no warning
 */

// =============================================================================
// 1. ENDPOINT
// =============================================================================
add_action('init', 'add_my_addons_endpoint');
function add_my_addons_endpoint() {
    add_rewrite_endpoint('addons', EP_ROOT | EP_PAGES);
}
 
// =============================================================================
// 2. MENU ITEM
// =============================================================================
add_filter('woocommerce_account_menu_items', 'add_my_addons_menu_item');
function add_my_addons_menu_item($items) {
    $new = [];
    foreach ($items as $key => $label) {
        $new[$key] = $label;
        if ($key === 'workspaces') {
            $new['addons'] = function_exists('phoenix_text') ? phoenix_text('my_addons.menu_label') : 'My Add-ons';
        }
    }
    return $new;
}
 
// =============================================================================
// HELPER: Resolve smart status badge
// v8: Free=no warning, Monthly=H-7, Yearly=H-30
// =============================================================================
function phoenix_resolve_instance_badge($sub, $plan_level = 0, $is_yearly = false) {
    if (!$sub) {
        return ['bg' => '#f5f5f5', 'color' => '#999', 'dot' => '#999', 'label' => phoenix_text('my_addons.badge_unknown')];
    }
 
    $status = $sub->get_status();
 
    if ($status === 'on-hold') {
        return ['bg' => '#fff3e0', 'color' => '#e65100', 'dot' => '#e65100', 'label' => phoenix_text('my_addons.badge_payment_issue')];
    }
 
    if (in_array($status, ['cancelled', 'expired', 'pending-cancel'])) {
        return ['bg' => '#ffebee', 'color' => '#c62828', 'dot' => '#c62828', 'label' => phoenix_text('my_addons.badge_inactive')];
    }
 
    if ($status === 'active') {
        // Free plan — tidak ada billing, tidak perlu warning
        if ($plan_level <= 1) {
            return ['bg' => '#e8f5e9', 'color' => '#2e7d32', 'dot' => '#4caf50', 'label' => phoenix_text('my_addons.badge_active')];
        }
 
        $np = $sub->get_date('next_payment');
        if ($np) {
            $days_left    = (int) ceil((strtotime($np) - time()) / 86400);
            $warning_days = $is_yearly ? 30 : 7; // Monthly = H-7, Yearly = H-30
 
            if ($days_left >= 0 && $days_left <= $warning_days) {
                return ['bg' => '#fff8e1', 'color' => '#f57c00', 'dot' => '#f57c00', 'label' => phoenix_text('my_addons.badge_renewing')];
            }
        }
 
        return ['bg' => '#e8f5e9', 'color' => '#2e7d32', 'dot' => '#4caf50', 'label' => 'Active'];
    }
 
    return ['bg' => '#f5f5f5', 'color' => '#999', 'dot' => '#999', 'label' => ucfirst($status)];
}
 
// =============================================================================
// 3. PAGE CONTENT
// =============================================================================
add_action('woocommerce_account_addons_endpoint', 'render_my_addons_page');
function render_my_addons_page() {
 
 
    if (!is_user_logged_in()) {
        echo '<p>' . phoenix_text('my_addons.login_required') . '</p>';
        return;
    }
 
    global $wpdb;
    $user_id       = get_current_user_id();
    $table_tenants = $wpdb->prefix . 'wbssaas_tenants';
 
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_tenants'") !== $table_tenants) {
        echo '<p>' . phoenix_text('my_addons.unavailable') . '</p>';
        return;
    }
 
    if (!function_exists('wcs_get_users_subscriptions')) {
        echo '<p>' . phoenix_text('my_addons.unavailable') . '</p>';
        return;
    }
 
    $all_rows = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_tenants WHERE customer_id = %d ORDER BY created DESC",
        $user_id
    ));
 
if (empty($all_rows)) {
    echo '<div style="background:#f0fff4;padding:32px;border-radius:10px;text-align:center;border:1px solid #a5d6a7;">
        <div style="font-size:40px;margin-bottom:12px;">🔌</div>
        <h3 style="margin:0 0 8px;color:#2e7d32;">' . phoenix_text('my_addons.empty_title') . '</h3>
        <p style="margin:0 0 16px;color:#666;font-size:14px;">' . phoenix_text('my_addons.empty_desc') . '</p>
        <a href="/pricing/" style="display:inline-block;padding:10px 22px;background:#27ae60;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;">' . phoenix_text('my_addons.empty_cta') . '</a>
    </div>';
    return;
}
 
    foreach ($all_rows as $row) {
        $sid       = (int) $row->subscription_wc_id;
        $sub       = ($sid && function_exists('wcs_get_subscription')) ? wcs_get_subscription($sid) : null;
        $sub_level = ($sub && function_exists('phoenix_get_subscription_plan_level'))
            ? phoenix_get_subscription_plan_level($sub) : 0;
        $sub_yearly = ($sub && function_exists('phoenix_is_yearly_subscription'))
            ? phoenix_is_yearly_subscription($sub) : false;
 
        $row->_sub    = $sub;
        $row->_status = $sub ? $sub->get_status() : 'unknown';
        $row->_level  = $sub_level;
        $row->_np     = $sub ? $sub->get_date('next_payment') : '';
        // v8: pass plan_level dan is_yearly ke badge resolver
        $row->_badge  = phoenix_resolve_instance_badge($sub, $sub_level, $sub_yearly);
    }
 
    $grouped = [];
    foreach ($all_rows as $row) {
        $key = trim(strtolower($row->tenant_name));
        if (!isset($grouped[$key])) $grouped[$key] = [];
        $grouped[$key][] = $row;
    }
 
    $active_instances   = [];
    $inactive_instances = [];
 
    foreach ($grouped as $name_key => $group) {
        usort($group, fn($a, $b) => strtotime($a->created) - strtotime($b->created));
        $oldest_row  = $group[0];
        $active_rows = array_values(array_filter($group, fn($r) => $r->_status === 'active'));
 
        if (!empty($active_rows)) {
            usort($active_rows, fn($a, $b) => $b->_level - $a->_level);
            $display_row = $active_rows[0];
 
            $oldest_url  = preg_replace('/\.stg\./i', '.', $oldest_row->tenant_url ?? '');
            $highest_url = preg_replace('/\.stg\./i', '.', $display_row->tenant_url ?? '');
            $is_custom   = $highest_url
                        && !preg_match('/whistleblowing\.|phoenix-/i', $highest_url)
                        && $oldest_url !== $highest_url;
 
            $display_row->_display_url    = $is_custom ? $highest_url : ($oldest_url ?: $highest_url);
            $display_row->_canonical_uuid = $oldest_row->tenant_uuid;
            $display_row->_oldest_url_raw = rtrim($oldest_row->tenant_url ?? '', '/'); // raw staging URL for BSW wizard
            $active_instances[]           = $display_row;
        } else {
            $display_row                  = $group[0];
            $display_row->_display_url    = preg_replace('/\.stg\./i', '.', $display_row->tenant_url ?? '');
            $display_row->_canonical_uuid = $display_row->tenant_uuid;
            $inactive_instances[]         = $display_row;
        }
    }
 
    $all_user_subs = wcs_get_users_subscriptions($user_id);
 
    $addon_by_np = [];
    $main_np_map = [];
 
    foreach ($all_user_subs as $sub) {
        if (!$sub->has_status('active')) continue;
        $np  = $sub->get_date('next_payment');
        $key = $np ? date('Y-m-d', strtotime($np)) : 'no_date_' . $sub->get_id();
 
        $is_addon = false;
        foreach ($sub->get_items() as $item) {
            if (has_term('add-on', 'product_cat', $item->get_product_id())) {
                $is_addon = true;
                break;
            }
        }
 
        if ($is_addon) {
            if (!isset($addon_by_np[$key])) $addon_by_np[$key] = [];
            $addon_by_np[$key][] = $sub;
        } else {
            $level = function_exists('phoenix_get_subscription_plan_level')
                ? phoenix_get_subscription_plan_level($sub) : 0;
            if (!isset($main_np_map[$key]) || $level > $main_np_map[$key]) {
                $main_np_map[$key] = $level;
            }
        }
    }
 
    $addon_products = wc_get_products([
        'status'   => 'publish',
        'limit'    => 50,
        'category' => ['add-on'],
        'orderby'  => 'menu_order',
        'order'    => 'ASC',
    ]);
    if (!is_array($addon_products)) $addon_products = [];
 
    $has_inactive = !empty($inactive_instances);
    ?>
 
    <h2 style="margin-bottom:5px;"><?php echo phoenix_text('my_addons.page_title'); ?></h2>
    <p style="color:#666;margin-bottom:20px;font-size:14px;"><?php echo phoenix_text('my_addons.page_subtitle'); ?></p>
 
  
    <style>
    .addon-card{background:#fff;border:1px solid #e0e0e0;border-radius:10px;margin-bottom:16px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,.05);transition:box-shadow .2s}
    .addon-card:hover{box-shadow:0 4px 14px rgba(0,0,0,.09)}
    .addon-card.inactive-card{opacity:.65;border-style:dashed;display:none}
    .addon-card-header{padding:14px 18px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:12px;border-bottom:1px solid transparent;transition:background .15s}
    .addon-card-header:hover{background:#fafafa}
    .addon-card-header.open{border-bottom-color:#f0f0f0}
    .addon-card-left{display:flex;align-items:center;gap:12px;flex:1;flex-wrap:wrap;min-width:0}
    .addon-card-icon{width:38px;height:38px;border-radius:8px;background:#f0fff4;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
    .addon-card-meta{min-width:0;flex:1}
    .addon-card-name{font-size:14px;font-weight:700;color:#2c3e50;margin:0 0 2px}
    .addon-card-sub{font-size:12px;color:#999;display:flex;align-items:center;gap:6px;flex-wrap:wrap}
    .addon-card-badges{display:flex;gap:6px;flex-shrink:0;flex-wrap:wrap;align-items:center}
    .addon-status-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .addon-status-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}
    .addon-status-dot.pulse{animation:pulse-dot 2s infinite}
    @keyframes pulse-dot{0%,100%{opacity:1}50%{opacity:.4}}
    .addon-chevron{color:#ccc;font-size:12px;transition:transform .2s;flex-shrink:0}
    .addon-chevron.open{transform:rotate(180deg);color:#27ae60}
    .addon-card-body{display:none;padding:18px}
    .addon-card-body.open{display:block}
    .available-addons-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:10px}
    @media(max-width:600px){.available-addons-grid{grid-template-columns:1fr}}
    .addon-product-card{background:#f8f9fa;border:1px solid #e0e0e0;border-radius:8px;padding:12px 14px;display:flex;flex-direction:column;gap:6px;transition:border-color .2s}
    .addon-product-card:hover{border-color:#27ae60;background:#f0fff4}
    .addon-product-name{font-size:13px;font-weight:700;color:#2c3e50}
    .addon-product-desc{font-size:12px;color:#888;line-height:1.4;flex:1}
    .addon-product-footer{display:flex;justify-content:space-between;align-items:center;margin-top:4px}
    .addon-product-price{font-size:13px;font-weight:700;color:#27ae60}
    .addon-product-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 12px;background:#27ae60;color:#fff;border-radius:5px;font-size:11px;font-weight:700;text-decoration:none;transition:background .2s}
    .addon-product-btn:hover{background:#219a52;color:#fff}
    .addon-product-btn.already{background:#e8f5e9;color:#2e7d32;cursor:default;pointer-events:none}
    .active-addons-empty{background:#f8f9fa;border-radius:8px;padding:14px;text-align:center;color:#999;font-size:13px;margin-bottom:12px}
    .addon-active-table{width:100%;border-collapse:collapse;font-size:13px;margin-bottom:14px}
    .addon-active-table thead tr{background:#f0fdf4;border-bottom:2px solid #a5d6a7}
    .addon-active-table th{padding:9px 12px;text-align:left;font-weight:700;color:#2e7d32;font-size:11px;text-transform:uppercase;letter-spacing:.5px}
    .addon-active-table td{padding:10px 12px;border-bottom:1px solid #f0f0f0;vertical-align:middle}
    .addon-active-table tbody tr:last-child td{border-bottom:none}
    .addon-active-table tbody tr:hover{background:#fafffe}
    .saas-upgrade{background:#f0f7ff;border-left:4px solid #3498db;border-radius:0 6px 6px 0;padding:14px 16px;margin-bottom:14px}
    .saas-upgrade h4{margin:0 0 10px;font-size:13px;color:#1565c0}
    .saas-upgrade-group{margin-bottom:12px}
    .saas-upgrade-group:last-child{margin-bottom:0}
    .saas-upgrade-group-label{font-size:11px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
    .saas-upgrade-btns{display:flex;gap:8px;flex-wrap:wrap}
    .saas-upgrade-btn{display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border-radius:6px;font-size:12px;font-weight:700;text-decoration:none;transition:all .2s;border:2px solid transparent}
    .saas-upgrade-btn.basic-monthly{background:#e3f2fd;color:#1565c0;border-color:#90caf9}
    .saas-upgrade-btn.basic-monthly:hover{background:#1565c0;color:#fff}
    .saas-upgrade-btn.basic-yearly{background:#e8f5e9;color:#2e7d32;border-color:#a5d6a7}
    .saas-upgrade-btn.basic-yearly:hover{background:#2e7d32;color:#fff}
    .saas-upgrade-btn.prem-monthly{background:#fff8e1;color:#f57c00;border-color:#ffcc80}
    .saas-upgrade-btn.prem-monthly:hover{background:#f57c00;color:#fff}
    .saas-upgrade-btn.prem-yearly{background:#fff3e0;color:#e65100;border-color:#ffb74d}
    .saas-upgrade-btn.prem-yearly:hover{background:#e65100;color:#fff}
    .active-themes-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;margin-bottom:14px}
    .active-theme-card{position:relative;border-radius:10px;overflow:hidden;border:2px solid #a5d6a7;background:#fff;box-shadow:0 2px 8px rgba(46,125,50,.12);transition:transform .2s,box-shadow .2s}
    .active-theme-card:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(46,125,50,.2)}
    .active-theme-glow{position:absolute;inset:0;border-radius:10px;box-shadow:inset 0 0 0 2px rgba(76,175,80,.4);pointer-events:none;z-index:2}
    .active-theme-img{width:100%;height:90px;object-fit:cover;display:block}
    .active-theme-placeholder{width:100%;height:90px;display:flex;align-items:center;justify-content:center;font-size:32px;background:linear-gradient(135deg,#e8f5e9,#c8e6c9)}
    .active-theme-info{padding:8px 10px}
    .active-theme-name{font-size:12px;font-weight:700;color:#1b5e20;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px}
    .active-theme-badge{display:flex;align-items:center;gap:5px;font-size:11px;color:#2e7d32;font-weight:600}
    .active-theme-dot{width:7px;height:7px;border-radius:50%;background:#4caf50;box-shadow:0 0 0 2px rgba(76,175,80,.3);animation:pulse-dot 2s infinite}
    .themes-cta-box{padding:14px 16px;background:#fafafa;border:1px solid #eee;border-radius:8px;display:flex;align-items:center;justify-content:space-between;gap:12px}
    .themes-cta-text{font-size:12px;color:#666;flex:1}
    .themes-cta-btn{display:inline-block;padding:8px 16px;background:#3498db;color:#fff;border-radius:6px;text-decoration:none;font-size:12px;font-weight:600;white-space:nowrap}
    .themes-cta-btn:hover{background:#2980b9}
    .addon-section-title{font-size:12px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin:16px 0 8px;padding-bottom:5px;border-bottom:1px solid #f0f0f0}
    .addon-readmore-btn{background:none;border:none;padding:0;margin:0;color:#3498db;font-size:11px;font-weight:600;cursor:pointer;text-decoration:underline;text-underline-offset:2px;display:inline;white-space:nowrap}
    .addon-readmore-btn:hover{color:#2980b9}
    #addon-modal-overlay{position:fixed;inset:0;z-index:9999;display:none;align-items:center;justify-content:center;padding:20px;background: #00000059;height:auto}
    #addon-modal-overlay.show{display:flex}
    #addon-modal-box{background:#fff;border-radius:12px;max-width:480px;width:100%;padding:24px;box-shadow:0 8px 40px rgba(0,0,0,.18);position:relative;max-height:80vh;overflow-y:auto}
    @media(prefers-color-scheme:dark){#addon-modal-box{color:#e0e0e0}}
    #addon-modal-title{font-size:16px;font-weight:700;color:#2c3e50;margin:0 32px 12px 0;line-height:1.3}
    #addon-modal-desc{font-size:13px;color:#555;line-height:1.7;margin:0}
    #addon-modal-close{position:absolute;top:16px;right:16px;background:none;border:none;font-size:20px;cursor:pointer;color:#999;line-height:1;padding:4px;border-radius:4px}
    #addon-modal-close:hover{background:#f0f0f0;color:#333}
    .alert-banner{padding:14px 16px;border-radius:8px;margin-bottom:14px;display:flex;align-items:flex-start;gap:10px;font-size:13px}
    .alert-banner.warning{background:#fff8e1;border-left:4px solid #f57c00;color:#e65100}
    .alert-banner.danger{background:#ffebee;border-left:4px solid #c62828;color:#c62828}
    .setup-wizard-banner{background:#fff8e1;border:1px solid #ffcc02;border-left:4px solid #f39c12;border-radius:8px;padding:13px 16px;margin-bottom:16px;display:flex;align-items:flex-start;gap:12px}
    .setup-wizard-banner .swb-icon{font-size:20px;flex-shrink:0;margin-top:1px}
    .setup-wizard-banner .swb-body{flex:1;min-width:0}
    .setup-wizard-banner .swb-title{font-size:13px;font-weight:700;color:#b26a00;margin:0 0 4px}
    .setup-wizard-banner .swb-desc{font-size:12px;color:#666;margin:0 0 10px;line-height:1.5}
    .setup-wizard-banner .swb-btn{display:inline-block;background:#f39c12;color:#fff;text-decoration:none;font-size:12px;font-weight:700;padding:7px 14px;border-radius:5px}
    .setup-wizard-banner .swb-btn:hover{background:#e67e22;color:#fff}
    .back-link{display:inline-flex;align-items:center;gap:5px;margin-top:20px;color:#3498db;font-size:13px;font-weight:600;text-decoration:none}
    .back-link:hover{color:#2980b9}
    </style>
 
<script>
document.addEventListener('DOMContentLoaded', function() {
 
    // ── Register click handler untuk semua card ──
    document.querySelectorAll('.addon-card-header').forEach(function(header, i) {
        header.addEventListener('click', function() {
            var body    = this.parentElement.querySelector('.addon-card-body');
            var chevron = this.querySelector('.addon-chevron');
            var isOpen  = body.classList.contains('open');
            body.classList.toggle('open', !isOpen);
            chevron.classList.toggle('open', !isOpen);
            this.classList.toggle('open', !isOpen);
        });
    });
 
    // ── Auto-open dari sessionStorage (redirect dari My SaaS) ──
    var targetAnchor = sessionStorage.getItem('phoenix_open_instance');
    if (targetAnchor) {
        sessionStorage.removeItem('phoenix_open_instance');
 
        function tryOpenCard(anchor, attempts) {
            var targetCard = document.getElementById(anchor);
            if (!targetCard) {
                if (attempts > 0) setTimeout(function() { tryOpenCard(anchor, attempts - 1); }, 200);
                return;
            }
 
            // Tutup semua card
            document.querySelectorAll('.addon-card-body').forEach(function(b) { b.classList.remove('open'); });
            document.querySelectorAll('.addon-card-header').forEach(function(h) {
                h.classList.remove('open');
                var c = h.querySelector('.addon-chevron');
                if (c) c.classList.remove('open');
            });
 
            // Buka target card
            var body   = targetCard.querySelector('.addon-card-body');
            var header = targetCard.querySelector('.addon-card-header');
            var chev   = header ? header.querySelector('.addon-chevron') : null;
            if (body)   body.classList.add('open');
            if (header) header.classList.add('open');
            if (chev)   chev.classList.add('open');
 
            setTimeout(function() {
                targetCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
 
        tryOpenCard(targetAnchor, 5);
 
    } else {
        // Tidak ada target — buka card pertama by default
        var firstHeader = document.querySelector('.addon-card-header');
        if (firstHeader) firstHeader.click();
    }
});
 
function phoenixAddonModal(btn) {
    var name = btn.getAttribute('data-name');
    var desc = btn.getAttribute('data-desc');
    document.getElementById('addon-modal-title').textContent = name;
    document.getElementById('addon-modal-desc').textContent  = desc;
    document.getElementById('addon-modal-overlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function phoenixCloseModal() {
    document.getElementById('addon-modal-overlay').classList.remove('show');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') phoenixCloseModal();
});
var inactiveVisible = false;
function toggleInactive() {
    inactiveVisible = !inactiveVisible;
    document.querySelectorAll('.inactive-card').forEach(function(card) {
        card.style.display = inactiveVisible ? 'block' : 'none';
    });
    var btn = document.getElementById('toggle-inactive');
    if (btn) {
        btn.textContent       = inactiveVisible ? phoenix_text('my_addons.hide_inactive') : phoenix_text('my_addons.show_inactive');
        btn.style.color       = inactiveVisible ? '#c62828' : '#666';
        btn.style.borderColor = inactiveVisible ? '#ffcdd2' : '#ddd';
    }
}
</script>
 
    <!-- Addon description modal -->
    <div id="addon-modal-overlay" onclick="if(event.target===this)phoenixCloseModal()">
        <div id="addon-modal-box">
            <button id="addon-modal-close" onclick="phoenixCloseModal()">✕</button>
            <div id="addon-modal-title"></div>
            <div id="addon-modal-desc"></div>
        </div>
    </div>
 
    <?php
    $all_display = array_merge(
        array_map(fn($r) => ['row' => $r, 'inactive' => false], $active_instances),
        array_map(fn($r) => ['row' => $r, 'inactive' => true],  $inactive_instances)
    );
 
    foreach ($all_display as $entry):
        $tenant      = $entry['row'];
        $is_inactive = $entry['inactive'];
 
        $sub_id         = (int) $tenant->subscription_wc_id;
        $tenant_name    = $tenant->tenant_name;
        $tenant_url     = $tenant->_display_url ?? preg_replace('/\.stg\./i', '.', $tenant->tenant_url ?? '');
        $subscription   = $tenant->_sub;
        $status         = $tenant->_status;
        $plan_level     = $tenant->_level;
        $np_raw         = $tenant->_np;
        $badge          = $tenant->_badge;
 
        $plan_slug = 'unknown';
        if ($plan_level >= 3)     $plan_slug = 'premium';
        elseif ($plan_level >= 2) $plan_slug = 'basic';
        elseif ($plan_level >= 1) $plan_slug = 'free';
 
        $plan_label     = ucfirst($plan_slug);
        $active_sub_id  = $subscription ? $subscription->get_id() : $sub_id;
        $canonical_uuid = $tenant->_canonical_uuid ?? $tenant->tenant_uuid ?? '';
        $is_premium     = ($plan_level >= 3);
 
            // ── FIX Bug 2: re-verify subscription dari WCS langsung ──────
			// Masalah: setelah upgrade Basic Monthly → Premium Yearly,
			// row DB mungkin masih punya subscription_wc_id lama (Basic Monthly).
			// display_row sudah benar (highest level), tapi $subscription bisa
			// masih Basic Monthly sub → is_yearly = false → warning_days = 7 → bug.
			//
			// Fix: kalau plan_level >= 3 (Premium) tapi sub masih monthly,
			// cari semua active subs user dan ambil yang highest level untuk instance ini.
			if ($subscription && $plan_level >= 2) {
				$actual_level = function_exists('phoenix_get_subscription_plan_level')
					? phoenix_get_subscription_plan_level($subscription) : 0;
				if ($actual_level < $plan_level) {
					// Sub di DB tidak match level yang di-display → cari yang benar
					foreach ($all_user_subs as $_candidate) {
						if (!$_candidate->has_status('active')) continue;
						$_is_addon = false;
						foreach ($_candidate->get_items() as $_ci) {
							if (has_term('add-on', 'product_cat', $_ci->get_product_id())) {
								$_is_addon = true; break;
							}
						}
						if ($_is_addon) continue;
						$_clevel = function_exists('phoenix_get_subscription_plan_level')
							? phoenix_get_subscription_plan_level($_candidate) : 0;
						if ($_clevel >= $plan_level) {
							$subscription  = $_candidate; // override ke sub yang benar
							$active_sub_id = $_candidate->get_id();
							break;
						}
					}
				}
			}
	
		
		$is_yearly      = $subscription && function_exists('phoenix_is_yearly_subscription')
			? phoenix_is_yearly_subscription($subscription) : false;
	
		// Recalculate badge after subscription correction
		$badge = phoenix_resolve_instance_badge(
			$subscription,
			$plan_level,
			$is_yearly
		);
	
		$billing_period = $is_yearly ? phoenix_text('my_addons.billing_yearly') : phoenix_text('my_addons.billing_monthly');
	
 
        $anchor = 'instance-' . ($tenant->id ?? $sub_id);
 
        $owned_product_ids = [];
        foreach ($all_user_subs as $_osub) {
            if (!$_osub->has_status('active')) continue;
            foreach ($_osub->get_items() as $_oitem) {
                $owned_product_ids[] = $_oitem->get_product_id();
                $vid = $_oitem->get_variation_id();
                if ($vid) $owned_product_ids[] = $vid;
            }
        }
        $t_settings_raw = $tenant->tenant_settings ?? '';
        $t_settings     = $t_settings_raw ? @unserialize($t_settings_raw) : [];
        $owned_skus     = (!empty($t_settings['themes']) && is_array($t_settings['themes'])) ? $t_settings['themes'] : [];
        foreach ($owned_skus as $_sku) {
            $_pid = wc_get_product_id_by_sku($_sku);
            if ($_pid) $owned_product_ids[] = $_pid;
        }
        $owned_product_ids = array_unique(array_filter($owned_product_ids));
 
        $upgrade_basic_url   = get_permalink(58) . '?upgrade_subscription=' . $active_sub_id . '&attribute_payment=' . $billing_period;
        $upgrade_premium_url = get_permalink(76) . '?upgrade_subscription=' . $active_sub_id . '&attribute_payment=' . $billing_period;
 
        // Days left — threshold berbeda per billing period (v8)
        $days_left    = null;
        $warning_days = $is_yearly ? 30 : 7;
        if ($np_raw && $status === 'active' && $plan_level >= 2) {
            $days_left = (int) ceil((strtotime($np_raw) - time()) / 86400);
        }
    ?>
 
    <div class="addon-card <?php echo $is_inactive ? 'inactive-card' : ''; ?>" id="<?php echo esc_attr($anchor); ?>">
 
        <div class="addon-card-header">
            <div class="addon-card-left">
                <div class="addon-card-icon">🔌</div>
                <div class="addon-card-meta">
                    <div class="addon-card-name"><?php echo esc_html($tenant_name); ?></div>
                    <div class="addon-card-sub">
                        <span><?php echo esc_html($tenant_url); ?></span>
                        <span style="color:#ccc;">•</span>
                        <span><?php echo $plan_label; ?> <?php echo phoenix_text('my_addons.plan_suffix'); ?></span>
                        <span style="color:#ccc;">•</span>
                        <span><?php echo $billing_period; ?> <?php echo phoenix_text('my_addons.plan_suffix'); ?></span>
                    </div>
                </div>
                <div class="addon-card-badges">
                    <span class="addon-status-badge" style="background:<?php echo $badge['bg']; ?>;color:<?php echo $badge['color']; ?>;">
                        <span class="addon-status-dot <?php echo $badge['label'] === 'Active' ? 'pulse' : ''; ?>"
                              style="background:<?php echo $badge['dot']; ?>;"></span>
                        <?php echo $badge['label']; ?>
                    </span>
                </div>
            </div>
            <span class="addon-chevron">▼</span>
        </div>
 
        <div class="addon-card-body">
 
            <?php if ($status !== 'active'): ?>
                <div class="alert-banner danger">
                    <span style="font-size:18px;">⚠️</span>
                    <div>
                        <strong>
                            <?php
                            if ($status === 'cancelled')      echo phoenix_text('my_addons.alert_cancelled');
                            elseif ($status === 'expired')    echo phoenix_text('my_addons.alert_expired');
                            elseif ($status === 'on-hold')    echo phoenix_text('my_addons.alert_on_hold');
                            else                              echo phoenix_text('my_addons.alert_inactive');
                            ?>
                        </strong><br>
                        <span style="font-weight:400;">
                            <?php
                            if ($status === 'cancelled')      echo phoenix_text('my_addons.alert_cancelled_msg');
                            elseif ($status === 'expired')    echo phoenix_text('my_addons.alert_expired_msg');
                            elseif ($status === 'on-hold')    echo phoenix_text('my_addons.alert_on_hold_msg');
                            else                              echo phoenix_text('my_addons.alert_other_msg');
                            ?>
                        </span>
                    </div>
                </div>
 
            <?php elseif ($days_left !== null && $days_left >= 0 && $days_left <= $warning_days): ?>
                <div class="alert-banner warning">
                    <span style="font-size:18px;">🔔</span>
                    <div>
							 <strong><?php echo phoenix_text_plural('my_addons.renewing_title', $days_left); ?></strong><br>
							<span style="font-weight:400;"><?php echo phoenix_text('my_addons.renewing_msg', date('d M Y', strtotime($np_raw))); ?></span>
                    </div>
                </div>
 
            <?php endif; ?>
 
            <?php if ($plan_level <= 1 && $status === 'active'): ?>
                <div style="background:#f0f7ff;border-left:4px solid #3498db;border-radius:0 8px 8px 0;padding:16px 18px;margin-bottom:14px;">
                    <div style="font-size:13px;font-weight:700;color:#1565c0;margin-bottom:6px;"><?php echo phoenix_text('my_addons.free_locked_title'); ?></div>
                    <div style="font-size:13px;color:#555;margin-bottom:14px;line-height:1.5;">
                        <?php echo phoenix_text('my_addons.free_locked_desc'); ?>
                    </div>
                    <?php
                    $upgrade_plan_url = get_permalink(get_page_by_path('upgrade-plan')) . '?sub_id=' . $active_sub_id;
                    ?>
                    <a href="<?php echo esc_url($upgrade_plan_url); ?>"
                       style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:#3498db;color:#fff;text-decoration:none;border-radius:6px;font-size:13px;font-weight:700;transition:background .2s;"
                       onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                        ⬆️ View Upgrade Plans →
                    </a>
                </div>
 
            <?php elseif ($status === 'active'): ?>

                <?php
                // BSW gate — check FIRST, render nothing else if incomplete
                $bsw_complete = !function_exists('phoenix_is_wizard_complete')
                    || phoenix_is_wizard_complete($active_sub_id, $user_id);

                if (!$bsw_complete):
                    $bsw_user       = get_user_by('id', $user_id);
                    $bsw_email_hash = $bsw_user ? hash_hmac('sha256', $bsw_user->user_email, AUTH_KEY) : '';
                    $bsw_wizard_url = ($tenant->_oldest_url_raw ?: rtrim($tenant->tenant_url ?? '', '/')) . '/clients/new'
                        . '?u=' . urlencode($canonical_uuid)
                        . '&m=' . urlencode($bsw_email_hash);
                ?>
                <div class="setup-wizard-banner">
                    <div class="swb-icon">⚙️</div>
                    <div class="swb-body">
                        <div class="swb-title"><?php echo phoenix_text('my_saas.wizard_title'); ?> — <?php echo esc_html($tenant_name); ?></div>
                        <div class="swb-desc">
                            <?php echo phoenix_text('my_saas.wizard_desc'); ?><br>
                            <strong style="color:#b26a00;"><?php echo phoenix_text('my_saas.wizard_desc2_addons'); ?></strong>
                        </div>
                        <a href="<?php echo esc_url($bsw_wizard_url); ?>" target="_blank" class="swb-btn"
                           onclick="this.textContent='<?php echo esc_js(phoenix_text('my_saas.wizard_opened')); ?>';this.style.background='#888';this.style.pointerEvents='none';"
                        ><?php echo phoenix_text('my_saas.wizard_cta'); ?></a>
                        <div style="margin-top:8px;font-size:11px;color:#999;"><?php echo phoenix_text('my_saas.wizard_note_addons'); ?></div>
                    </div>
                </div>

                <?php else: // BSW complete — show all content ?>

                <div class="addon-section-title">✅ Active Add-ons</div>
                <?php
                $instance_addon_subs    = [];
                $instance_addon_sub_ids = [];
                $t_uuid = $canonical_uuid;
                $t_np   = $np_raw;

                foreach ($all_user_subs as $_asub) {
                    if (!$_asub->has_status('active')) continue;
                    $_is_addon = false;
                    foreach ($_asub->get_items() as $_ai) {
                        if (has_term('add-on', 'product_cat', $_ai->get_product_id())) { $_is_addon = true; break; }
                    }
                    if (!$_is_addon) continue;
                    if (in_array($_asub->get_id(), $instance_addon_sub_ids)) continue;

                    $matched = false;
                    if (!$matched && $t_uuid && function_exists('GFAPI')) {
                        $_oid     = $_asub->get_parent_id();
                        $_entries = $_oid ? GFAPI::get_entries(64, ['field_filters' => [['key' => 'woocommerce_order_number', 'value' => $_oid]]]) : [];
                        if (!empty($_entries) && ($t_uuid === ($_entries[0]['1'] ?? ''))) $matched = true;
                    }
                    if (!$matched && $t_uuid) {
                        $_oid   = $_asub->get_parent_id();
                        $_order = $_oid ? wc_get_order($_oid) : null;
                        if ($_order) {
                            foreach ($_order->get_items() as $_oitem) {
                                foreach ($_oitem->get_meta_data() as $_ometa) {
                                    if ((string)$_ometa->value === $t_uuid) { $matched = true; break 2; }
                                }
                            }
                        }
                    }
                    if (!$matched && $t_uuid) {
                        $_oid = $_asub->get_parent_id();
                        if ($_oid) {
                            $_gf_val = $wpdb->get_var($wpdb->prepare(
                                "SELECT em.meta_value FROM {$wpdb->prefix}gf_entry_meta em
                                 INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
                                 WHERE e.source_url LIKE %s OR e.id IN (
                                     SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                                     WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
                                 ) AND em.meta_key = '1' AND e.form_id = 64 LIMIT 1",
                                '%order_id=' . $_oid . '%', (string)$_oid
                            ));
                            if ($_gf_val && $_gf_val === $t_uuid) $matched = true;
                        }
                    }
                    if ($matched) {
                        $instance_addon_subs[]    = $_asub;
                        $instance_addon_sub_ids[] = $_asub->get_id();
                    }
                }
                ?>
                <?php if (empty($instance_addon_subs)): ?>
                    <div class="active-addons-empty">
                        <?php echo phoenix_text('my_addons.active_empty'); ?>
                    </div>
                <?php else: ?>
                    <table class="addon-active-table">
                        <thead>
                            <tr>
                                <th><?php echo phoenix_text('my_addons.col_addon'); ?></th>
                                <th><?php echo phoenix_text('my_addons.col_qty'); ?></th>
                                <th><?php echo phoenix_text('my_addons.col_billing'); ?></th>
                                <th><?php echo phoenix_text('my_addons.col_amount'); ?></th>
                                <th><?php echo phoenix_text('my_addons.col_renewal'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($instance_addon_subs as $_asub):
                            $_np     = $_asub->get_date('next_payment');
                            $_period = $_asub->get_billing_period() === 'year'
                                ? phoenix_text('my_addons.period_yearly')
                                : phoenix_text('my_addons.period_monthly');

                            foreach ($_asub->get_items() as $_ai): ?>

                            <?php
                            $_pending_yearly = function_exists('phoenix_addon_pending_yearly_conversion')
                                && phoenix_addon_pending_yearly_conversion($_asub);
                            $_yearly_price = $_pending_yearly && function_exists('phoenix_addon_pending_yearly_price')
                                ? phoenix_addon_pending_yearly_price($_asub) : 0;
                            $_conv_date = $_pending_yearly && function_exists('phoenix_addon_pending_conversion_date')
                                ? phoenix_addon_pending_conversion_date($_asub) : '';
                            ?>

                            <tr<?php if ($_pending_yearly): ?> style="background:#fffbf0;"<?php endif; ?>>
                                <td style="font-weight:600;color:#2c3e50;">
                                    <?php echo esc_html($_ai->get_name()); ?>
                                    <?php if ($_pending_yearly): ?>
                                        <div style="margin-top:4px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                            <span style="display:inline-block;padding:2px 8px;background:#e3f2fd;color:#1565c0;border-radius:20px;font-size:10px;font-weight:700;"><?php echo phoenix_text('my_addons.badge_upgrading_yearly'); ?></span>
                                            <?php if ($_conv_date): ?>
                                                <span style="font-size:11px;color:#888;"><?php echo sprintf(phoenix_text('my_addons.renewal_on'), esc_html(date('d M Y', strtotime($_conv_date)))); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo (int)$_ai->get_quantity(); ?></td>
                                <td>
                                    <?php if ($_pending_yearly): ?>
                                        <span style="text-decoration:line-through;color:#bbb;font-size:11px;"><?php echo esc_html($_period); ?></span>
                                        <span style="color:#1565c0;font-weight:700;font-size:11px;display:block;">→ <?php echo phoenix_text('my_addons.period_yearly'); ?></span>
                                    <?php else: ?>
                                        <?php echo esc_html($_period); ?>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight:700;color:#27ae60;">
                                    <?php if ($_pending_yearly && $_yearly_price > 0): ?>
                                        <span style="text-decoration:line-through;color:#bbb;font-size:11px;"><?php echo wc_price((float)$_asub->get_total()); ?>/mo</span>
                                        <span style="display:block;"><?php echo wc_price($_yearly_price); ?>/yr</span>
                                    <?php else: ?>
                                        <?php echo wc_price((float)$_asub->get_total()); ?>
                                    <?php endif; ?>
                                </td>
                                <td style="color:#666;"><?php echo $_np ? date('d M Y', strtotime($_np)) : '—'; ?></td>
                            </tr>

                        <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <?php if (!empty($addon_products)): ?>
                    <div class="addon-section-title">🛒 Available Add-ons</div>
                    <div class="available-addons-grid">
                    <?php foreach ($addon_products as $product):
                        $pid        = $product->get_id();
                        $pname      = $product->get_name();
                        $pdesc_full = wp_strip_all_tags($product->get_short_description() ?: $product->get_description());
                        $pdesc      = wp_trim_words($pdesc_full, 18);
                        $pprice     = $product->get_price();
                        $pperiod    = 'mo';
                        $purl       = add_query_arg(['tenant_uuid' => $canonical_uuid], get_permalink($pid));

                        if ($product->is_type('variable-subscription') || $product->is_type('variable')) {
                            $target_period = $is_yearly ? 'year' : 'month';
                            foreach ($product->get_children() as $_var_id) {
                                $_var = wc_get_product($_var_id);
                                if (!$_var || !$_var->is_purchasable()) continue;
                                if (get_post_meta($_var_id, '_subscription_period', true) === $target_period) {
                                    $pprice  = $_var->get_price();
                                    $pperiod = $is_yearly ? 'year' : 'month';
                                    break;
                                }
                            }
                        } elseif ($product->is_type('subscription')) {
                            if (get_post_meta($pid, '_subscription_period', true) === 'year') $pperiod = 'yr';
                        }

                        $allowed_plans = get_post_meta($pid, '_addon_allowed_plans', true);
                        $allowed_arr   = $allowed_plans ? array_map('trim', explode(',', $allowed_plans)) : [];
                        $plan_allowed  = empty($allowed_arr);
                        if (!$plan_allowed) {
                            foreach ($allowed_arr as $allowed) {
                                if (stripos($allowed, $plan_slug) !== false) { $plan_allowed = true; break; }
                            }
                        }
                        if (!$plan_allowed) continue;

                        $is_sold_individually = $product->is_sold_individually();
                        $is_already_owned     = false;
                        if ($is_sold_individually) {
                            foreach ($instance_addon_subs as $_osub) {
                                foreach ($_osub->get_items() as $_oitem) {
                                    if ($_oitem->get_product_id() === $pid) { $is_already_owned = true; break 2; }
                                    $children = $product->get_children();
                                    if (!empty($children) && in_array($_oitem->get_product_id(), $children, true)) {
                                        $is_already_owned = true; break 2;
                                    }
                                }
                            }
                        }
                    ?>
                        <div class="addon-product-card">
                            <div class="addon-product-name"><?php echo esc_html($pname); ?></div>
                            <div class="addon-product-desc">
                                <?php echo esc_html($pdesc); ?>
                                <?php if (strlen($pdesc_full) > strlen($pdesc)): ?>
                                <button class="addon-readmore-btn"
                                    onclick="phoenixAddonModal(this)"
                                    data-name="<?php echo esc_attr($pname); ?>"
                                    data-desc="<?php echo esc_attr($pdesc_full); ?>"><?php echo phoenix_text('my_addons.read_more'); ?></button>
                                <?php endif; ?>
                            </div>
                            <div class="addon-product-footer">
                                <div class="addon-product-price">
                                    <?php if ($pprice): echo wc_price($pprice) . '/' . $pperiod; else: ?>
                                    <span style="color:#999;">—</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($is_already_owned): ?>
                                    <span class="addon-product-btn already"><?php echo phoenix_text('my_addons.btn_active'); ?></span>
                                <?php else: ?>
                                    <a href="<?php echo esc_url($purl); ?>" class="addon-product-btn"><?php echo phoenix_text('my_addons.btn_add'); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="addon-section-title">🎨 Theme</div>
                <?php
                $t_settings_parsed = [];
                if (!empty($tenant->tenant_settings)) {
                    $t_parsed = @unserialize($tenant->tenant_settings);
                    if (is_array($t_parsed)) $t_settings_parsed = $t_parsed;
                }
                $active_themes = (!empty($t_settings_parsed['themes']) && is_array($t_settings_parsed['themes']))
                    ? $t_settings_parsed['themes'] : [];
                ?>
                <?php if (!empty($active_themes)): ?>
                <div class="active-themes-grid">
                    <?php foreach ($active_themes as $tsku):
                        $t_pid  = wc_get_product_id_by_sku($tsku);
                        $t_prod = $t_pid ? wc_get_product($t_pid) : null;
                        $t_name = $t_prod ? $t_prod->get_name() : ucwords(str_replace(['-','_'], ' ', $tsku));
                        $t_img  = $t_prod ? get_the_post_thumbnail_url($t_pid, 'thumbnail') : '';
                    ?>
                    <div class="active-theme-card">
                        <div class="active-theme-glow"></div>
                        <?php if ($t_img): ?>
                            <img src="<?php echo esc_url($t_img); ?>" alt="<?php echo esc_attr($t_name); ?>" class="active-theme-img">
                        <?php else: ?>
                            <div class="active-theme-placeholder">🎨</div>
                        <?php endif; ?>
                        <div class="active-theme-info">
                            <div class="active-theme-name"><?php echo esc_html($t_name); ?></div>
                            <div class="active-theme-badge"><span class="active-theme-dot"></span> <?php echo phoenix_text('my_addons.theme_active_label'); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!$is_premium): ?>
                <div class="themes-cta-box">
                    <div class="themes-cta-text">
                        <?php echo !empty($active_themes) ? phoenix_text('my_addons.theme_add_more') : phoenix_text('my_addons.theme_browse_txt'); ?>
                    </div>
                    <a href="<?php echo esc_url(add_query_arg(['tenant_uuid' => $canonical_uuid], home_url('/product-category/theme/'))); ?>" class="themes-cta-btn">
                        🎨 Browse Theme →
                    </a>
                </div>
                <?php endif; ?>

                <?php endif; // end BSW check ?>

            <?php endif; ?>
 
        </div>
    </div>
 
    <?php endforeach; ?>
 
    <a href="<?php echo esc_url(wc_get_account_endpoint_url('workspaces')); ?>" class="back-link"><?php echo phoenix_text('my_addons.back_link'); ?></a>
 
    <?php
}