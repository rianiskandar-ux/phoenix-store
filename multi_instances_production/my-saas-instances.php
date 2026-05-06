
/**
 * SNIPPET: My SaaS Workspaces - v8 + Channel Settings Fix
 *
 * v8 original:
 * + WIZARD GATE: User wajib selesaikan setup wizard sebelum bisa upgrade
 *   - Upgrade card disabled (abu-abu) + JS alert + buka wizard
 *   - Fail open: API error → upgrade tetap boleh
 *
 * + FIX CHANNEL SETTINGS (merged):
 *   Setelah upgrade Basic→Premium, channel data di card tidak update.
 *   Fix 1: cari settings dari row dengan _level tertinggi yang active di group.
 *   Fallback ke wbs-saas-wp/config/wc.php kalau settings tidak match plan.
 *
 * + FIX ADDON CHANNEL COUNT:
 *   Channel count (Email, Phone, Post Mail, dll) tidak include addon yang dibeli.
 *   Fix: loop addon subscription aktif milik instance ini, tambahkan qty-nya
 *   ke base settings. Contoh: Basic email=1 + addon Email Inbox qty 3 = 4 total.
 */

// =============================================================================
// 0. WIZARD GATE HELPER
// =============================================================================
if (!function_exists('phoenix_is_wizard_complete')) {
    function phoenix_is_wizard_complete($sub_id = 0, $user_id = 0) {
        global $wpdb;
        if (!$user_id) $user_id = get_current_user_id();
        if (!$user_id) return true;
        $uuid = ''; $location = '';
        if ($sub_id) {
            $row = $wpdb->get_row($wpdb->prepare(
                "SELECT tenant_uuid, tenant_location FROM {$wpdb->prefix}wbssaas_tenants
                 WHERE subscription_wc_id = %d AND customer_id = %d LIMIT 1",
                $sub_id, $user_id
            ));
            if ($row) { $uuid = $row->tenant_uuid ?? ''; $location = $row->tenant_location ?? ''; }
        }
        if (empty($uuid)) {
            $row = $wpdb->get_row($wpdb->prepare(
                "SELECT tenant_uuid, tenant_location FROM {$wpdb->prefix}wbssaas_tenants
                 WHERE customer_id = %d AND tenant_uuid != '' AND tenant_uuid IS NOT NULL
                 ORDER BY created ASC LIMIT 1",
                $user_id
            ));
            if ($row) { $uuid = $row->tenant_uuid ?? ''; $location = $row->tenant_location ?? ''; }
        }
        if (empty($uuid)) return true;
        $location = strtolower(trim($location));
        if (!in_array($location, ['staging','switzerland','singapore','indonesia'])) return true;
        $cache_key   = 'phoenix_migrated_' . md5($uuid);
        $is_migrated = get_transient($cache_key);
        if ($is_migrated === false) {
            try {
                if (!class_exists('\WBSSaaS\PhoenixAPI')) return true;
                $api      = new \WBSSaaS\PhoenixAPI($location);
                $response = $api->checkMigration($uuid);
                $is_migrated = ($response && isset($response->data->migrate_status))
                    ? ($response->data->migrate_status ? 'yes' : 'no')
                    : null;
                if ($is_migrated === null) return true;
            } catch (Exception $e) {
                error_log('phoenix_is_wizard_complete error: ' . $e->getMessage());
                return true;
            }
            set_transient($cache_key, $is_migrated, $is_migrated === 'yes' ? DAY_IN_SECONDS : 15 * MINUTE_IN_SECONDS);
        }
        return ($is_migrated === 'yes');
    }
}

if (!function_exists('phoenix_wpml_url')) {
    function phoenix_wpml_url($url) {
        return apply_filters('wpml_permalink', $url, apply_filters('wpml_current_language', null));
    }
}

// =============================================================================
// 1. ENDPOINT
// =============================================================================
add_action('init', 'add_my_saas_endpoint');
function add_my_saas_endpoint() {
    add_rewrite_endpoint('workspaces', EP_ROOT | EP_PAGES);
}

// =============================================================================
// 2. MENU ITEM
// =============================================================================
add_filter('woocommerce_account_menu_items', 'add_my_saas_menu_item');
function add_my_saas_menu_item($items) {
    $new = [];
    foreach ($items as $key => $label) {
        $new[$key] = $label;
        if ($key === 'orders') {
            $new['workspaces'] = function_exists('phoenix_text') ? phoenix_text('my_saas.menu_label') : 'My Workspaces';
        }
    }
    return $new;
}

// =============================================================================
// 3. CANCEL BUTTON CONTROL
// =============================================================================
add_filter('wcs_view_subscription_actions', 'phoenix_control_cancel_button', 10, 2);
function phoenix_control_cancel_button($actions, $subscription) {
    if (!isset($actions['cancel'])) return $actions;
    $plan_level = function_exists('phoenix_get_subscription_plan_level')
        ? phoenix_get_subscription_plan_level($subscription) : 0;
    if ($plan_level <= 1) return $actions;
    $is_yearly = function_exists('phoenix_is_yearly_subscription')
        ? phoenix_is_yearly_subscription($subscription) : false;
    if ($is_yearly) {
        $np   = $subscription->get_date('next_payment');
        $days = $np ? floor((strtotime($np) - time()) / DAY_IN_SECONDS) : 0;
        if ($days > 30) unset($actions['cancel']);
    } else {
        $progress = function_exists('phoenix_get_commitment_progress')
            ? phoenix_get_commitment_progress($subscription)
            : ['months' => 0, 'complete' => false];
        if (!$progress['complete'] && $progress['months'] < 11) unset($actions['cancel']);
    }
    return $actions;
}

// =============================================================================
// 4. PAGE CONTENT
// =============================================================================
add_action('wp_ajax_phoenix_clear_wizard_cache', 'phoenix_clear_wizard_cache_handler');
function phoenix_clear_wizard_cache_handler() {
    check_ajax_referer('phoenix_wizard_nonce', 'nonce');
    $uuid = isset($_POST['uuid']) ? sanitize_text_field($_POST['uuid']) : '';
    if ($uuid) delete_transient('phoenix_migrated_' . md5($uuid));
    wp_send_json_success();
}

add_action('woocommerce_account_workspaces_endpoint', 'render_my_saas_page');
function render_my_saas_page() {
    if (!is_user_logged_in()) { echo '<p>' . phoenix_text('my_saas.login_required') . '</p>'; return; }

    if (isset($_GET['wizard_done']) && $_GET['wizard_done'] === '1') {
        global $wpdb;
        $rows = $wpdb->get_col($wpdb->prepare(
            "SELECT tenant_uuid FROM {$wpdb->prefix}wbssaas_tenants WHERE customer_id = %d",
            get_current_user_id()
        ));
        foreach ($rows as $uuid) delete_transient('phoenix_migrated_' . md5($uuid));
    }

    global $wpdb;
    $user_id       = get_current_user_id();
    $table_tenants = $wpdb->prefix . 'wbssaas_tenants';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_tenants'") !== $table_tenants) {
        echo '<div style="background:#fff3cd;padding:15px;border-radius:5px;border-left:4px solid #ffc107;">' . phoenix_text('my_saas.system_error') . '</div>';
        return;
    }

    $all_rows = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_tenants WHERE customer_id = %d ORDER BY created DESC", $user_id
    ));

    if (empty($all_rows)) {
        echo '<div style="background:#f0f7ff;padding:24px;border-radius:10px;text-align:center;border:1px solid #3498db;">
            <h3 style="margin:0 0 10px;color:#1565c0;">' . phoenix_text('my_saas.empty_title') . '</h3>
            <a href="' . esc_url(phoenix_wpml_url(home_url('/pricing/'))) . '" style="display:inline-block;padding:10px 22px;background:#3498db;color:#fff;border-radius:6px;font-weight:600;">' . phoenix_text('my_saas.empty_cta') . '</a>
        </div>';
        return;
    }

    foreach ($all_rows as $row) {
        $sid          = (int) $row->subscription_wc_id;
        $sub          = ($sid && function_exists('wcs_get_subscription')) ? wcs_get_subscription($sid) : null;
        $row->_sub    = $sub;
        $row->_status = $sub ? $sub->get_status() : 'unknown';
        $row->_level  = ($sub && function_exists('phoenix_get_subscription_plan_level'))
            ? phoenix_get_subscription_plan_level($sub) : 0;
        $row->_np     = $sub ? $sub->get_date('next_payment') : '';
    }

    $grouped = [];
    foreach ($all_rows as $row) {
        $key = trim(strtolower($row->tenant_name));
        $grouped[$key][] = $row;
    }

    $active_instances = $inactive_instances = [];
    foreach ($grouped as $group) {
        $active_rows = array_filter($group, fn($r) => $r->_status === 'active');
        $oldest_row  = array_values($group)[count($group) - 1];
        if (!empty($active_rows)) {
            usort($active_rows, fn($a, $b) => $b->_level - $a->_level);
            $d = array_values($active_rows)[0];
            $d->_oldest = $oldest_row;
            $active_instances[] = $d;
        } else {
            $d = array_values($group)[0];
            $d->_oldest = $oldest_row;
            $inactive_instances[] = $d;
        }
    }

    $addon_map = [];
    $all_user_subs = function_exists('wcs_get_users_subscriptions') ? wcs_get_users_subscriptions($user_id) : [];
    foreach ($all_user_subs as $asub) {
        if (!$asub->has_status('active')) continue;
        $is_addon = false;
        foreach ($asub->get_items() as $ai) { if (has_term('add-on','product_cat',$ai->get_product_id())) { $is_addon=true; break; } }
        if (!$is_addon) continue;
        $np = $asub->get_date('next_payment');
        $k  = $np ? date('Y-m-d', strtotime($np)) : 'no_date';
        $addon_map[$k] = ($addon_map[$k] ?? 0) + 1;
    }

    $has_cancelled = !empty($inactive_instances);
    ?>

    <h2 style="margin-bottom:5px;"><?php echo phoenix_text('my_saas.page_title'); ?></h2>
    <p style="color:#666;margin-bottom:20px;font-size:14px;"><?php echo phoenix_text('my_saas.page_subtitle'); ?></p>

    <?php if ($has_cancelled): ?>
    <div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <span style="font-size:13px;color:#666;">
            <?php $ac = count($active_instances); echo $ac . ' ' . ($ac !== 1 ? phoenix_text('my_saas.active_plural') : phoenix_text('my_saas.active_singular')); ?>
            · <span style="color:#999;"><?php echo count($inactive_instances); ?> <?php echo phoenix_text('my_saas.cancelled'); ?></span>
        </span>
        <button id="toggle-cancelled" onclick="toggleCancelled()"
            style="padding:6px 14px;border:1px solid #ddd;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;background:#fff;color:#666;">
            <?php echo phoenix_text('my_saas.show_cancelled'); ?>
        </button>
    </div>
    <?php endif; ?>

    <style>
    .saas-row{display:flex;align-items:stretch;gap:12px;margin-bottom:16px}
    .saas-card{background:#fff;border:1px solid #e0e0e0;border-radius:10px;flex:1;min-width:0;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,.05);transition:box-shadow .2s}
    .saas-card:hover{box-shadow:0 4px 14px rgba(0,0,0,.09)}
    .saas-card.cancelled-card{opacity:.7;border-style:dashed}
    .saas-row.cancelled-row{display:none}
    .saas-upgrade-card{flex-shrink:0;width:110px;position:relative;align-self:stretch}
    .saas-upgrade-card-inner{position:sticky;top:70px;height:110px;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(44,62,80,.2)}
    .saas-upgrade-card a{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;width:100%;height:100%;text-decoration:none;border-radius:10px;font-size:12px;font-weight:700;padding:10px 8px;text-align:center;background:linear-gradient(135deg,#2c3e50 0%,#34495e 100%);color:#fff;transition:opacity .2s}
    .saas-upgrade-card a.enterprise{background:linear-gradient(135deg,#2c3e50 0%,#34495e 100%)}
    .saas-upgrade-card a:hover{opacity:.88;color:#fff}
    .saas-upgrade-card a.upg-wizard-required{background:linear-gradient(135deg,#95a5a6 0%,#7f8c8d 100%);cursor:default;opacity:.85}
    .saas-upgrade-card a.upg-wizard-required:hover{opacity:.85}
    .saas-upgrade-card .upg-icon{font-size:20px}
    .saas-card-header{padding:14px 18px;cursor:pointer;display:flex;align-items:center;gap:12px;border-bottom:1px solid transparent;transition:background .15s}
    .saas-card-header:hover{background:#fafafa}
    .saas-card-header.open{border-bottom-color:#f0f0f0}
    .saas-card-icon{width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
    .saas-card-icon.free{background:#f5f5f5}.saas-card-icon.basic{background:#e3f2fd}.saas-card-icon.premium{background:#fff8e1}.saas-card-icon.unknown{background:#f5f5f5}
    .saas-card-meta{min-width:0;flex:1}
    .saas-card-name{font-size:14px;font-weight:700;color:#2c3e50;margin:0 0 3px}
    .saas-card-url{font-size:12px;color:#999;margin:0}
    .saas-card-sub{font-size:12px;color:#aaa;margin-top:3px;display:flex;align-items:center;gap:6px;flex-wrap:wrap}
    .saas-status-badge{margin-left:auto;flex-shrink:0}
    .badge{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700}
    .badge-free{background:#f5f5f5;color:#666}.badge-basic{background:#e3f2fd;color:#1565c0}.badge-premium{background:#fff8e1;color:#f57c00}
    .badge-active{background:#e8f5e9;color:#2e7d32}.badge-expiring-soon{background:#fff8e1;color:#f57c00}.badge-on-hold{background:#fff3e0;color:#ef6c00}
    .badge-expired{background:#ffebee;color:#c62828}.badge-cancelled{background:#ffebee;color:#c62828}.badge-pending-cancel{background:#fff3e0;color:#ef6c00}.badge-unknown{background:#f5f5f5;color:#999}
    .saas-chevron{color:#ccc;font-size:12px;transition:transform .2s;flex-shrink:0}
    .saas-chevron.open{transform:rotate(180deg);color:#3498db}
    .saas-card-body{display:none;padding:18px}.saas-card-body.open{display:block}
    .saas-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px}
    @media(max-width:600px){.saas-info-grid{grid-template-columns:1fr}}
    .saas-info-item{background:#f8f9fa;border-radius:6px;padding:10px 14px}
    .saas-info-label{font-size:11px;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px}
    .saas-info-value{font-size:13px;color:#2c3e50;font-weight:600}.saas-info-value a{color:#3498db;text-decoration:none}
    .setup-wizard-banner{background:#fff8e1;border:1px solid #ffcc02;border-left:4px solid #f39c12;border-radius:8px;padding:13px 16px;margin-bottom:16px;display:flex;align-items:flex-start;gap:12px}
    .setup-wizard-banner .swb-icon{font-size:20px;flex-shrink:0;margin-top:1px}
    .setup-wizard-banner .swb-body{flex:1;min-width:0}
    .setup-wizard-banner .swb-title{font-size:13px;font-weight:700;color:#b26a00;margin:0 0 4px}
    .setup-wizard-banner .swb-desc{font-size:12px;color:#666;margin:0 0 10px;line-height:1.5}
    .setup-wizard-banner .swb-btn{display:inline-block;background:#f39c12;color:#fff;text-decoration:none;font-size:12px;font-weight:700;padding:7px 14px;border-radius:5px}
    .setup-wizard-banner .swb-btn:hover{background:#e67e22;color:#fff}
    .saas-section{margin-bottom:14px}
    .saas-section-title{font-size:12px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;padding-bottom:5px;border-bottom:1px solid #f0f0f0}
    .saas-channel-list{list-style:none;padding:0;margin:0;font-size:13px;color:#444}
    .saas-channel-list li{padding:4px 0;display:flex;align-items:center;gap:8px}
    .saas-channel-list li .dot{width:6px;height:6px;border-radius:50%;background:#ddd;flex-shrink:0}
    .saas-channel-list li .dot.on{background:#27ae60}
    .not-included{color:#bbb;font-style:italic}
    .saas-section-cta{display:inline-block;margin-top:8px;padding:4px 10px;background:#f8f9fa;border:1px solid #dee2e6;border-radius:4px;font-size:11px;font-weight:600;color:#3498db;text-decoration:none}
    .saas-section-cta:hover{background:#e3f2fd;border-color:#3498db}
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.saas-card-header').forEach(function(header, i) {
            header.addEventListener('click', function() {
                var body = this.parentElement.querySelector('.saas-card-body');
                var chev = this.querySelector('.saas-chevron');
                var open = body.classList.contains('open');
                body.classList.toggle('open', !open);
                chev.classList.toggle('open', !open);
                this.classList.toggle('open', !open);
            });
            if (i === 0) header.click();
        });
    });

    var cancelledVisible = false;
    function toggleCancelled() {
        cancelledVisible = !cancelledVisible;
        document.querySelectorAll('.cancelled-row').forEach(function(r) { r.style.display = cancelledVisible ? 'flex' : 'none'; });
        var btn = document.getElementById('toggle-cancelled');
        if (btn) { btn.textContent = cancelledVisible ? '<?php echo esc_js(phoenix_text('my_saas.hide_cancelled')); ?>' : '<?php echo esc_js(phoenix_text('my_saas.show_cancelled')); ?>'; btn.style.color = cancelledVisible ? '#c62828' : '#666'; btn.style.borderColor = cancelledVisible ? '#ffcdd2' : '#ddd'; }
    }

    function phoenixOpenInstanceOnLoad(anchor) { sessionStorage.setItem('phoenix_open_instance', anchor); }
    window.addEventListener('DOMContentLoaded', function() {
        var t = sessionStorage.getItem('phoenix_open_instance');
        if (t) { sessionStorage.removeItem('phoenix_open_instance'); var el = document.getElementById(t); if (el) { setTimeout(function() { el.scrollIntoView({behavior:'smooth',block:'start'}); var h = el.querySelector('.saas-card-header'); if (h && !h.classList.contains('open')) h.click(); }, 200); } }
    });

    function phoenixWizardClicked(el) {
        var uuid = el.getAttribute('data-uuid'), nonce = el.getAttribute('data-nonce');
        if (!uuid || !nonce) return;
        var fd = new FormData(); fd.append('action','phoenix_clear_wizard_cache'); fd.append('uuid',uuid); fd.append('nonce',nonce);
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {method:'POST',body:fd}).catch(function(){});
        var banner = el.closest('.setup-wizard-banner');
        if (banner) { el.textContent = '<?php echo esc_js(phoenix_text('my_saas.wizard_opened')); ?>'; el.style.background = '#888'; el.style.pointerEvents = 'none'; }
    }

    function phoenixUpgradeGated(el) {
        var wizardUrl = el.getAttribute('data-wizard-url');
        var msg = '<?php echo esc_js(phoenix_text('my_saas.wizard_gate_title')); ?>\n\n<?php echo esc_js(phoenix_text('my_saas.wizard_gate_msg')); ?>';
        if (confirm(msg)) {
            window.open(wizardUrl, '_blank');
            var uuid = el.getAttribute('data-uuid'), nonce = el.getAttribute('data-nonce');
            if (uuid && nonce) { var fd = new FormData(); fd.append('action','phoenix_clear_wizard_cache'); fd.append('uuid',uuid); fd.append('nonce',nonce); fetch('<?php echo admin_url('admin-ajax.php'); ?>',{method:'POST',body:fd}).catch(function(){}); }
        }
        return false;
    }
    </script>

    <?php
    $all_display = array_merge(
        array_map(fn($r) => ['row'=>$r,'is_cancelled'=>false], $active_instances),
        array_map(fn($r) => ['row'=>$r,'is_cancelled'=>true],  $inactive_instances)
    );

    foreach ($all_display as $entry):
        $tenant       = $entry['row'];
        $is_cancelled = $entry['is_cancelled'];
        $sub_id         = (int) $tenant->subscription_wc_id;
        $tenant_name    = $tenant->tenant_name;
        $tenant_url_raw = rtrim($tenant->_oldest->tenant_url ?? $tenant->tenant_url ?? '', '/');
        $tenant_url     = preg_replace('/\.stg\./i', '.', $tenant_url_raw);
        $anchor         = 'instance-' . ($tenant->id ?? $sub_id);

        // ── Resolve tenant_settings: merge nilai tertinggi per channel ───────
        // Plugin WBS SaaS update tenant_settings di DB setiap kali addon dibeli.
        // Post-upgrade: row baru (Premium) punya default package + addon baru,
        // row lama (Basic/cancelled) punya addon yang dibeli sebelum upgrade.
        // Solusi: ambil nilai TERTINGGI per channel dari semua rows.
        // Contoh: Basic row postmail=4 (addon), Premium row postmail=1 (default) → pakai 4.
        // Khusus untuk keys yang hanya ada di plan tertentu (chat=Premium only):
        // pakai nilai dari row plan tertinggi.
        $settings     = [];
        $group_rows   = $grouped[trim(strtolower($tenant->tenant_name))] ?? [$tenant];
        $active_level = $tenant->_level;

        // Load default Premium/Basic package untuk baseline 'chat' dan 'webforms'
        $wc_cfg_base  = [];
        $wc_cfg_bpath = WP_PLUGIN_DIR . '/wbs-saas-wp/config/wc.php';
        if (file_exists($wc_cfg_bpath)) {
            $wc_cfg_tmp  = include $wc_cfg_bpath;
            $plan_key_b  = $active_level >= 3 ? 'premium' : ($active_level >= 2 ? 'basic' : 'free');
            $wc_cfg_base = $wc_cfg_tmp['subscription'][$plan_key_b]['default_package'] ?? [];
        }

        // Start dari default package plan aktif sebagai baseline
        if (!empty($wc_cfg_base)) $settings = $wc_cfg_base;

        // Loop semua rows, ambil nilai max per channel
        foreach ($group_rows as $grow) {
            if (empty($grow->tenant_settings)) continue;
            $pg = @unserialize($grow->tenant_settings);
            if (!is_array($pg)) continue;

            $row_level = $grow->_level ?? 0;

            // Channel numerik: ambil max
            foreach (['phone','email','im','postmail','chat','mobileapp','languages'] as $ck) {
                $cur = (int)($settings[$ck] ?? 0);
                $new = (int)($pg[$ck] ?? 0);

                // 'chat' hanya valid dari row Premium (level 3)
                // jangan pakai chat=0 dari Basic row untuk override Premium default
                if ($ck === 'chat' && $row_level < 3) continue;

                if ($new > $cur) $settings[$ck] = $new;
            }

            // Users: ambil max per role
            foreach (['manager','operator','agent'] as $role) {
                $cur = (int)($settings['users'][$role] ?? 0);
                $new = (int)($pg['users'][$role] ?? 0);
                if ($new > $cur) $settings['users'][$role] = $new;
            }

            // Webforms: pakai dari row level tertinggi
            if (!empty($pg['webforms']) && is_array($pg['webforms']) && $row_level >= $active_level) {
                $settings['webforms'] = $pg['webforms'];
            }

            // Themes: gabungkan semua themes dari semua rows
            if (!empty($pg['themes']) && is_array($pg['themes'])) {
                $existing = $settings['themes'] ?? [];
                $settings['themes'] = array_unique(array_merge($existing, $pg['themes']));
            }
        }
        // ── END settings resolution ───────────────────────────────────────────

        $subscription = $tenant->_sub; $plan_level = $tenant->_level; $status = $tenant->_status;
        $plan_name = 'Unknown Plan'; $plan_slug = 'unknown'; $next_payment = '-';
        $next_payment_raw = ''; $expiration = '-'; $billing_period = ''; $is_yearly = false;
        $start_time = 0; $active_sub_id = $sub_id; $active_item_id = 0;

        if ($subscription) {
            $active_sub_id = $subscription->get_id();
            foreach ($subscription->get_items() as $item) {
                if (has_term('add-on','product_cat',$item->get_product_id())) continue;
                $plan_name = $item->get_name(); $active_item_id = $item->get_id(); break;
            }
            if ($plan_level >= 3) $plan_slug = 'premium';
            elseif ($plan_level >= 2) $plan_slug = 'basic';
            elseif ($plan_level >= 1) $plan_slug = 'free';
            $is_yearly      = function_exists('phoenix_is_yearly_subscription') ? phoenix_is_yearly_subscription($subscription) : false;
            $billing_period = $is_yearly ? phoenix_text('my_saas.billing_yearly') : phoenix_text('my_saas.billing_monthly');
            $start_time     = $subscription->get_time('start');
            $np_raw = $subscription->get_date('next_payment');
            if ($np_raw) {
                $next_payment_raw = $np_raw;
                $days_diff        = floor((strtotime($np_raw) - time()) / DAY_IN_SECONDS);
                $next_payment     = $days_diff . ' days (' . date('d M Y', strtotime($np_raw)) . ')';
            }
            $end_date   = $subscription->get_date('end');
            // Renewal = next_payment date (auto-renew subscription)
            // Bukan start+1year karena next_payment sudah reflect extend dari prorate upgrade
            $expiration = $next_payment_raw
                ? date('d F Y', strtotime($next_payment_raw))
                : ($end_date ? date('d F Y', strtotime($end_date)) : '-');
        }

        $show_commitment = false; $commitment = ['months'=>0,'percentage'=>0,'complete'=>false]; $commitment_end = '';
        if ($plan_level >= 2 && !$is_yearly && $status === 'active' && $start_time) {
            $commitment = function_exists('phoenix_get_commitment_progress') ? phoenix_get_commitment_progress($subscription) : $commitment;
            $months_left = max(0, 12 - $commitment['months']);
            $commitment_end = date('F d, Y', strtotime('+' . $months_left . ' months', time()));
            $show_commitment = true;
        }

        $addon_count = 0;
        if ($next_payment_raw) { $np_key = date('Y-m-d', strtotime($next_payment_raw)); $addon_count = $addon_map[$np_key] ?? 0; }

        $s_phone    = (int)($settings['phone']               ?? 0);
        $s_email    = (int)($settings['email']               ?? 0);
        $s_im       = (int)($settings['im']                  ?? 0);
        $s_postmail = (int)($settings['postmail']            ?? 0);
        $s_chat     = (int)($settings['chat']                ?? 0);
        $s_mobile   = (int)($settings['mobileapp']           ?? 0);
        $s_manager  = (int)($settings['users']['manager']    ?? 0);
        $s_operator = (int)($settings['users']['operator']   ?? 0);
        $s_agent    = (int)($settings['users']['agent']      ?? 0);
        $s_languages= (int)($settings['languages']           ?? 1);

        $fmt = function($n, $singular, $plural = null) {
            if (!$plural) $plural = $singular . 's';
            if ($n >= 999) return sprintf(phoenix_text('my_saas.fmt_unlimited'), $plural);
            if ($n === 1)  return sprintf(phoenix_text('my_saas.fmt_one'), $singular);
            if ($n === 0)  return '<span class="not-included">' . phoenix_text('my_saas.channel_not_included') . '</span>';
            return sprintf(phoenix_text('my_saas.fmt_up_to'), $n, $plural);
        };

        $view_sub_url      = $active_sub_id ? wc_get_endpoint_url('view-subscription', $active_sub_id, wc_get_page_permalink('myaccount')) : '#';
        $manage_addons_url = phoenix_wpml_url(wc_get_account_endpoint_url('addons')) . '?instance=' . $active_sub_id . '#' . $anchor;
        $icons = ['free'=>'🌱','basic'=>'📦','premium'=>'⭐','unknown'=>'🏢'];
        $icon  = $icons[$plan_slug] ?? '🏢';

        // ── Wizard Banner ─────────────────────────────────────────────────────
        $show_wizard_banner = false; $wizard_url = '';
        $wizard_uuid     = ($tenant->_oldest->tenant_uuid ?? '') ?: ($tenant->tenant_uuid ?? '');
        $wizard_location = strtolower($tenant->_oldest->tenant_location ?? $tenant->tenant_location ?? '');

        if ($status === 'active' && !empty($wizard_uuid) && in_array($wizard_location, ['staging','switzerland','singapore','indonesia'])) {
            $cache_key   = 'phoenix_migrated_' . md5($wizard_uuid);
            $is_migrated = get_transient($cache_key);
            if ($is_migrated === false) {
                try {
                    $api = new \WBSSaaS\PhoenixAPI($wizard_location);
                    $res = $api->checkMigration($wizard_uuid);
                    $is_migrated = ($res && isset($res->data->migrate_status)) ? ($res->data->migrate_status ? 'yes' : 'no') : 'no';
                } catch (Exception $e) { $is_migrated = 'no'; }
                set_transient($cache_key, $is_migrated, $is_migrated === 'yes' ? DAY_IN_SECONDS : 15 * MINUTE_IN_SECONDS);
            }
            if ($is_migrated === 'no') {
                $show_wizard_banner = true;
                $wg_user   = get_user_by('id', $user_id);
                $wg_email  = $wg_user ? password_hash($wg_user->user_email, PASSWORD_DEFAULT) : '';
                $wizard_url = $tenant_url_raw . '/clients/new'
                    . '?u=' . urlencode($wizard_uuid)
                    . '&m=' . urlencode($wg_email);
            }
        }

        // ── Status badge ──────────────────────────────────────────────────────
        $days_to_next = $next_payment_raw ? (int)floor((strtotime($next_payment_raw) - time()) / DAY_IN_SECONDS) : 999;
        $status_label = ucfirst(str_replace('-',' ',$status));
        $status_badge = str_replace('_','-',$status);
        if ($status === 'active') {
            if ($plan_level <= 1) { $status_label = phoenix_text('my_saas.status_active'); $status_badge = 'active'; }
            else {
                $warning_days = $is_yearly ? 30 : 7;
                $is_locked    = (!$is_yearly && $show_commitment && !($commitment['complete'] ?? false));
                $is_new       = $start_time && (floor((time()-$start_time)/DAY_IN_SECONDS) < 3);
                if (!$is_locked && !$is_new && $days_to_next >= 0 && $days_to_next <= $warning_days) {
                    $status_label = phoenix_text('my_saas.status_expiring'); $status_badge = 'expiring-soon';
                } else { $status_label = phoenix_text('my_saas.status_active'); $status_badge = 'active'; }
            }
        }

        // ── Upgrade card ──────────────────────────────────────────────────────
        $opts_env = get_option('wbssaas_options');
        $is_stg   = isset($opts_env['environment']) && $opts_env['environment'] == 'staging';
        $upgrade_gated = $show_wizard_banner;

        if ($upgrade_gated) {
            $upgrade_url='#'; $upgrade_label=phoenix_text('my_saas.btn_setup_first'); $upgrade_icon='⚙️'; $upgrade_target='_self'; $upgrade_class='upg-wizard-required';
        } elseif ($plan_level >= 3) {
            $ent_base = $is_stg ? 'https://staging.phoenix-whistleblowing.com/contact-enterprise/?swcfpc=1' : 'https://phoenix-whistleblowing.com/contact-enterprise/';
            $upgrade_url=add_query_arg(['sub_id'=>$active_sub_id,'org'=>urlencode($tenant_name),'source'=>'my-account-upgrade'],$ent_base); $upgrade_label=phoenix_text('my_saas.upgrade_label'); $upgrade_icon='⬆️'; $upgrade_target='_blank'; $upgrade_class='enterprise';
        } else {
            $upg_page = get_page_by_path('upgrade-plan') ?: get_page_by_path('upgrade');
            $upg_base = phoenix_wpml_url($upg_page ? get_permalink($upg_page) : home_url('/upgrade-plan/'));
            $upgrade_url = add_query_arg('sub_id', $active_sub_id, $upg_base);
            $upgrade_label=phoenix_text('my_saas.upgrade_label'); $upgrade_icon='⬆️'; $upgrade_target='_self'; $upgrade_class='';
        }
    ?>

    <div class="saas-row <?php echo $is_cancelled ? 'cancelled-row' : ''; ?>" id="<?php echo esc_attr($anchor); ?>">
        <div class="saas-card <?php echo $is_cancelled ? 'cancelled-card' : ''; ?>">
        <div class="saas-card-header">
            <div class="saas-card-icon <?php echo $plan_slug; ?>"><?php echo $icon; ?></div>
            <div class="saas-card-meta">
                <div class="saas-card-name"><?php echo esc_html($tenant_name); ?></div>
                <div class="saas-card-url"><?php echo esc_html($tenant_url); ?></div>
                <?php if ($plan_slug !== 'unknown'): ?>
                <div class="saas-card-sub">
                    <span class="badge badge-<?php echo $plan_slug; ?>"><?php echo phoenix_text('my_saas.plan_' . $plan_slug); ?></span>
                    <?php if ($billing_period): ?><span style="color:#bbb;">·</span><span style="color:#aaa;"><?php echo $billing_period; ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="saas-status-badge"><span class="badge badge-<?php echo $status_badge; ?>"><?php echo $status_label; ?></span></div>
            <span class="saas-chevron">▼</span>
        </div>

        <div class="saas-card-body">
            <?php if ($show_wizard_banner): ?>
            <div class="setup-wizard-banner">
                <div class="swb-icon">⚙️</div>
                <div class="swb-body">
                    <div class="swb-title"><?php echo phoenix_text('my_saas.wizard_title'); ?> - <?php echo esc_html($tenant_name); ?></div>
                    <div class="swb-desc"><?php echo phoenix_text('my_saas.wizard_desc'); ?><br><strong style="color:#b26a00;"><?php echo phoenix_text('my_saas.wizard_desc2'); ?></strong></div>
                    <a href="<?php echo esc_url($wizard_url); ?>" target="_blank" class="swb-btn"
                       data-uuid="<?php echo esc_attr($wizard_uuid); ?>"
                       data-nonce="<?php echo wp_create_nonce('phoenix_wizard_nonce'); ?>"
                       onclick="phoenixWizardClicked(this)"><?php echo phoenix_text('my_saas.wizard_cta'); ?></a>
                    <div style="margin-top:8px;font-size:11px;color:#999;"><?php echo phoenix_text('my_saas.wizard_note'); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <div class="saas-info-grid">
                <div class="saas-info-item"><div class="saas-info-label"><?php echo phoenix_text('my_saas.label_website'); ?></div><div class="saas-info-value"><a href="<?php echo esc_url($tenant_url_raw); ?>" target="_blank"><?php echo esc_html($tenant_url); ?> ↗</a></div></div>
                <div class="saas-info-item"><div class="saas-info-label"><?php echo phoenix_text('my_saas.label_server'); ?></div><div class="saas-info-value"><?php echo esc_html(ucfirst($tenant->tenant_location ?: phoenix_text('my_saas.label_default'))); ?></div></div>
                <div class="saas-info-item"><div class="saas-info-label"><?php echo phoenix_text('my_saas.label_created'); ?></div><div class="saas-info-value"><?php echo date('d F Y', strtotime($tenant->created)); ?></div></div>
                <div class="saas-info-item">
                    <div class="saas-info-label"><?php echo phoenix_text('my_saas.label_subscription'); ?></div>
                    <div class="saas-info-value"><?php echo esc_html($plan_name); ?>
                        <?php if ($active_sub_id): ?><a href="<?php echo esc_url($view_sub_url); ?>" style="margin-left:6px;font-size:11px;"><?php echo phoenix_text('my_saas.view_link'); ?></a><?php endif; ?>
                    </div>
                </div>
                <div class="saas-info-item"><div class="saas-info-label"><?php echo phoenix_text('my_saas.label_next_payment'); ?></div><div class="saas-info-value">
                    <?php if ($plan_level <= 1 && $status === 'active'): ?><span style="color:#27ae60;"><?php echo phoenix_text('my_saas.free_no_billing'); ?></span><?php else: ?><?php echo $next_payment; ?><?php endif; ?>
                </div></div>
                <div class="saas-info-item"><div class="saas-info-label"><?php echo phoenix_text('my_saas.label_renewal'); ?></div><div class="saas-info-value"><?php echo $expiration; ?></div></div>
            </div>

            <div style="margin-bottom:14px;">
                <div style="font-size:11px;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;"><?php echo phoenix_text('my_saas.addons_section_label'); ?></div>
                <?php if ($plan_level <= 1 && $status === 'active'): ?>
                <div style="background:#f8f9fa;border-radius:6px;padding:10px 14px;"><span style="font-size:13px;color:#bbb;font-style:italic;"><?php echo phoenix_text('my_saas.addons_free_msg'); ?></span></div>
                <?php elseif ($plan_level >= 2 && $status === 'active'): ?>
                <div><a href="<?php echo esc_url($manage_addons_url); ?>" onclick="phoenixOpenInstanceOnLoad('<?php echo esc_js($anchor); ?>')"
                   style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;background:#27ae60;color:#fff;text-decoration:none;border-radius:6px;font-size:12px;font-weight:700;"
                   onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'"><?php echo phoenix_text('my_saas.addons_browse_btn'); ?></a></div>
                <?php endif; ?>
            </div>

            <div class="saas-section">
                <div class="saas-section-title"><?php echo phoenix_text('my_saas.channels_title'); ?></div>
                <ul class="saas-channel-list">
                    <li><span class="dot on"></span><strong><?php echo phoenix_text('my_saas.channel_webform'); ?>:</strong>&nbsp;<?php echo !empty($settings['webforms']) ? sprintf(phoenix_text('my_saas.channel_webform_choice'), implode(', ', array_map('esc_html', $settings['webforms']))) : phoenix_text('my_saas.channel_webform_std'); ?></li>
                    <li><span class="dot <?php echo $s_phone?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.channel_phone'); ?>:</strong>&nbsp;<?php echo $fmt($s_phone, __('number','wbs-saas-plugin')); ?></li>
                    <li><span class="dot <?php echo $s_email?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.channel_email'); ?>:</strong>&nbsp;<?php echo $fmt($s_email, __('secure inbox','wbs-saas-plugin'), __('secure inboxes','wbs-saas-plugin')); ?></li>
                    <li><span class="dot <?php echo $s_im?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.channel_im'); ?>:</strong>&nbsp;<?php echo $fmt($s_im, __('IM','wbs-saas-plugin')); ?></li>
                    <li><span class="dot <?php echo $s_postmail?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.channel_postmail'); ?>:</strong>&nbsp;<?php echo $fmt($s_postmail, __('address','wbs-saas-plugin'), __('addresses','wbs-saas-plugin')); ?></li>
                    <li><span class="dot <?php echo $s_chat?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.channel_chat'); ?>:</strong>&nbsp;<?php echo $fmt($s_chat, __('room','wbs-saas-plugin')); ?></li>
                    <li><span class="dot <?php echo $s_mobile?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.channel_mobileapp'); ?>:</strong>&nbsp;<?php echo $s_mobile ? '<span style="color:#27ae60;font-weight:600;">' . phoenix_text('my_saas.channel_mobile_active') . '</span>' : '<span class="not-included">' . phoenix_text('my_saas.channel_not_included') . '</span>'; ?></li>
                </ul>
                <?php if ($status==='active' && $plan_level>=2): ?><a href="<?php echo esc_url($manage_addons_url); ?>" onclick="phoenixOpenInstanceOnLoad('<?php echo esc_js($anchor); ?>')" class="saas-section-cta"><?php echo phoenix_text('my_saas.cta_buy_channel'); ?></a><?php endif; ?>
            </div>

            <div class="saas-section">
                <div class="saas-section-title"><?php echo phoenix_text('my_saas.users_title'); ?></div>
                <ul class="saas-channel-list">
                    <li><span class="dot <?php echo $s_manager?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.user_manager'); ?>:</strong>&nbsp;<?php echo $fmt($s_manager, __('account','wbs-saas-plugin')); ?></li>
                    <li><span class="dot <?php echo $s_operator?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.user_operator'); ?>:</strong>&nbsp;<?php echo $fmt($s_operator, __('account','wbs-saas-plugin')); ?></li>
                    <li><span class="dot <?php echo $s_agent?'on':''; ?>"></span><strong><?php echo phoenix_text('my_saas.user_agent'); ?>:</strong>&nbsp;<?php echo $fmt($s_agent, __('account','wbs-saas-plugin')); ?></li>
                </ul>
                <?php if ($status==='active' && $plan_level>=2): ?><a href="<?php echo esc_url($manage_addons_url); ?>" onclick="phoenixOpenInstanceOnLoad('<?php echo esc_js($anchor); ?>')" class="saas-section-cta"><?php echo phoenix_text('my_saas.cta_buy_user'); ?></a><?php endif; ?>
            </div>

            <div class="saas-info-grid" style="margin-bottom:14px;">
                <div class="saas-info-item">
                    <div class="saas-info-label"><?php echo phoenix_text('my_saas.languages_label'); ?></div>
                    <div class="saas-info-value"><?php echo ($s_languages===1) ? phoenix_text('my_saas.languages_one') : sprintf(phoenix_text('my_saas.languages_up_to'), $s_languages); ?>
                        <?php if ($status==='active' && $plan_level>=2): ?><br><a href="<?php echo esc_url($manage_addons_url); ?>" onclick="phoenixOpenInstanceOnLoad('<?php echo esc_js($anchor); ?>')" class="saas-section-cta" style="margin-top:5px;"><?php echo phoenix_text('my_saas.cta_buy_extra'); ?></a><?php endif; ?>
                    </div>
                </div>
                <div class="saas-info-item">
                    <div class="saas-info-label"><?php echo phoenix_text('my_saas.themes_label'); ?></div>
                    <div class="saas-info-value">
                        <?php
                        if (!empty($settings['themes']) && is_array($settings['themes'])) {
                            $names = [];
                            foreach ($settings['themes'] as $sku) {
                                $tid = wc_get_product_id_by_sku($sku); $tp = $tid ? wc_get_product($tid) : null;
                                $names[] = $tp ? esc_html($tp->get_name()) : esc_html($sku);
                            }
                            echo implode(', ', $names);
                        } else { echo phoenix_text('my_saas.themes_default'); }
                        ?>
                        <?php if ($status==='active' && $plan_level>=2): ?><br><a href="<?php echo esc_url($manage_addons_url); ?>" onclick="phoenixOpenInstanceOnLoad('<?php echo esc_js($anchor); ?>')" class="saas-section-cta" style="margin-top:5px;"><?php echo phoenix_text('my_saas.cta_buy_theme'); ?></a><?php endif; ?>
                    </div>
                </div>
            </div>

        </div><!-- .saas-card-body -->
        </div><!-- .saas-card -->

        <?php if (!$is_cancelled && $status === 'active'): ?>
        <div class="saas-upgrade-card">
            <div class="saas-upgrade-card-inner">
                <?php if ($upgrade_gated): ?>
                <a href="#" class="<?php echo esc_attr($upgrade_class); ?>"
                   data-wizard-url="<?php echo esc_attr($wizard_url); ?>"
                   data-uuid="<?php echo esc_attr($wizard_uuid); ?>"
                   data-nonce="<?php echo wp_create_nonce('phoenix_wizard_nonce'); ?>"
                   onclick="return phoenixUpgradeGated(this)" title="<?php echo esc_attr(phoenix_text('my_saas.wizard_gate_title')); ?>">
                    <span class="upg-icon"><?php echo $upgrade_icon; ?></span><?php echo esc_html($upgrade_label); ?>
                </a>
                <?php else: ?>
                <a href="<?php echo esc_url($upgrade_url); ?>" target="<?php echo esc_attr($upgrade_target); ?>" class="<?php echo esc_attr($upgrade_class); ?>">
                    <span class="upg-icon"><?php echo $upgrade_icon; ?></span><?php echo esc_html($upgrade_label); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div><!-- .saas-row -->
    <?php endforeach; ?>

    <div style="background:#f8f9fa;border:2px dashed #dee2e6;border-radius:10px;padding:28px;text-align:center;margin-top:8px;">
        <h3 style="margin:0 0 6px;color:#495057;font-size:15px;"><?php echo phoenix_text('my_saas.new_plan_title'); ?></h3>
        <p style="margin:0 0 14px;color:#6c757d;font-size:13px;text-align:center;"><?php echo phoenix_text('my_saas.new_plan_desc'); ?></p>
        <a href="<?php echo esc_url(phoenix_wpml_url(home_url('/pricing/'))); ?>" style="display:inline-block;padding:10px 22px;background:#3498db;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;font-size:13px;"><?php echo phoenix_text('my_saas.new_plan_cta'); ?></a>
    </div>
    <?php
}

// =============================================================================
// 5. REDIRECT MY ACCOUNT DASHBOARD + /subscribers/ → /workspaces/
// =============================================================================
add_action('template_redirect', 'phoenix_redirect_myaccount_to_workspaces');
function phoenix_redirect_myaccount_to_workspaces() {
    if (!is_user_logged_in()) return;

    $workspaces_url = function_exists('wpml_get_permalink')
        ? apply_filters('wpml_permalink', home_url('/my-account/workspaces/'))
        : home_url('/my-account/workspaces/');
    $path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/') . '/';

    // Redirect /my-account/subscribers/ → /my-account/workspaces/ (any lang prefix)
    if (preg_match('#^(/[a-z]{2}(-[a-z]{2})?)?/my-account/subscribers/$#i', $path)) {
        wp_safe_redirect($workspaces_url, 301);
        exit;
    }

    // Redirect bare /my-account/ dashboard (any lang prefix) → /my-account/workspaces/
    if (preg_match('#^(/[a-z]{2}(-[a-z]{2})?)?/my-account/(account-dashboard/)?$#i', $path)) {
        wp_safe_redirect($workspaces_url, 302);
        exit;
    }
}

// =============================================================================
// 6. SET WC SESSION ON UPGRADE PARAM
// =============================================================================
add_action('template_redirect', 'handle_upgrade_subscription_param');
function handle_upgrade_subscription_param() {
    if (!is_product() || !isset($_GET['upgrade_subscription'])) return;
    $sub_id = absint($_GET['upgrade_subscription']);
    if ($sub_id && WC()->session) WC()->session->set('upgrade_from_subscription', $sub_id);
}