/**
 * SNIPPET: My Billing - v3
 *
 * Design language identik dengan My SaaS Instances.
 * - Collapsible cards per instance (saas-card style)
 * - Hide cancelled/inactive by default (toggle show)
 * - Upcoming billing: main plan + addons consolidated
 * - Billing history: semua orders (main + addon) 1 tabel per instance
 * - Commitment progress bar (monthly non-free only)
 * - Cancel window notice (H-31 sebelum commitment end)
 * - Manage → link saja, NO renew/cancel buttons (handled di view-subscription)
 * - Invoice link: WPO PDF plugin aware, fallback ke view order
 * - Status label informatif: Active / Cancels on [date] / Suspended / etc
 */

// =============================================================================
// 1. ENDPOINT + MENU
// =============================================================================
add_action('init', 'phoenix_add_billing_endpoint');
function phoenix_add_billing_endpoint() {
    add_rewrite_endpoint('billing', EP_ROOT | EP_PAGES);
}

add_filter('woocommerce_account_menu_items', 'phoenix_add_billing_menu_item');
function phoenix_add_billing_menu_item($items) {
    $new = [];
    foreach ($items as $key => $label) {
        $new[$key] = $label;
        if ($key === 'addons') {
            $new['billing'] = function_exists('phoenix_text') ? phoenix_text('billing.menu_label') : 'My Billing';
        }
    }
    if (!isset($new['billing'])) $new['billing'] = 'My Billing';
    return $new;
}

// =============================================================================
// 2. HELPERS
// =============================================================================

// Invoice URL: WPO PDF plugin → fallback view order
function phoenix_billing_invoice_url($order) {
    if (function_exists('wpo_wcpdf_get_document')) {
        $inv = wpo_wcpdf_get_document('invoice', $order);
        if ($inv && $inv->is_allowed()) {
            return wp_nonce_url(add_query_arg([
                'action'        => 'generate_wpo_wcpdf',
                'document_type' => 'invoice',
                'order_ids'     => $order->get_id(),
            ], admin_url('admin-ajax.php')), 'generate_wpo_wcpdf');
        }
    }
    return $order->get_view_order_url();
}

// Rich status label
function phoenix_billing_sub_status($sub) {
    if (!$sub) return ['text' => phoenix_text('common.unknown'), 'class' => 'badge-unknown'];
    switch ($sub->get_status()) {
        case 'active':
            if (function_exists('phoenix_get_cancel_window')) {
                $w = phoenix_get_cancel_window($sub);
                if ($w === 'window') return ['text' => phoenix_text('billing.status_cancel_window'), 'class' => 'badge-on-hold'];
            }
            return ['text' => phoenix_text('billing.status_active'), 'class' => 'badge-active'];
        case 'pending-cancel':
            $d = $sub->get_date('end') ?: $sub->get_date('next_payment');
            $f = $d ? date('d M Y', strtotime($d)) : '';
            return ['text' => sprintf(phoenix_text('billing.status_cancels_on'), $f), 'class' => 'badge-cancelled'];
        case 'on-hold':
            return ['text' => phoenix_text('billing.status_suspended'), 'class' => 'badge-on-hold'];
        case 'cancelled':
            return ['text' => phoenix_text('billing.status_cancelled'), 'class' => 'badge-cancelled'];
        case 'expired':
            return ['text' => phoenix_text('billing.status_expired'), 'class' => 'badge-unknown'];
        default:
            return ['text' => ucfirst($sub->get_status()), 'class' => 'badge-unknown'];
    }
}

// All order IDs from a subscription
function phoenix_billing_sub_order_ids($sub) {
    $ids = [];
    if ($sub->get_parent_id()) $ids[] = $sub->get_parent_id();
    foreach ($sub->get_related_orders('ids', 'renewal') as $rid) $ids[] = $rid;
    return $ids;
}

// =============================================================================
// 3. PAGE CONTENT
// =============================================================================
add_action('woocommerce_account_billing_endpoint', 'phoenix_render_billing_page');
function phoenix_render_billing_page() {


    if (!is_user_logged_in()) { echo '<p>' . phoenix_text('billing.login_required') . '</p>'; return; }

    global $wpdb;
    $user_id       = get_current_user_id();
    $table_tenants = $wpdb->prefix . 'wbssaas_tenants';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_tenants'") !== $table_tenants) {
        echo '<p>' . phoenix_text('billing.unavailable') . '</p>'; return;
    }

    // ── Tenant rows ───────────────────────────────────────────────────────────
    $all_rows = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_tenants WHERE customer_id = %d ORDER BY created ASC", $user_id
    ));

if (empty($all_rows)) {
    echo '<div style="background:#f0f7ff;padding:24px;border-radius:10px;text-align:center;border:1px solid #3498db;">
        <h3 style="margin:0 0 10px;color:#1565c0;">' . phoenix_text('billing.empty_title') . '</h3>
        <a href="/pricing/" style="display:inline-block;padding:10px 22px;background:#3498db;color:#fff;border-radius:6px;font-weight:600;">' . phoenix_text('billing.empty_cta') . '</a>
    </div>';
    return;
}

    // ── Enrich rows ───────────────────────────────────────────────────────────
    foreach ($all_rows as $row) {
        $sid          = (int) $row->subscription_wc_id;
        $sub          = ($sid && function_exists('wcs_get_subscription')) ? wcs_get_subscription($sid) : null;
        $row->_sub    = $sub;
        $row->_status = $sub ? $sub->get_status() : 'unknown';
        $row->_level  = ($sub && function_exists('phoenix_get_subscription_plan_level'))
            ? phoenix_get_subscription_plan_level($sub) : 0;
    }

    // ── Group → 1 instance per tenant_name ───────────────────────────────────
    $grouped = [];
    foreach ($all_rows as $row) {
        $grouped[trim(strtolower($row->tenant_name))][] = $row;
    }

    $active_instances   = [];
    $inactive_instances = [];

    foreach ($grouped as $group) {
        usort($group, fn($a,$b) => strtotime($a->created) - strtotime($b->created));
        $oldest      = $group[0];
        $active_rows = array_values(array_filter($group, fn($r) => in_array($r->_status, ['active', 'pending-cancel', 'on-hold'])));

        // Collect ALL UUIDs from ALL rows in this group — addon bisa dibeli post-upgrade
        // sehingga GF entry punya UUID dari row baru, bukan oldest row
        $all_uuids = array_values(array_unique(array_filter(
            array_map(fn($r) => trim($r->tenant_uuid ?? ''), $group)
        )));

        if (!empty($active_rows)) {
            usort($active_rows, fn($a,$b) => $b->_level - $a->_level);
            $d = $active_rows[0];
            $d->_oldest    = $oldest;
            $d->_all_uuids = $all_uuids;
            $d->_all_rows  = $group;
            $active_instances[] = $d;
        } else {
            $oldest->_oldest    = $oldest;
            $oldest->_all_uuids = $all_uuids;
            $oldest->_all_rows  = $group;
            $inactive_instances[] = $oldest;
        }
    }

    //$all_instances = array_merge($active_instances, $inactive_instances);
    $all_instances = array_merge($active_instances, $inactive_instances);

	// 🔽 TAMBAHKAN DI SINI
	usort($all_instances, function($a, $b) {

		$status_priority = function($status) {
			return in_array($status, ['active','pending-cancel','on-hold']) ? 1 : 0;
		};

		$a_active = $status_priority($a->_status);
		$b_active = $status_priority($b->_status);

		// Active selalu di atas
		if ($a_active !== $b_active) {
			return $b_active - $a_active;
		}

		// Kalau sama-sama active / inactive → sort by latest
		$a_time = $a->_sub && $a->_sub->get_date('start')
			? strtotime($a->_sub->get_date('start'))
			: strtotime($a->created);

		$b_time = $b->_sub && $b->_sub->get_date('start')
			? strtotime($b->_sub->get_date('start'))
			: strtotime($b->created);

		return $b_time - $a_time;
	});

    // ── All user subscriptions ────────────────────────────────────────────────
    $all_user_subs = function_exists('wcs_get_users_subscriptions')
        ? wcs_get_users_subscriptions($user_id) : [];

    // ── Addon subs pre-grouped: ambil semua addon subs dulu ─────────────────
    // Matching per instance dilakukan saat render (exact UUID compare, same as menu-addon)
    $all_addon_subs = [];
    foreach ($all_user_subs as $_sub) {
        foreach ($_sub->get_items() as $_item) {
            if (has_term('add-on', 'product_cat', $_item->get_product_id())) {
                $all_addon_subs[] = $_sub; break;
            }
        }
    }

    // ── Render ────────────────────────────────────────────────────────────────
    $active_count   = count($active_instances);
    $inactive_count = count($inactive_instances);
    ?>

    <h2 style="margin-bottom:5px;"><?php echo phoenix_text('billing.page_title'); ?></h2>

    <p style="color:#666;margin-bottom:16px;font-size:14px;">
    <?php echo phoenix_text('billing.page_subtitle'); ?>
	</p>

    <style>
    /* Matches .saas-card design language exactly */
    .billing-card{background:#fff;border:1px solid #e0e0e0;border-radius:10px;margin-bottom:16px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,.05);transition:box-shadow .2s}
    .billing-card:hover{box-shadow:0 4px 14px rgba(0,0,0,.09)}
    .billing-card.cancelled-card{opacity:.7;border-style:dashed;display:none}
    .billing-card-header{padding:14px 18px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:12px;border-bottom:1px solid transparent;transition:background .15s}
    .billing-card-header:hover{background:#fafafa}
    .billing-card-header.open{border-bottom-color:#f0f0f0}
    .billing-card-left{display:flex;align-items:center;gap:12px;flex:1;flex-wrap:wrap;min-width:0}
    .billing-card-icon{width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
    .billing-card-icon.free{background:#f5f5f5}
    .billing-card-icon.basic{background:#e3f2fd}
    .billing-card-icon.premium{background:#fff8e1}
    .billing-card-meta{min-width:0;flex:1}
    .billing-card-name{font-size:14px;font-weight:700;color:#2c3e50;margin:0 0 3px}
    .billing-card-url{font-size:12px;color:#999}
    .billing-card-badges{display:flex;gap:6px;flex-shrink:0;flex-wrap:wrap;align-items:center}
    .billing-chevron{color:#ccc;font-size:12px;transition:transform .2s;flex-shrink:0}
    .billing-chevron.open{transform:rotate(180deg);color:#3498db}
    .billing-card-body{display:none;padding:0}
    .billing-card-body.open{display:block}

    /* Reuse badge classes from my-saas */
    .badge{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700}
    .badge-free{background:#f5f5f5;color:#666}
    .badge-basic{background:#e3f2fd;color:#1565c0}
    .badge-premium{background:#fff8e1;color:#f57c00}
    .badge-active{background:#e8f5e9;color:#2e7d32}
    .badge-cancelled{background:#ffebee;color:#c62828}
    .badge-on-hold{background:#fff3e0;color:#ef6c00}
    .badge-unknown{background:#f5f5f5;color:#999}
    .badge-monthly{background:#f3e5f5;color:#7b1fa2}
    .badge-yearly{background:#e8f5e9;color:#2e7d32}
    .badge-pending-cancel{background:#ffebee;color:#c62828}

    /* Commitment bar — same as my-saas */
    .commitment-wrap{background:#fffbf0;border-top:1px solid #ffe082;padding:12px 18px}
    .commitment-header{display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px}
    .commitment-label{font-weight:700;color:#f57c00}
    .commitment-bar-bg{background:#ffe082;border-radius:20px;height:7px;overflow:hidden}
    .commitment-bar-fill{height:7px;border-radius:20px;background:linear-gradient(90deg,#f39c12,#27ae60)}
    .commitment-footer{display:flex;justify-content:space-between;font-size:11px;color:#aaa;margin-top:4px}

    /* Cancel window notice */
    .billing-cancel-notice{background:#fff8e1;border-top:1px solid #ffe082;padding:10px 18px;font-size:12px;color:#b45309;display:flex;gap:8px;align-items:flex-start}

    /* Upcoming billing */
    .billing-upcoming{padding:14px 18px;background:#f8f9fa;border-top:1px solid #f0f0f0}
    .billing-section-title{font-size:11px;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px}
    .billing-line{display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:4px 0}
    .billing-line-label{color:#555}
    .billing-line-amount{font-weight:700;color:#2c3e50}
    .billing-section-sep{border:none;border-top:1px dashed #e0e0e0;margin:8px 0}
    .billing-addon-subtotal{display:flex;justify-content:space-between;font-size:12px;color:#999;padding:2px 0 4px}
    .billing-total-line{display:flex;justify-content:space-between;align-items:center;font-size:14px;font-weight:700;border-top:2px solid #e0e0e0;padding-top:8px;margin-top:4px}
    .billing-date-note{font-size:12px;color:#aaa;margin-top:8px}


    /* History */
    .billing-history{padding:14px 18px}
    .billing-show-all-btn{background:none;border:1px solid #e0e0e0;border-radius:6px;padding:6px 16px;font-size:12px;color:#888;cursor:pointer;transition:all .2s}
    .billing-show-all-btn:hover{border-color:#3498db;color:#3498db;background:#f0f7ff}
    .billing-table{width:100%;border-collapse:collapse;font-size:13px}
    .billing-table thead tr{background:#f8f9fa}
    .billing-table th{padding:7px 10px;text-align:left;font-size:10px;font-weight:700;color:#aaa;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e0e0e0}
    .billing-table td{padding:9px 10px;border-bottom:1px solid #f5f5f5;vertical-align:middle}
    .billing-table tbody tr:last-child td{border-bottom:none}
    .billing-table tbody tr:hover{background:#fafafa}
    .billing-tag{display:inline-block;padding:2px 7px;border-radius:4px;font-size:10px;font-weight:400;margin-left:4px}
    .tag-prorate{background:#e3f2fd;color:#1565c0}
    .tag-renewal{background:#f5f5f5;color:#888}
    .tag-addon{background: #f8f9fa;color: #000000;}
    .tag-plan{background: #f8f9fa;color: #000000;}
    .os-completed{color:#27ae60;font-weight:700}
    .os-processing{color:#f57c00;font-weight:700}
    .os-pending{color:#999}
    .os-failed{color:#c62828;font-weight:700}
    .os-refunded{color:#7b1fa2;font-weight:700}
    .billing-invoice-btn{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;background:#f8f9fa;border:1px solid #e0e0e0;border-radius:5px;font-size:11px;font-weight:600;color:#555;text-decoration:none;transition:all .15s}
    .billing-invoice-btn:hover{background:#3498db;color:#fff;border-color:#3498db}
    .billing-manage-link{display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border:1px solid #ddd;border-radius:6px;font-size:12px;font-weight:600;color:#555;text-decoration:none;transition:all .2s;background:#fff}
    .billing-manage-link:hover{border-color:#3498db;color:#3498db;background:#f0f7ff}
    .billing-cta-btn{display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;transition:all .2s;text-decoration:none;border:1px solid}
    .billing-btn-cancel{background:#fff;color:#c62828;border-color:#ffcdd2}.billing-btn-cancel:hover{background:#ffebee;border-color:#c62828}
    .billing-btn-renew{background:#1E4A7A;color:#fff;border-color:#1E4A7A}.billing-btn-renew:hover{background:#163d6a;border-color:#163d6a}
    .billing-btn-reactivate{background:#e8f5e9;color:#2e7d32;border-color:#a5d6a7}.billing-btn-reactivate:hover{background:#c8e6c9;border-color:#2e7d32}
    /* Cancel confirm modal */
    #phoenix-cancel-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:99999;align-items:center;justify-content:center}
    #phoenix-cancel-modal.open{display:flex}
    .phoenix-cancel-box{background:#fff;border-radius:10px;width:100%;max-width:420px;padding:32px;position:relative;box-shadow:0 8px 32px rgba(0,0,0,0.18)}
    @media(max-width:600px){
        .billing-table th:nth-child(3),.billing-table td:nth-child(3){display:none}
        .billing-card-badges{display:none}
    }

    /* ── Theme Section ── */
    .billing-themes{padding:14px 18px;border-top:1px solid #f0f0f0}
    .billing-themes-summary{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
    .billing-themes-left{display:flex;align-items:center;gap:10px}
    .billing-themes-icon{width:32px;height:32px;background:#f0fff4;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
    .billing-themes-info{font-size:13px}
    .billing-themes-title{font-weight:700;color:#2c3e50}
    .billing-themes-meta{color:#888;font-size:12px;margin-top:2px}
    .billing-themes-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border:1px solid #ddd;border-radius:6px;font-size:12px;font-weight:600;color:#555;background:#fff;cursor:pointer;text-decoration:none;transition:all .2s}
    .billing-themes-btn:hover{border-color:#27ae60;color:#27ae60;background:#f0fff4}

    /* ── Theme Modal ── */
    .theme-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;padding:20px}
    .theme-modal-overlay.open{display:flex}
    .theme-modal-box{background:#fff;border-radius:12px;width:100%;max-width:520px;max-height:80vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 8px 32px rgba(0,0,0,.18)}
    .theme-modal-header{padding:18px 20px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between}
    .theme-modal-title{font-size:15px;font-weight:700;color:#2c3e50}
    .theme-modal-close{background:none;border:none;font-size:20px;color:#aaa;cursor:pointer;padding:0;line-height:1}
    .theme-modal-close:hover{color:#333}
    .theme-modal-body{overflow-y:auto;padding:0}
    .theme-modal-table{width:100%;border-collapse:collapse;font-size:13px}
    .theme-modal-table th{padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:#aaa;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e0e0e0;background:#fafafa;position:sticky;top:0}
    .theme-modal-table td{padding:11px 16px;border-bottom:1px solid #f5f5f5;vertical-align:middle}
    .theme-modal-table tbody tr:last-child td{border-bottom:none}
    .theme-modal-table tbody tr:hover{background:#fafffe}
    .theme-modal-footer{padding:14px 20px;border-top:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;background:#fafafa}
    .theme-modal-total{font-size:13px;color:#666}
    .theme-modal-total strong{color:#2c3e50}
    .theme-invoice-btn{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border:1px solid #ddd;border-radius:5px;font-size:11px;color:#555;text-decoration:none;transition:all .2s}
    .theme-invoice-btn:hover{border-color:#3498db;color:#3498db}
    </style>

    <?php foreach ($all_instances as $idx => $tenant):
        $sub          = $tenant->_sub;
        $status       = $tenant->_status;
        $plan_level   = $tenant->_level;
        // tenant_uuid: canonical (oldest) UUID — used for display
        $tenant_uuid  = trim($tenant->_oldest->tenant_uuid ?? ($tenant->tenant_uuid ?? ''));
        // all_uuids: ALL UUIDs from all rows of this instance group
        // Addon bisa dibeli setelah upgrade → UUID di GF entry bisa dari row post-upgrade
        $all_uuids    = $tenant->_all_uuids ?? [$tenant_uuid];
        $tenant_url   = preg_replace('/\.stg\./i', '.', $tenant->_oldest->tenant_url ?? $tenant->tenant_url ?? '');
        $tenant_name  = $tenant->tenant_name;
        $is_inactive  = !in_array($status, ['active', 'pending-cancel', 'on-hold']);

        $plan_slug = 'free';
        if ($plan_level >= 3)     $plan_slug = 'premium';
        elseif ($plan_level >= 2) $plan_slug = 'basic';

        $icon_map = ['free' => '🔒', 'basic' => '📦', 'premium' => '⭐'];
        $icon     = $icon_map[$plan_slug] ?? '📦';

        $is_free    = ($plan_level <= 1);
        $is_yearly  = $sub && function_exists('phoenix_is_yearly_subscription')
            ? phoenix_is_yearly_subscription($sub) : false;
        $period_label = $is_yearly ? phoenix_text('billing.period_yearly') : phoenix_text('billing.period_monthly');

        $status_badge = phoenix_billing_sub_status($sub);
        $manage_url   = $sub
            ? wc_get_endpoint_url('view-subscription', $sub->get_id(), wc_get_page_permalink('myaccount'))
            : '';



        // Addon subs for this instance
        // ── Match addon subs ke instance ini — exact UUID compare (same as menu-addon) ──
        // Pre-built UUID map tidak reliable karena regex bisa ambil UUID salah instance.
        // Solusi: per-instance matching dengan compare langsung ke $tenant_uuid.
        $instance_addon_subs = [];
        if (!empty($all_uuids)) {
            foreach ($all_addon_subs as $_asub) {
                $matched  = false;
                $_oid     = $_asub->get_parent_id();
                if (!$_oid) continue;

                // Method 1: GFAPI — ambil UUID dari GF entry, cek apakah ada di $all_uuids
                if (!$matched && function_exists('GFAPI')) {
                    $_entries = GFAPI::get_entries(64, ['field_filters' => [
                        ['key' => 'woocommerce_order_number', 'value' => $_oid]
                    ]]);
                    $_gf_uuid = $_entries[0]['1'] ?? '';
                    if ($_gf_uuid && in_array($_gf_uuid, $all_uuids, true)) {
                        $matched = true;
                    }
                }

                // Method 2: WC order item meta — cek semua meta, match ke any of $all_uuids
                if (!$matched) {
                    $_order = wc_get_order($_oid);
                    if ($_order) {
                        foreach ($_order->get_items() as $_oitem) {
                            foreach ($_oitem->get_meta_data() as $_ometa) {
                                if (in_array((string) $_ometa->value, $all_uuids, true)) {
                                    $matched = true; break 2;
                                }
                            }
                        }
                    }
                }

                // Method 3: wpdb GF entry meta — ambil UUID, cek ke $all_uuids
                if (!$matched) {
                    $_gf_val = $wpdb->get_var($wpdb->prepare(
                        "SELECT em.meta_value
                         FROM {$wpdb->prefix}gf_entry_meta em
                         INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
                         WHERE e.id IN (
                             SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                             WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
                         )
                         AND em.meta_key = '1' AND e.form_id = 64 LIMIT 1",
                        (string) $_oid
                    ));
                    if ($_gf_val && in_array((string) $_gf_val, $all_uuids, true)) $matched = true;
                }

                if ($matched) $instance_addon_subs[] = $_asub;
            }
        }

        // Main plan billing info
        $main_np    = $sub ? $sub->get_date('next_payment') : null;
        // FIX: get_subtotal() returns recurring amount, not get_total() which includes one-time prorate fees
        $main_total = $sub ? (float) $sub->get_subtotal() : 0;
        $main_name  = '';
        if ($sub) {
            foreach ($sub->get_items() as $item) {
                if (!has_term('add-on', 'product_cat', $item->get_product_id())) {
                    $main_name = $item->get_name(); break;
                }
            }
        }

        // Addon totals
        $addon_total = 0;
        foreach ($instance_addon_subs as $asub) $addon_total += (float) $asub->get_subtotal();
        $grand_total = $main_total + $addon_total;

        // Alignment check
        $all_aligned = true;
        foreach ($instance_addon_subs as $asub) {
            $anp = $asub->get_date('next_payment');
            if ($anp && $main_np && date('Y-m-d', strtotime($anp)) !== date('Y-m-d', strtotime($main_np))) {
                $all_aligned = false; break;
            }
        }

        // Commitment (monthly non-free only) — use same helper as My SaaS for consistency
        $show_commitment    = false;
        $cancel_window_open = false;
        $commitment_pct     = 0;
        $months_passed      = 0;
        $commitment_end_fmt = '';
        $next_payment_fmt   = '';

        if ($sub && !$is_free && !$is_yearly && $sub->has_status('active')) {
            // Use phoenix_get_commitment_progress (same as My SaaS) — consistent display
            $commitment_data = function_exists('phoenix_get_commitment_progress')
                ? phoenix_get_commitment_progress($sub)
                : ['months' => 0, 'total' => 12, 'percentage' => 0, 'complete' => false];

            $months_passed   = (int) $commitment_data['months'];
            $commitment_pct  = min(100, (int) round($commitment_data['percentage']));
            $show_commitment = !$commitment_data['complete'];

            // Cancel window open = bulan 11 (months >= 11) belum selesai komitmen
            // Consistent with phoenix_get_cancel_window() logic in upgrade-subscriptions.php
            $cancel_window_open = ($months_passed === 11);

            // Commitment end date dari start_ts helper
            if (!empty($commitment_data['start_ts'])) {
                $commitment_end     = strtotime('+12 months', (int) $commitment_data['start_ts']);
                $commitment_end_fmt = date('d M Y', $commitment_end);
            }

            $np = $sub->get_date('next_payment');
            $next_payment_fmt = $np ? date('d M Y', strtotime($np)) : $commitment_end_fmt;
        }

        // Build order history (main + addons in 1 list, including all historical plan subs)
        $all_order_ids = [];

        // Active/current plan subscription
        if ($sub) {
            foreach (phoenix_billing_sub_order_ids($sub) as $oid) $all_order_ids[$oid] = 'plan';
        }

        // Historical plan subscriptions — scan ALL user plan subs (incl. cancelled/expired),
        // match to this instance via parent order meta containing any of $all_uuids
        foreach ($all_user_subs as $_hsub) {
            if ($sub && $_hsub->get_id() === $sub->get_id()) continue; // skip current
            // Skip addon subs
            $_is_addon = false;
            foreach ($_hsub->get_items() as $_hitem) {
                if (has_term('add-on', 'product_cat', $_hitem->get_product_id())) { $_is_addon = true; break; }
            }
            if ($_is_addon) continue;

            // Match: check parent order item meta for any UUID of this instance
            $_hoid = $_hsub->get_parent_id();
            if (!$_hoid) continue;
            $_matched = false;

            // Method 1: WC order item meta
            $_horder = wc_get_order($_hoid);
            if ($_horder) {
                foreach ($_horder->get_items() as $_hoitem) {
                    foreach ($_hoitem->get_meta_data() as $_hometa) {
                        if (in_array((string) $_hometa->value, $all_uuids, true)) { $_matched = true; break 2; }
                    }
                }
            }

            // Method 2: GF entry meta (form 58/61/70 — main plan forms)
            if (!$_matched) {
                $_hgf_val = $wpdb->get_var($wpdb->prepare(
                    "SELECT em.meta_value
                     FROM {$wpdb->prefix}gf_entry_meta em
                     INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
                     WHERE e.id IN (
                         SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                         WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
                     )
                     AND em.meta_key IN ('4','5') LIMIT 1",
                    (string) $_hoid
                ));
                // meta_key 4/5 = subdomain field — match against tenant subdomain
                if ($_hgf_val) {
                    $tenant_subdomain = strtolower(explode('.', parse_url($tenant_url, PHP_URL_HOST) ?: $tenant_url)[0] ?? '');
                    if ($tenant_subdomain && strtolower(trim($_hgf_val)) === $tenant_subdomain) $_matched = true;
                }
            }

            if (!$_matched) continue;

            foreach (phoenix_billing_sub_order_ids($_hsub) as $oid) {
                if (!isset($all_order_ids[$oid])) $all_order_ids[$oid] = 'plan';
            }
        }

        // Addon subscriptions
        foreach ($instance_addon_subs as $asub) {
            foreach (phoenix_billing_sub_order_ids($asub) as $oid) {
                if (!isset($all_order_ids[$oid])) $all_order_ids[$oid] = 'addon';
            }
        }

        $orders = [];
        foreach ($all_order_ids as $oid => $type) {
            $o = wc_get_order($oid);
            if ($o) $orders[] = ['order' => $o, 'type' => $type];
        }
        usort($orders, fn($a,$b) =>
            $b['order']->get_date_created()->getTimestamp() - $a['order']->get_date_created()->getTimestamp()
        );

        // Detect prorated orders
        $prorated_ids = [];
        foreach ($orders as $entry) {
            foreach ($entry['order']->get_fees() as $fee) {
                if (stripos($fee->get_name(), 'prorate') !== false) {
                    $prorated_ids[$entry['order']->get_id()] = true; break;
                }
            }
        }

        // ── Theme purchases for this instance ──
        $theme_orders  = [];
        $theme_total   = 0.0;

        $all_user_orders = wc_get_orders([
            'customer_id' => $user_id,
            'limit'       => 100,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'status'      => ['completed', 'processing'],
        ]);

        foreach ($all_user_orders as $_torder) {
            $_has_theme  = false;
            $_uuid_match = false;

            foreach ($_torder->get_items() as $_titem) {
                if (has_term('theme', 'product_cat', $_titem->get_product_id())) {
                    $_has_theme = true;
                }
                foreach ($_titem->get_meta_data() as $_tmeta) {
                    $mv = (string) $_tmeta->value;
                    // Match by UUID
                    if (in_array($mv, $all_uuids, true)) {
                        $_uuid_match = true; break;
                    }
                    // Match by tenant_name (plugin simpan nama bukan UUID di order meta)
                    if (strtolower(trim($mv)) === strtolower(trim($tenant_name))) {
                        $_uuid_match = true; break;
                    }
                }
            }

            // Method 2: GF entry field 1 = tenant_uuid
            if ($_has_theme && !$_uuid_match && function_exists('GFAPI')) {
                $_tentries = GFAPI::get_entries(64, ['field_filters' => [
                    ['key' => 'woocommerce_order_number', 'value' => $_torder->get_id()]
                ]]);
                $_tgf_uuid = $_tentries[0]['1'] ?? '';
                if ($_tgf_uuid && in_array($_tgf_uuid, $all_uuids, true)) {
                    $_uuid_match = true;
                }
            }

            // Method 3: wpdb GF entry meta langsung
            if ($_has_theme && !$_uuid_match) {
                global $wpdb;
                $_gf_val = $wpdb->get_var($wpdb->prepare(
                    "SELECT em.meta_value
                     FROM {$wpdb->prefix}gf_entry_meta em
                     INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
                     WHERE e.id IN (
                         SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                         WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
                     )
                     AND em.meta_key = '1' AND e.form_id = 64 LIMIT 1",
                    (string) $_torder->get_id()
                ));
                if ($_gf_val && in_array((string) $_gf_val, $all_uuids, true)) {
                    $_uuid_match = true;
                }
            }

            if ($_has_theme && $_uuid_match) {
                $theme_orders[] = $_torder;
                $theme_total   += (float) $_torder->get_total();
            }
        }

        // ── CTA Button Logic (must be after $main_np, $is_yearly, $cancel_window_open, $show_commitment) ──
        $show_cancel     = false;
        $show_renew      = false;
        $show_reactivate = false;
        $renew_url       = $manage_url;

        if ($sub) {
            $sub_status      = $sub->get_status();
            $now_ts          = current_time('timestamp');
            $np_ts           = $main_np ? strtotime($main_np) : 0;
            $days_to_renewal = $np_ts ? (int) ceil(($np_ts - $now_ts) / DAY_IN_SECONDS) : 999;

            // Reactivate — pending-cancel saja
            if ($sub_status === 'pending-cancel') {
                $show_reactivate = true;
            }

			if ($sub_status === 'active' && !$is_free) {
            // Cancel & Renew — hanya active, skip Free plan
				if ($is_yearly) {
					// Yearly: cancel & renew muncul H-30
					// Tapi skip kalau sub baru dibuat < 7 hari (baru upgrade)
					$sub_age_days = (int) floor(
						(current_time('timestamp') - $sub->get_time('start')) / DAY_IN_SECONDS
					);
					if ($days_to_renewal <= 30 && $sub_age_days >= 7) {
						$show_cancel = true;
					}
				} else {
					// Monthly: cancel HANYA saat cancel window terbuka (bulan ke-11 atau post-commitment)
					// Pastikan show_commitment benar — kalau months < 11 dan belum complete → masih locked
					$is_truly_locked = $show_commitment && !$cancel_window_open;
					if ($cancel_window_open) {
						$show_cancel = true;
					}
					// Post-commitment: cancel muncul H-30, tapi hanya kalau sudah benar-benar bebas commitment
					if (!$show_commitment && !$is_truly_locked && $days_to_renewal <= 30) {
						$show_cancel = true;
					}
					// Monthly tidak punya tombol renew
				}
			}
		}
        

        $card_id = 'billing-card-' . $idx;
    ?>

    <div class="billing-card<?php echo $is_inactive ? ' cancelled-card' : ''; ?>">

        <!-- ── Header (collapsible) ───────────────────────────────────────── -->
        <div class="billing-card-header" onclick="phoenixBillingToggle('<?php echo $card_id; ?>')">
            <div class="billing-card-left">
                <div class="billing-card-icon <?php echo $plan_slug; ?>"><?php echo $icon; ?></div>
                <div class="billing-card-meta">
                    <div class="billing-card-name"><?php echo esc_html($tenant_name); ?></div>
                    <?php if ($tenant_url): ?>
                    <div class="billing-card-url"><?php echo esc_html($tenant_url); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="billing-card-badges">
                <span class="badge badge-<?php echo $plan_slug; ?>"><?php echo phoenix_text('billing.plan_' . $plan_slug); ?></span>
                <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $period_label)); ?>"><?php echo $period_label; ?></span>
                <span class="badge <?php echo $status_badge['class']; ?>"><?php echo esc_html($status_badge['text']); ?></span>
                <?php if ($show_reactivate): ?>
                <button class="billing-cta-btn billing-btn-reactivate"
                    onclick="event.stopPropagation();phoenixReactivateSub(<?php echo $sub->get_id(); ?>, this)"
                    title="<?php echo esc_attr(phoenix_text('billing.title_reactivate')); ?>"><?php echo phoenix_text('billing.btn_reactivate'); ?></button>
                <?php endif; ?>

                <?php if ($show_cancel): ?>
                <button class="billing-cta-btn billing-btn-cancel"
                    onclick="event.stopPropagation();phoenixOpenCancelModal(<?php echo $sub->get_id(); ?>, '<?php echo esc_js($tenant_name); ?>', '<?php echo esc_js($commitment_end_fmt ?: ($main_np ? date('d M Y', strtotime($main_np)) : '')); ?>')"
                    title="<?php echo esc_attr(phoenix_text('billing.title_cancel')); ?>"><?php echo phoenix_text('billing.btn_cancel'); ?></button>
                <?php endif; ?>

                <?php if ($show_renew): ?>
                <a href="<?php echo esc_url($renew_url); ?>" class="billing-cta-btn billing-btn-renew"
                    onclick="event.stopPropagation()" title="<?php echo esc_attr(phoenix_text('billing.title_renew')); ?>"><?php echo phoenix_text('billing.btn_renew'); ?></a>
                <?php endif; ?>
            </div>
            <span class="billing-chevron" id="<?php echo $card_id; ?>-chevron">▼</span>
        </div>

        <!-- ── Collapsible body ───────────────────────────────────────────── -->
        <div class="billing-card-body" id="<?php echo $card_id; ?>-body">


            <?php if ($show_commitment): ?>
            <!-- Commitment progress (monthly non-free only) -->
            <div class="commitment-wrap">
                <div class="commitment-header">
                    <span class="commitment-label"><?php echo phoenix_text('billing.commitment_title'); ?></span>
                    <span style="color:#888;font-weight:600;"><?php echo sprintf(phoenix_text('billing.commitment_progress'), $months_passed); ?></span>
                </div>
                <div class="commitment-bar-bg">
                    <div class="commitment-bar-fill" style="width:<?php echo $commitment_pct; ?>%"></div>
                </div>
                <div class="commitment-footer">
                    <span><!-- % complete--></span>
                    <span><?php echo sprintf(phoenix_text('billing.commitment_renewal'), esc_html($commitment_end_fmt)); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($is_free && $sub && $sub->has_status('active')): ?>
            <!-- Free plan trial ends notice -->
            <div class="billing-upcoming">
                <div class="billing-section-title"><?php echo phoenix_text('billing.trial_section_title'); ?></div>
                <?php
                    $_free_end = $sub->get_date('end') ?: $sub->get_date('next_payment');
                    $_free_end_fmt = $_free_end ? date('d M Y', strtotime($_free_end)) : '';
                ?>
                <div class="billing-date-note" style="margin-top:8px;">
                    <?php echo phoenix_text('billing.trial_ends_label'); ?> <strong style="color:#e67e22;"><?php echo esc_html($_free_end_fmt); ?></strong>
                </div>
                <div style="margin-top:8px;font-size:12px;color:#888;">
                    <?php echo phoenix_text('billing.trial_upgrade_note'); ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($status === 'active' && $main_np && !$is_free): ?>
            <!-- Upcoming billing -->
            <div class="billing-upcoming">
                <div class="billing-section-title"><?php echo phoenix_text('billing.upcoming_title'); ?></div>

                <?php if ($main_name): ?>
                <div class="billing-line">
                    <span class="billing-line-label">📦 <?php echo esc_html($main_name); ?></span>
                    <span class="billing-line-amount"><?php echo phoenix_format_price($main_total); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($instance_addon_subs)): ?>
                <hr class="billing-section-sep">
                <?php endif; ?>

                <?php
                // Group addon baris by name — tampilkan qty kalau > 1
                $addon_grouped = [];
                foreach ($instance_addon_subs as $asub) {
                    if (!$asub->has_status(['active','pending-cancel'])) continue;
                    foreach ($asub->get_items() as $aitem) {
                        $key = $aitem->get_name();
                        if (!isset($addon_grouped[$key])) {
                            $addon_grouped[$key] = ['qty' => 0, 'subtotal' => 0];
                        }
                        $addon_grouped[$key]['qty']++;
                        $addon_grouped[$key]['subtotal'] += (float) $asub->get_subtotal();
                    }
                }
                foreach ($addon_grouped as $aname => $adat):
                    $qty_label = $adat['qty'] > 1 ? ' <span style="color:#999;font-size:11px;">×' . $adat['qty'] . '</span>' : '';
                ?>
                <div class="billing-line">
                    <span class="billing-line-label">🔌 <?php echo esc_html($aname) . $qty_label; ?></span>
                    <span class="billing-line-amount"><?php echo phoenix_format_price($adat['subtotal']); ?></span>
                </div>
                <?php endforeach; ?>

                <?php if ($addon_total > 0): ?>
                <div class="billing-addon-subtotal">
                    <span><?php echo phoenix_text('billing.upcoming_addon_subtotal'); ?></span>
                    <span><?php echo phoenix_format_price($addon_total); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($addon_total > 0 || $main_total > 0): ?>
                <div class="billing-total-line">
                    <span><?php echo phoenix_text('billing.upcoming_total'); ?></span>
                    <span><?php echo phoenix_format_price($grand_total); ?></span>
                </div>
                <?php endif; ?>

                <div class="billing-date-note">
                    <?php echo phoenix_text('billing.upcoming_next_billing'); ?> <strong style="color:#555;"><?php echo date('d M Y', strtotime($main_np)); ?>.</strong>
                    <?php if (!empty($instance_addon_subs)): ?>
                        &nbsp;
                        <?php if ($all_aligned): ?>
                        <span class="billing-aligned"><?php echo phoenix_text('billing.upcoming_aligned'); ?></span>
                        <?php else: ?>
                        <span class="billing-misaligned"><?php echo phoenix_text('billing.upcoming_misaligned'); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Purchased Themes -->
            <?php if (!empty($theme_orders)): ?>
            <div class="billing-themes">
                <div class="billing-section-title"><?php echo phoenix_text('billing.themes_title'); ?></div>
                <div class="billing-themes-summary">
                    <div class="billing-themes-left">
                        <div class="billing-themes-icon">🎨</div>
                        <div class="billing-themes-info">
                            <div class="billing-themes-title"><?php echo phoenix_text_plural('billing.themes_purchased', count($theme_orders)); ?></div>
                            <div class="billing-themes-meta"><?php echo phoenix_text('billing.themes_total_spent'); ?> <?php echo phoenix_format_price($theme_total); ?></div>
                        </div>
                    </div>
                    <button class="billing-themes-btn" onclick="phoenixOpenThemeModal('theme-modal-<?php echo $card_id; ?>')">
                        <?php echo phoenix_text('billing.themes_view_invoices'); ?>
                    </button>
                </div>
            </div>

            <!-- Theme Modal -->
            <div class="theme-modal-overlay" id="theme-modal-<?php echo $card_id; ?>" onclick="if(event.target===this)phoenixCloseThemeModal('theme-modal-<?php echo $card_id; ?>')">
                <div class="theme-modal-box">
                    <div class="theme-modal-header">
                        <div class="theme-modal-title"><?php echo sprintf(phoenix_text('billing.themes_modal_title'), esc_html($tenant_name)); ?></div>
                        <button class="theme-modal-close" onclick="phoenixCloseThemeModal('theme-modal-<?php echo $card_id; ?>')">✕</button>
                    </div>
                    <div class="theme-modal-body">
                        <table class="theme-modal-table">
                            <thead>
                                <tr>
                                    <th><?php echo phoenix_text('billing.themes_col_date'); ?></th>
                                    <th><?php echo phoenix_text('billing.themes_col_theme'); ?></th>
                                    <th><?php echo phoenix_text('billing.themes_col_amount'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($theme_orders as $_tord):
                                $_tdate    = $_tord->get_date_created() ? date('d M Y', $_tord->get_date_created()->getTimestamp()) : '—';
                                $_tamount  = phoenix_format_price((float) $_tord->get_total());
                                $_tinv_url = function_exists('wpo_wcpdf_get_document')
                                    ? esc_url(add_query_arg(['action' => 'generate_wpo_wcpdf', 'document_type' => 'invoice', 'order_ids' => $_tord->get_id()], admin_url('admin-ajax.php')))
                                    : esc_url($_tord->get_view_order_url());
                                // Theme name(s) from order items
                                $_tnames = [];
                                foreach ($_tord->get_items() as $_ti) {
                                    if (has_term('theme', 'product_cat', $_ti->get_product_id())) {
                                        $_tnames[] = $_ti->get_name();
                                    }
                                }
                                $_tname = implode(', ', $_tnames) ?: '—';
                            ?>
                                <tr>
                                    <td style="color:#888;white-space:nowrap;"><?php echo $_tdate; ?></td>
                                    <td style="font-weight:600;color:#2c3e50;">🎨 <?php echo esc_html($_tname); ?></td>
                                    <td style="font-weight:700;color:#27ae60;white-space:nowrap;"><?php echo $_tamount; ?></td>
                                    <td>
                                        <a href="<?php echo $_tinv_url; ?>" target="_blank" class="theme-invoice-btn"><?php echo phoenix_text('billing.invoice_btn'); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="theme-modal-footer">
                        <div class="theme-modal-total"><?php echo phoenix_text('billing.themes_total_spent'); ?> <strong><?php echo phoenix_format_price($theme_total); ?></strong></div>
                        <a href="<?php echo esc_url(add_query_arg(['tenant_uuid' => $tenant_uuid], home_url('/product-category/theme/'))); ?>" class="billing-themes-btn"><?php echo phoenix_text('billing.themes_browse_btn'); ?></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Billing history -->
            <div class="billing-history">
                <div class="billing-section-title"><?php echo phoenix_text('billing.history_title'); ?></div>
                <?php if (empty($orders)): ?>
                    <p style="color:#aaa;font-size:13px;font-style:italic;"><?php echo phoenix_text('billing.history_empty'); ?></p>
                <?php else:
                    $history_limit   = 6;
                    $history_total   = count($orders);
                    $history_show_all = ($history_total <= $history_limit);
                ?>
                <table class="billing-table" id="<?php echo $card_id; ?>-history">
                    <thead>
                        <tr>
                            <th><?php echo phoenix_text('billing.history_col_date'); ?></th>
                            <th><?php echo phoenix_text('billing.history_col_desc'); ?></th>
                            <th><?php echo phoenix_text('billing.history_col_type'); ?></th>
                            <th><?php echo phoenix_text('billing.history_col_amount'); ?></th>
                            <th><?php echo phoenix_text('billing.history_col_status'); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $i => $entry):
                        $o          = $entry['order'];
                        $oid        = $o->get_id();
                        $o_date     = $o->get_date_created() ? date('d M Y', $o->get_date_created()->getTimestamp()) : '—';
                        $o_total    = phoenix_format_price((float) $o->get_total());
                        $o_status   = $o->get_status();
                        $is_prorate = isset($prorated_ids[$oid]);
                        $is_renewal = (bool) $o->get_meta('_subscription_renewal');
                        $type       = $entry['type'];

                        $desc_parts = [];
                        foreach ($o->get_items() as $oitem) $desc_parts[] = $oitem->get_name();
                        $description = implode(', ', $desc_parts);
                        $inv_url = phoenix_billing_invoice_url($o);
                        $is_hidden = (!$history_show_all && $i >= $history_limit);
                    ?>
                    <tr<?php echo $is_hidden ? ' class="billing-history-extra" style="display:none"' : ''; ?>>
                        <td style="color:#888;white-space:nowrap;"><?php echo esc_html($o_date); ?></td>
                        <td>
                            <span style="font-weight:600;color:#2c3e50;"><?php echo esc_html($description); ?></span>
                            <?php if ($is_prorate): ?><span class="billing-tag tag-prorate"><?php echo phoenix_text('billing.tag_prorated'); ?></span><?php endif; ?>
                            <?php if ($is_renewal): ?><span class="billing-tag tag-renewal"><?php echo phoenix_text('billing.tag_renewal'); ?></span><?php endif; ?>
                        </td>
                        <td>
                            <span class="billing-tag <?php echo $type === 'addon' ? 'tag-addon' : 'tag-plan'; ?>">
                                <?php echo $type === 'addon' ? phoenix_text('billing.tag_addon') : phoenix_text('billing.tag_plan'); ?>
                            </span>
                        </td>
                        <td style="font-weight:700;color:#2c3e50;"><?php echo $o_total; ?></td>
                        <td><span class="os-<?php echo esc_attr($o_status); ?>"><?php echo ucfirst($o_status); ?></span></td>
                        <td><a href="<?php echo esc_url($inv_url); ?>" class="billing-invoice-btn" target="_blank"><?php echo phoenix_text('billing.invoice_btn'); ?></a></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (!$history_show_all): ?>
                <div style="text-align:center;padding:10px 0 4px;">
                    <button class="billing-show-all-btn"
                        onclick="phoenixShowAllHistory('<?php echo $card_id; ?>', this)"
                        data-expanded="0"
                        data-label-more="▼ <?php echo esc_attr(phoenix_text('billing.history_show_all')); ?>"
                        data-label-less="▲ <?php echo esc_attr(phoenix_text('billing.history_show_less')); ?>">
                        ▼ <?php echo phoenix_text('billing.history_show_all'); ?>
                    </button>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>

        </div><!-- .billing-card-body -->
    </div><!-- .billing-card -->

    <?php endforeach; ?>

    <!-- Cancel confirm modal -->
	<div id="phoenix-cancel-modal">
		<div class="phoenix-cancel-box">
			<h3 style="margin:0 0 8px;color:#c62828;font-size:17px;font-weight:700;"><?php echo phoenix_text('billing.cancel_modal_title'); ?></h3>
			<p id="phoenix-cancel-desc" style="margin:0 0 20px;color:#555;font-size:13px;line-height:1.6;"></p>

			<?php if (true): ?>
			<div style="display:flex;gap:10px;justify-content:flex-end;">
				<button onclick="phoenixCloseCancelModal()" style="padding:9px 18px;border:1px solid #ddd;border-radius:6px;background:#fff;font-size:13px;font-weight:600;cursor:pointer;color:#555;"><?php echo phoenix_text('billing.btn_keep'); ?></button>
				<button id="phoenix-cancel-confirm" style="padding:9px 18px;border:none;border-radius:6px;background:#c62828;color:#fff;font-size:13px;font-weight:600;cursor:pointer;">Yes, Cancel</button>
			</div>
			<div id="phoenix-cancel-status" style="margin-top:10px;font-size:13px;min-height:16px;"></div>
			<?php endif; ?>

		</div>
	</div>

    <script>
    function phoenixShowAllHistory(cardId, btn) {
        var rows = document.querySelectorAll('#' + cardId + '-history .billing-history-extra');
        var isExpanded = btn.dataset.expanded === '1';
        if (isExpanded) {
            rows.forEach(function(r) { r.style.display = 'none'; });
            btn.dataset.expanded = '0';
            btn.innerHTML = btn.dataset.labelMore;
        } else {
            rows.forEach(function(r) { r.style.display = ''; });
            btn.dataset.expanded = '1';
            btn.innerHTML = btn.dataset.labelLess;
        }
    }

    function phoenixBillingToggle(cardId) {
        var body    = document.getElementById(cardId + '-body');
        var chevron = document.getElementById(cardId + '-chevron');
        var header  = body.previousElementSibling;
        var isOpen  = body.classList.contains('open');
        body.classList.toggle('open', !isOpen);
        chevron.classList.toggle('open', !isOpen);
        header.classList.toggle('open', !isOpen);
    }
    // Auto-open first active card
    document.addEventListener('DOMContentLoaded', function() {
        var first = document.querySelector('.billing-card:not(.cancelled-card) .billing-card-header');
        if (first) first.click();
    });

    // ── Cancel Modal ─────────────────────────────────────────────────────────
    var _cancelSubId = null;

    function phoenixOpenCancelModal(subId, instanceName, endDate) {
        _cancelSubId = subId;
        var d = window.phoenixBillingData || {};
        var warnText = d.txt_cancel_warn || 'This action cannot be undone.';
        var line1 = (d.txt_cancel_about || '').replace('%s', instanceName);
        var line2 = (d.txt_cancel_until || '').replace('%s', endDate) + ' ' + (d.txt_cancel_after || '');
        var desc = line1 + '\n\n' + line2 + '\n\n' + warnText;
        var el = document.getElementById('phoenix-cancel-desc');
        el.style.whiteSpace = 'pre-line';
        el.textContent = desc;
        document.getElementById('phoenix-cancel-status').textContent = '';
        document.getElementById('phoenix-cancel-confirm').disabled = false;
        document.getElementById('phoenix-cancel-confirm').textContent = (d.txt_yes_cancel || 'Yes, Cancel');
        document.getElementById('phoenix-cancel-modal').classList.add('open');
        document.body.style.overflow = 'hidden';
        document.getElementById('phoenix-cancel-confirm').onclick = function() {
            phoenixDoCancel(subId);
        };
    }

    function phoenixCloseCancelModal() {
        document.getElementById('phoenix-cancel-modal').classList.remove('open');
        document.body.style.overflow = '';
        _cancelSubId = null;
    }

    function phoenixOpenThemeModal(modalId) {
        var modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }

    function phoenixCloseThemeModal(modalId) {
        var modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }
    }

    function phoenixDoCancel(subId) {
        var btn = document.getElementById('phoenix-cancel-confirm');
        btn.disabled = true;
        btn.textContent = (window.phoenixBillingData && window.phoenixBillingData.txt_cancelling) || 'Cancelling...';
        var nonce = (window.phoenixBillingData && window.phoenixBillingData.nonce) ? window.phoenixBillingData.nonce : '';
        fetch((window.phoenixBillingData && window.phoenixBillingData.ajaxurl) ? window.phoenixBillingData.ajaxurl : '/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=phoenix_cancel_subscription&nonce=' + nonce + '&subscription_id=' + subId
        }).then(r => r.json()).then(data => {
            if (data.success) {
                document.getElementById('phoenix-cancel-status').innerHTML =
                    '<span style="color:#2e7d32;">✓ ' + data.data.message + '</span>';
                setTimeout(function() { location.reload(); }, 1800);
            } else {
                document.getElementById('phoenix-cancel-status').innerHTML =
                    '<span style="color:#c62828;">✗ ' + data.data.message + '</span>';
                btn.disabled = false;
                btn.textContent = (window.phoenixBillingData && window.phoenixBillingData.txt_yes_cancel) || 'Yes, Cancel';
            }
        }).catch(function() {
            var _d = window.phoenixBillingData || {};
            document.getElementById('phoenix-cancel-status').innerHTML =
                '<span style="color:#c62828;">' + (_d.txt_wrong || '✗ Something went wrong. Please try again.') + '</span>';
            btn.disabled = false;
            btn.textContent = (_d.txt_yes_cancel || 'Yes, Cancel');
        });
    }

    // ── Reactivate ───────────────────────────────────────────────────────────
    function phoenixReactivateSub(subId, btn) {
        btn.disabled = true;
        btn.textContent = (window.phoenixBillingData && window.phoenixBillingData.txt_reactivating) || 'Reactivating...';
        var nonce = (window.phoenixBillingData && window.phoenixBillingData.nonce) ? window.phoenixBillingData.nonce : '';
        fetch((window.phoenixBillingData && window.phoenixBillingData.ajaxurl) ? window.phoenixBillingData.ajaxurl : '/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=phoenix_reactivate_subscription&nonce=' + nonce + '&subscription_id=' + subId
        }).then(r => r.json()).then(data => {
            var _d = window.phoenixBillingData || {};
            if (data.success) {
                btn.textContent = _d.txt_reactivated_ok || '✓ Reactivated!';
                btn.style.background = '#c8e6c9';
                setTimeout(function() { location.reload(); }, 1500);
            } else {
                btn.textContent = _d.txt_reactivated_err || '✗ Failed';
                btn.disabled = false;
                setTimeout(function() { btn.textContent = _d.txt_reactivate || '↩ Reactivate'; btn.disabled = false; }, 2000);
            }
        });
    }
    </script>

    <script>
    window.phoenixBillingData = {
        nonce: '<?php echo wp_create_nonce("phoenix_billing_nonce"); ?>',
        ajaxurl: '<?php echo esc_js(admin_url("admin-ajax.php")); ?>',
        txt_cancel_about:   '<?php echo esc_js(phoenix_text("billing.js_cancel_about")); ?>',
        txt_cancel_until:   '<?php echo esc_js(phoenix_text("billing.js_cancel_until")); ?>',
        txt_cancel_after:   '<?php echo esc_js(phoenix_text("billing.js_cancel_after")); ?>',
        txt_cancel_warn:    '<?php echo esc_js(phoenix_text("billing.js_cancel_warn")); ?>',
        txt_yes_cancel:     '<?php echo esc_js(phoenix_text("billing.btn_confirm_cancel")); ?>',
        txt_cancelling:     '<?php echo esc_js(phoenix_text("billing.btn_cancelling")); ?>',
        txt_reactivating:   '<?php echo esc_js(phoenix_text("billing.btn_reactivating")); ?>',
        txt_reactivated_ok: '<?php echo esc_js(phoenix_text("billing.reactivated_success")); ?>',
        txt_reactivated_err:'<?php echo esc_js(phoenix_text("billing.reactivated_failed")); ?>',
        txt_reactivate:     '<?php echo esc_js(phoenix_text("billing.btn_reactivate")); ?>',
        txt_wrong:          '<?php echo esc_js(phoenix_text("billing.js_something_wrong")); ?>'
    };
    </script>

    <?php
}

// ============================================================================
// SECTION: UPDATE PAYMENT METHOD
// (Digabung dari phoenix-payment-method.php)
// ============================================================================

/**
 * AJAX: Buat Stripe SetupIntent baru
 */
add_action('wp_ajax_phoenix_create_setup_intent', 'phoenix_ajax_create_setup_intent');
function phoenix_ajax_create_setup_intent() {
    check_ajax_referer('phoenix_payment_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error(['message' => phoenix_text('billing.msg_not_logged_in')]);

    $user_id            = get_current_user_id();
    $stripe_customer_id = get_user_meta($user_id, '_stripe_customer_id', true)
        ?: get_user_meta($user_id, 'stripe_id', true);

    if (!$stripe_customer_id) wp_send_json_error(['message' => phoenix_text('billing.msg_stripe_no_customer')]);

    $stripe_settings = get_option('woocommerce_stripe_settings', []);
    $test_mode       = isset($stripe_settings['testmode']) && $stripe_settings['testmode'] === 'yes';
    $secret_key      = $test_mode ? ($stripe_settings['test_secret_key'] ?? '') : ($stripe_settings['secret_key'] ?? '');

    if (!$secret_key) wp_send_json_error(['message' => phoenix_text('billing.msg_gateway_not_configured')]);

    $response = wp_remote_post('https://api.stripe.com/v1/setup_intents', [
        'headers' => [
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ],
        'body'    => [
            'customer'             => $stripe_customer_id,
            'payment_method_types' => ['card'],
            'usage'                => 'off_session',
        ],
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) wp_send_json_error(['message' => phoenix_text('billing.msg_gateway_connect_failed')]);

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($body['error'])) wp_send_json_error(['message' => $body['error']['message'] ?? phoenix_text('billing.msg_stripe_error')]);

    wp_send_json_success([
        'client_secret'   => $body['client_secret'],
        'setup_intent_id' => $body['id'],
        'publishable_key' => $test_mode
            ? ($stripe_settings['test_publishable_key'] ?? '')
            : ($stripe_settings['publishable_key'] ?? ''),
    ]);
}

/**
 * AJAX: Save payment method baru setelah SetupIntent dikonfirmasi client-side
 */
add_action('wp_ajax_phoenix_save_payment_method', 'phoenix_ajax_save_payment_method');
function phoenix_ajax_save_payment_method() {
    check_ajax_referer('phoenix_payment_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error(['message' => phoenix_text('billing.msg_not_logged_in')]);

    $user_id        = get_current_user_id();
    $payment_method = sanitize_text_field($_POST['payment_method_id'] ?? '');

    if (!$payment_method || strpos($payment_method, 'pm_') !== 0)
        wp_send_json_error(['message' => phoenix_text('billing.msg_invalid_pm')]);

    $stripe_settings    = get_option('woocommerce_stripe_settings', []);
    $test_mode          = isset($stripe_settings['testmode']) && $stripe_settings['testmode'] === 'yes';
    $secret_key         = $test_mode ? ($stripe_settings['test_secret_key'] ?? '') : ($stripe_settings['secret_key'] ?? '');
    $stripe_customer_id = get_user_meta($user_id, '_stripe_customer_id', true)
        ?: get_user_meta($user_id, 'stripe_id', true);

    if (!$stripe_customer_id || !$secret_key) wp_send_json_error(['message' => phoenix_text('billing.msg_config_error')]);

    // Set default payment method di Stripe customer
    $response = wp_remote_post("https://api.stripe.com/v1/customers/{$stripe_customer_id}", [
        'headers' => [
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ],
        'body'    => ['invoice_settings[default_payment_method]' => $payment_method],
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) wp_send_json_error(['message' => phoenix_text('billing.msg_pm_update_failed')]);
    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($body['error'])) wp_send_json_error(['message' => $body['error']['message'] ?? phoenix_text('billing.msg_stripe_error')]);

    // Ambil info kartu
    $pm_body = json_decode(wp_remote_retrieve_body(wp_remote_get(
        "https://api.stripe.com/v1/payment_methods/{$payment_method}",
        ['headers' => ['Authorization' => 'Bearer ' . $secret_key], 'timeout' => 15]
    )), true);
    $card = $pm_body['card'] ?? [];

    // Update semua WCS subscription aktif
    if (function_exists('wcs_get_users_subscriptions')) {
        foreach (wcs_get_users_subscriptions($user_id) as $subscription) {
            if (!in_array($subscription->get_status(), ['active', 'on-hold', 'pending-cancel'])) continue;
            update_post_meta($subscription->get_id(), '_stripe_source_id', $payment_method);
            update_post_meta($subscription->get_id(), '_payment_method', 'stripe');
            update_post_meta($subscription->get_id(), '_payment_method_title', 'Credit / Debit Card');

            // Kalau on-hold karena grace period → trigger retry
            if ($subscription->get_status() === 'on-hold' &&
                get_post_meta($subscription->get_id(), '_phoenix_grace_period_active', true)) {
                $subscription->update_status('active', phoenix_text('billing.note_card_reactivated'));
                delete_post_meta($subscription->get_id(), '_phoenix_grace_period_active');
                delete_post_meta($subscription->get_id(), '_phoenix_grace_period_start');
                delete_post_meta($subscription->get_id(), '_phoenix_retry_count');
                do_action('phoenix_retry_renewal_after_card_update', $subscription->get_id());
            }
        }
    }

    // Simpan info kartu di user meta
    update_user_meta($user_id, '_phoenix_card_last4', $card['last4'] ?? '');
    update_user_meta($user_id, '_phoenix_card_brand', $card['brand'] ?? '');
    update_user_meta($user_id, '_phoenix_card_exp',   ($card['exp_month'] ?? '') . '/' . ($card['exp_year'] ?? ''));

    wp_send_json_success([
        'message' => phoenix_text('billing.payment_updated_msg'),
        'card'    => [
            'brand' => ucfirst($card['brand'] ?? 'Card'),
            'last4' => $card['last4'] ?? '****',
            'exp'   => ($card['exp_month'] ?? '') . '/' . ($card['exp_year'] ?? ''),
        ],
    ]);
}

/**
 * Ambil info kartu aktif dari Stripe API
 */
function phoenix_get_stripe_card_info($user_id) {
    $stripe_customer_id = get_user_meta($user_id, '_stripe_customer_id', true)
        ?: get_user_meta($user_id, 'stripe_id', true);
    if (!$stripe_customer_id) return [];

    $stripe_settings = get_option('woocommerce_stripe_settings', []);
    $test_mode       = isset($stripe_settings['testmode']) && $stripe_settings['testmode'] === 'yes';
    $secret_key      = $test_mode ? ($stripe_settings['test_secret_key'] ?? '') : ($stripe_settings['secret_key'] ?? '');
    if (!$secret_key) return [];

    $response = wp_remote_get(
        "https://api.stripe.com/v1/customers/{$stripe_customer_id}?expand[]=invoice_settings.default_payment_method",
        ['headers' => ['Authorization' => 'Bearer ' . $secret_key], 'timeout' => 10]
    );
    if (is_wp_error($response)) return [];

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $pm   = $body['invoice_settings']['default_payment_method'] ?? null;
    if (!$pm || !is_array($pm)) return [];

    $card = $pm['card'] ?? [];
    return [
        'brand' => $card['brand'] ?? '',
        'last4' => $card['last4'] ?? '',
        'exp'   => ($card['exp_month'] ?? '') . '/' . ($card['exp_year'] ?? ''),
    ];
}

/**
 * Enqueue Stripe.js + modal JS — hanya di halaman My Account
 */
add_action('wp_enqueue_scripts', 'phoenix_enqueue_payment_method_assets');
function phoenix_enqueue_payment_method_assets() {
    if (!is_user_logged_in() || !is_account_page()) return;
    wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', [], null, true);
    wp_add_inline_script('stripe-js', phoenix_payment_method_inline_js());
}

function phoenix_payment_method_inline_js() {
    $nonce           = wp_create_nonce('phoenix_payment_nonce');
    $ajax            = admin_url('admin-ajax.php');
    $txt_save_btn      = esc_js(phoenix_text('billing.payment_save_btn'));
    $txt_saved         = esc_js(phoenix_text('billing.payment_saved'));
    $txt_modal_title   = esc_js(phoenix_text('billing.payment_modal_title'));
    $txt_modal_sub     = esc_js(phoenix_text('billing.payment_modal_subtitle'));
    $txt_processing    = esc_js(phoenix_text('billing.payment_processing'));
    $txt_wrong         = esc_js(phoenix_text('billing.js_something_wrong'));
    $txt_init_failed   = esc_js(phoenix_text('billing.msg_pm_init_failed'));
    $txt_card_updated  = esc_js(phoenix_text('billing.msg_card_updated'));
    return <<<JS
(function() {
    var stripe, cardElement;

    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-phoenix-update-card]')) {
            e.preventDefault(); e.stopPropagation();
            phoenixOpenCardModal();
        }
    });

    function phoenixOpenCardModal() {
        if (!document.getElementById('phoenix-card-modal')) buildModal();
        document.getElementById('phoenix-card-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        document.getElementById('phoenix-card-status').textContent = '';
        document.getElementById('phoenix-card-submit').disabled = false;
        document.getElementById('phoenix-card-submit').textContent = '{$txt_save_btn}';
        initStripeElements();
    }

    function buildModal() {
        var overlay = document.createElement('div');
        overlay.id = 'phoenix-card-modal';
        overlay.style.cssText = 'display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:99999;align-items:center;justify-content:center;';
        overlay.innerHTML =
            '<div style="background:#fff;border-radius:10px;width:100%;max-width:460px;padding:36px;position:relative;box-shadow:0 8px 32px rgba(0,0,0,0.18);">' +
            '<button id="phoenix-card-close" style="position:absolute;top:16px;right:20px;background:none;border:none;font-size:22px;cursor:pointer;color:#888;">×</button>' +
            '<h3 style="margin:0 0 6px;color:#1E4A7A;font-size:18px;font-weight:700;">{$txt_modal_title}</h3>' +
            '<p style="margin:0 0 24px;color:#666;font-size:13px;">{$txt_modal_sub}</p>' +
            '<div id="phoenix-card-element" style="padding:14px;border:1.5px solid #d1d9e0;border-radius:6px;background:#fafbfc;min-height:44px;"></div>' +
            '<div id="phoenix-card-errors" style="color:#dc2626;font-size:13px;margin-top:8px;min-height:18px;"></div>' +
            '<div id="phoenix-card-status" style="margin-top:12px;font-size:13px;min-height:18px;"></div>' +
            '<button id="phoenix-card-submit" style="margin-top:24px;width:100%;padding:13px;background:#1E4A7A;color:#fff;border:none;border-radius:6px;font-size:15px;font-weight:600;cursor:pointer;">{$txt_save_btn}</button>' +
            '</div>';
        document.body.appendChild(overlay);
        document.getElementById('phoenix-card-close').addEventListener('click', closeModal);
        overlay.addEventListener('click', function(e) { if (e.target === overlay) closeModal(); });
        document.getElementById('phoenix-card-submit').addEventListener('click', handleSubmit);
    }

    function closeModal() {
        var modal = document.getElementById('phoenix-card-modal');
        if (modal) modal.style.display = 'none';
        document.body.style.overflow = '';
        if (cardElement) { cardElement.unmount(); cardElement = null; stripe = null; }
    }

    function initStripeElements() {
        fetch('{$ajax}', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'action=phoenix_create_setup_intent&nonce={$nonce}'
        }).then(r => r.json()).then(data => {
            if (!data.success) { setStatus('Error: ' + data.data.message, 'error'); return; }
            stripe = Stripe(data.data.publishable_key);
            window._phoenixClientSecret = data.data.client_secret;
            cardElement = stripe.elements().create('card', {
                style: { base: { fontSize:'15px', color:'#1a202c', fontFamily:'Arial,sans-serif', '::placeholder':{color:'#adb5bd'} }, invalid:{color:'#dc2626'} }
            });
            cardElement.mount('#phoenix-card-element');
            cardElement.on('change', e => { document.getElementById('phoenix-card-errors').textContent = e.error ? e.error.message : ''; });
        }).catch(() => setStatus('{$txt_init_failed}', 'error'));
    }

    function handleSubmit() {
        if (!stripe || !cardElement) return;
        var btn = document.getElementById('phoenix-card-submit');
        btn.disabled = true; btn.textContent = '{$txt_processing}'; setStatus('');
        stripe.confirmCardSetup(window._phoenixClientSecret, { payment_method: { card: cardElement } })
        .then(result => {
            if (result.error) throw new Error(result.error.message);
            return fetch('{$ajax}', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'action=phoenix_save_payment_method&nonce={$nonce}&payment_method_id=' + result.setupIntent.payment_method
            });
        }).then(r => r.json()).then(data => {
            if (!data.success) throw new Error(data.data.message);
            var c = data.data.card;
            setStatus('{$txt_card_updated}'.replace('%s', c.brand).replace('%s', c.last4).replace('%s', c.exp), 'success');
            btn.textContent = '{$txt_saved}';
            document.querySelectorAll('[data-phoenix-card-display]').forEach(el => {
                el.textContent = c.brand + ' •••• ' + c.last4;
            });
            setTimeout(closeModal, 2200);
        }).catch(err => {
            setStatus(err.message || '{$txt_wrong}', 'error');
            btn.disabled = false; btn.textContent = '{$txt_save_btn}';
        });
    }

    function setStatus(msg, type) {
        var el = document.getElementById('phoenix-card-status');
        if (!el) return;
        el.textContent = msg;
        el.style.color = type === 'error' ? '#dc2626' : type === 'success' ? '#16a34a' : '#666';
    }
})();
JS;
}


// =============================================================================
// AJAX: Cancel Subscription
// =============================================================================
add_action('wp_ajax_phoenix_cancel_subscription', 'phoenix_ajax_cancel_subscription');
function phoenix_ajax_cancel_subscription() {
    check_ajax_referer('phoenix_billing_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error(['message' => phoenix_text('billing.msg_not_logged_in')]);

    $sub_id = (int) ($_POST['subscription_id'] ?? 0);
    if (!$sub_id) wp_send_json_error(['message' => phoenix_text('billing.msg_invalid_sub')]);

    $subscription = wcs_get_subscription($sub_id);
    if (!$subscription) wp_send_json_error(['message' => phoenix_text('billing.msg_not_found')]);

    // Security: pastikan subscription milik user ini
    if ($subscription->get_user_id() !== get_current_user_id())
        wp_send_json_error(['message' => phoenix_text('billing.msg_access_denied')]);

    // Hanya bisa cancel kalau active
    if (!$subscription->has_status('active'))
        wp_send_json_error(['message' => phoenix_text('billing.msg_not_active')]);

    // Ambil next_payment SEBELUM update status — WCS menghapusnya saat pending-cancel
    $end_date = $subscription->get_date('next_payment');
    $end_fmt  = $end_date ? date('d M Y', strtotime($end_date)) : '';

    // Cancel → pending-cancel (tetap aktif sampai end date)
    $subscription->update_status('pending-cancel', 'Subscription cancelled by user via My Billing.');

    wp_send_json_success([
        'message' => sprintf(phoenix_text('billing.msg_cancelled'), $end_fmt),
    ]);
}

// =============================================================================
// AJAX: Reactivate Subscription
// =============================================================================
add_action('wp_ajax_phoenix_reactivate_subscription', 'phoenix_ajax_reactivate_subscription');
function phoenix_ajax_reactivate_subscription() {
    check_ajax_referer('phoenix_billing_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error(['message' => phoenix_text('billing.msg_not_logged_in')]);

    $sub_id = (int) ($_POST['subscription_id'] ?? 0);
    if (!$sub_id) wp_send_json_error(['message' => phoenix_text('billing.msg_invalid_sub')]);

    $subscription = wcs_get_subscription($sub_id);
    if (!$subscription) wp_send_json_error(['message' => phoenix_text('billing.msg_not_found')]);

    // Security: pastikan subscription milik user ini
    if ($subscription->get_user_id() !== get_current_user_id())
        wp_send_json_error(['message' => phoenix_text('billing.msg_access_denied')]);

    // Hanya bisa reactivate kalau pending-cancel
    if (!$subscription->has_status('pending-cancel'))
        wp_send_json_error(['message' => phoenix_text('billing.msg_not_pending')]);

    $subscription->update_status('active', 'Subscription reactivated by user via My Billing.');

    // Sync addon subscriptions — ikut active kembali
    if (function_exists('wcs_get_users_subscriptions')) {
        $all_subs = wcs_get_users_subscriptions($subscription->get_user_id());
        foreach ($all_subs as $addon_sub) {
            if ($addon_sub->get_id() === $sub_id) continue;
            if (!$addon_sub->has_status('pending-cancel')) continue;
            $is_addon = false;
            foreach ($addon_sub->get_items() as $item) {
                if (has_term('add-on', 'product_cat', $item->get_product_id())) {
                    $is_addon = true; break;
                }
            }
            if (!$is_addon) continue;
            if (function_exists('phoenix_addon_belongs_to_main_plan') &&
                !phoenix_addon_belongs_to_main_plan($addon_sub->get_id(), $sub_id)) continue;
            $addon_sub->update_status('active', 'Auto reactivated: main plan reactivated by user via My Billing.');
        }
    }

    wp_send_json_success([
        'message' => phoenix_text('billing.msg_reactivated'),
    ]);
}