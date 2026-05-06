// ================================================================
// SNIPPET: Upgrade Plan Page — v4
// Flow: UPGRADE NOW → AJAX (buat GF entry + add WCS switch to cart) → redirect checkout
// User tidak pernah lihat product page atau GF form
//
// v4 changes:
// + WIZARD GATE: blokir render page kalau user belum selesaikan setup wizard
//   Cek via phoenix_is_wizard_complete() dari snippet my-saas-instances
//   Fail open: fungsi tidak ada / API error → render normal (jangan blokir)
//   ON/OFF: matikan snippet my-saas-instances untuk bypass gate ini
// ================================================================

// ================================================================
// AJAX: Submit GF + Add WCS switch item to cart programmatically
// ================================================================
add_action('wp_ajax_phoenix_submit_upgrade_gf', 'phoenix_submit_upgrade_gf');
function phoenix_submit_upgrade_gf() {
    check_ajax_referer('phoenix_upgrade_gf', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['msg' => 'Not logged in']);
    }

    $sub_id      = isset($_POST['sub_id'])      ? absint($_POST['sub_id'])      : 0;
    $form_id     = isset($_POST['form_id'])     ? absint($_POST['form_id'])     : 0;
    $variation_id= isset($_POST['variation_id'])? absint($_POST['variation_id']): 0;

    if (!$sub_id || !in_array($form_id, [58, 61]) || !$variation_id) {
        wp_send_json_error(['msg' => 'Invalid params', 'got' => compact('sub_id','form_id','variation_id')]);
    }

    $user_id = get_current_user_id();

    if (!function_exists('phoenix_user_owns_subscription') || !phoenix_user_owns_subscription($sub_id, $user_id)) {
        wp_send_json_error(['msg' => 'Access denied']);
    }

    $subscription = wcs_get_subscription($sub_id);
    if (!$subscription || !$subscription->has_status('active')) {
        wp_send_json_error(['msg' => 'Subscription not active']);
    }

    // Ambil item_id dari subscription
    $item_id    = 0;
    $item_debug = [];
    foreach ($subscription->get_items() as $iid => $item) {
        $item_debug[] = [
            'iid'        => $iid,
            'product_id' => $item->get_product_id(),
            'name'       => $item->get_name(),
            'is_addon'   => has_term('add-on', 'product_cat', $item->get_product_id()),
        ];
        if (!has_term('add-on', 'product_cat', $item->get_product_id())) {
            $item_id = $iid; break;
        }
    }
    if (!$item_id) {
        wp_send_json_error(['msg' => 'Subscription item not found', 'items' => $item_debug, 'sub_id' => $sub_id]);
    }

    // ── Ambil data instance ──────────────────────────────────────
    global $wpdb;
    $org = $server = $subdomain = $domain = '';

    $parent_order_id = $subscription->get_parent_id();
    if ($parent_order_id) {
        $parent_order = wc_get_order($parent_order_id);
        if ($parent_order) {
            foreach ($parent_order->get_items() as $item) {
                if (has_term('add-on', 'product_cat', $item->get_product_id())) continue;
                foreach ($item->get_meta_data() as $m) {
                    if (!$org       && stripos($m->key, 'organisation') !== false) $org = $m->value;
                    if (!$server    && stripos($m->key, 'server') !== false)        $server = $m->value;
                    if (!$subdomain && $m->key === 'Enter your subdomain')          $subdomain = $m->value;
                    if (!$domain    && $m->key === "Select one of Phoenix's domain") $domain = $m->value;
                }
            }
        }
    }

    if (!$org || !$subdomain) {
        $tenant_uuid = $wpdb->get_var($wpdb->prepare(
            "SELECT tenant_uuid FROM {$wpdb->prefix}wbssaas_tenants WHERE subscription_wc_id = %d LIMIT 1", $sub_id
        ));
        if (!$tenant_uuid) {
            $tenant_uuid = $wpdb->get_var($wpdb->prepare(
                "SELECT tenant_uuid FROM {$wpdb->prefix}wbssaas_tenants WHERE customer_id = %d ORDER BY created ASC LIMIT 1", $user_id
            ));
        }
        if ($tenant_uuid && function_exists('GFAPI')) {
            $tenant_row = $wpdb->get_row($wpdb->prepare(
                "SELECT tenant_url, tenant_location FROM {$wpdb->prefix}wbssaas_tenants WHERE tenant_uuid = %s LIMIT 1", $tenant_uuid
            ));
            if ($tenant_row) {
                $host = parse_url('https://' . $tenant_row->tenant_url, PHP_URL_HOST) ?: $tenant_row->tenant_url;
                $host = preg_replace('/\.stg\./i', '.', $host);
                $sub_from_url = explode('.', $host)[0] ?? '';
                if ($sub_from_url) {
                    $entries = GFAPI::get_entries(70, [
                        'field_filters' => [['key' => '4', 'value' => $sub_from_url]],
                        'status' => 'active',
                    ], ['key' => 'date_created', 'direction' => 'DESC'], ['page_size' => 1]);
                    if (!empty($entries[0])) {
                        $e = $entries[0];
                        if (!$org)       $org       = $e['1'] ?? '';
                        if (!$subdomain) $subdomain = $e['4'] ?? '';
                        if (!$domain)    $domain    = $e['5'] ?? '';
                        if (!$server)    $server    = $tenant_row->tenant_location ?? '';
                    }
                }
                if (!$server) $server = $tenant_row->tenant_location ?? '';
            }
        }
    }

    if (!$server) {
        $t = $wpdb->get_row($wpdb->prepare(
            "SELECT tenant_location FROM {$wpdb->prefix}wbssaas_tenants WHERE subscription_wc_id = %d LIMIT 1", $sub_id
        ));
        if ($t && $t->tenant_location && $t->tenant_location !== 'staging') $server = $t->tenant_location;
    }

    if ((!$org || !$subdomain) && function_exists('GFAPI')) {
        foreach ([70, 58] as $fallback_form) {
            $field_sub = ($fallback_form === 61) ? '5' : '4';
            $all_entries = GFAPI::get_entries($fallback_form, [
                'field_filters' => [['key' => 'created_by', 'value' => $user_id]],
                'status'        => 'active',
            ], ['key' => 'date_created', 'direction' => 'ASC'], ['page_size' => 20]);
            foreach ($all_entries as $fe) {
                $fe_sub = $fe[$field_sub] ?? '';
                if (!$fe_sub || strpos($fe_sub, 'upgrade-') === 0) continue;
                if (!$org)       $org       = $fe['3'] ?? '';
                if (!$subdomain) $subdomain = $fe_sub;
                if (!$domain)    $domain    = $fe['5'] ?? '';
                if (!$server)    $server    = $fe['9'] ?? $server;
                break 2;
            }
        }
    }

    if (!$org || !$subdomain) {
        $tenant_rows = $wpdb->get_results($wpdb->prepare(
            "SELECT tenant_name, tenant_url FROM {$wpdb->prefix}wbssaas_tenants
             WHERE customer_id = %d AND tenant_url != '' AND tenant_url NOT LIKE 'upgrade-%'
             ORDER BY created ASC LIMIT 5",
            $user_id
        ));
        foreach ($tenant_rows as $tr) {
            if (!$org && $tr->tenant_name) $org = $tr->tenant_name;
            if (!$subdomain && $tr->tenant_url) {
                $h = parse_url('https://' . $tr->tenant_url, PHP_URL_HOST) ?: $tr->tenant_url;
                $h = preg_replace('/\.stg\./i', '.', $h);
                $parts = explode('.', $h);
                if (!empty($parts[0]) && strpos($parts[0], 'upgrade-') !== 0) {
                    $subdomain = $parts[0];
                    if (!$domain && count($parts) > 1) $domain = implode('.', array_slice($parts, 1));
                }
            }
            if ($org && $subdomain) break;
        }
    }

    if (!$org || !$subdomain) {
        wp_send_json_error(['msg' => 'Could not retrieve instance data', 'org' => $org, 'subdomain' => $subdomain]);
    }

    if (!class_exists('GFAPI')) {
        wp_send_json_error(['msg' => 'GFAPI not available']);
    }

    $fake_sub = 'upgrade-' . substr(md5($sub_id . time()), 0, 8);
    $entry_data = ($form_id === 58) ? [
        'form_id' => 58, 'status' => 'active', 'created_by' => $user_id,
        '3' => $org, '9' => $server, '4' => $fake_sub, '5' => $domain, '1' => 'upgrade',
    ] : [
        'form_id' => 61, 'status' => 'active', 'created_by' => $user_id,
        '3' => $org, '17' => $server, '5' => $fake_sub, '6' => $domain, '1' => 'upgrade',
    ];

    $entry_id = GFAPI::add_entry($entry_data);
    if (is_wp_error($entry_id)) {
        wp_send_json_error(['msg' => 'GF entry error: ' . $entry_id->get_error_message()]);
    }

    $sub_field = ($form_id === 58) ? 4 : 5;
    $dom_field = ($form_id === 58) ? 5 : 6;
    GFAPI::update_entry_field($entry_id, $sub_field, $subdomain);
    if ($domain) GFAPI::update_entry_field($entry_id, $dom_field, $domain);

    update_option('_phoenix_upgrade_subdomain_' . $user_id, $subdomain);
    update_option('_phoenix_upgrade_domain_'    . $user_id, $domain);

    $product_map = [61=>58, 62=>58, 78=>76, 79=>76];
    $product_id  = $product_map[$variation_id] ?? 0;
    if (!$product_id) {
        wp_send_json_error(['msg' => 'Invalid variation_id: ' . $variation_id]);
    }

    $plan_hierarchy = function_exists('phoenix_get_plan_hierarchy')
        ? phoenix_get_plan_hierarchy()
        : [30688=>1,11=>1,58=>2,61=>2,62=>2,76=>3,78=>3,79=>3];
    $current_level = 0;
    foreach ($subscription->get_items() as $_chk) {
        $pid = $_chk->get_product_id();
        if (isset($plan_hierarchy[$pid])) { $current_level = $plan_hierarchy[$pid]; break; }
    }
    $is_free_plan = ($current_level <= 1);

    if (WC()->session) {
        WC()->session->set('phoenix_upgrade_from_sub', $sub_id);
        WC()->session->set('phoenix_upgrade_item_id',  $item_id);
    }
    update_option('_phoenix_upgrade_from_sub_' . $user_id, $sub_id);
    update_option('_phoenix_upgrade_org_'      . $user_id, $org);

    $redirect_url = add_query_arg([
        'add-to-cart' => $variation_id,
        'quantity'    => 1,
    ], phoenix_wpml_url(wc_get_checkout_url()));

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[phoenix_upgrade] entry='.$entry_id.' free='.$is_free_plan.' vid='.$variation_id.' redirect='.$redirect_url);
    }

    wp_send_json_success([
        'entry_id'    => $entry_id,
        'org'         => $org,
        'server'      => $server,
        'subdomain'   => $subdomain,
        'domain'      => $domain,
        'item_id'     => $item_id,
        'is_free'     => $is_free_plan,
        'fallback'    => false,
        'redirect_url'=> $redirect_url,
        'msg'         => 'Redirecting to checkout via add-to-cart',
    ]);
}

// ================================================================
// Restore subdomain di order item meta saat checkout
// ================================================================
if (!function_exists('phoenix_fix_order_item_meta')) {
    add_action('woocommerce_checkout_create_order_line_item', 'phoenix_fix_order_item_meta', 20, 4);
    function phoenix_fix_order_item_meta($item, $cart_item_key, $values, $order) {
        $user_id        = get_current_user_id();
        $real_subdomain = get_option('_phoenix_upgrade_subdomain_' . $user_id);
        $real_domain    = get_option('_phoenix_upgrade_domain_'    . $user_id);
        if (!$real_subdomain) return;
        foreach ($item->get_meta_data() as $meta) {
            if ($meta->key === 'Enter your subdomain' && strpos($meta->value, 'upgrade-') === 0) {
                $item->update_meta_data('Enter your subdomain', $real_subdomain);
            }
        }
        delete_option('_phoenix_upgrade_subdomain_' . $user_id);
        delete_option('_phoenix_upgrade_domain_'    . $user_id);
    }
}


// ================================================================
// Shortcode
// ================================================================
if (!function_exists('phoenix_wpml_url')) {
    function phoenix_wpml_url($url) {
        return apply_filters('wpml_permalink', $url, apply_filters('wpml_current_language', null));
    }
}

add_shortcode('phoenix_upgrade_page', 'phoenix_render_upgrade_page');
function phoenix_render_upgrade_page() {
    if (!is_user_logged_in()) {
        return '<p>Please <a href="' . esc_url(phoenix_wpml_url(wc_get_page_permalink('myaccount'))) . '">log in</a> to upgrade your plan.</p>';
    }

    $user_id = get_current_user_id();
    $sub_id  = isset($_GET['sub_id']) ? absint($_GET['sub_id']) : 0;

    if (!$sub_id) {
        return '<div style="padding:20px;background:#fff3cd;border-left:4px solid #ffc107;border-radius:6px;">
            ⚠️ No subscription selected. <a href="' . esc_url(phoenix_wpml_url(home_url('/my-account/workspaces/'))) . '">' . phoenix_text('upgrade.error_go_plans') . '</a></div>';
    }

    if (!function_exists('phoenix_user_owns_subscription') || !phoenix_user_owns_subscription($sub_id, $user_id)) {
        return '<div style="padding:20px;background:#ffebee;border-left:4px solid #e53935;border-radius:6px;">' . phoenix_text('upgrade.error_access_denied') . '</div>';
    }

    $subscription = wcs_get_subscription($sub_id);
    if (!$subscription || !$subscription->has_status('active')) {
        return '<div style="padding:20px;background:#fff3cd;border-left:4px solid #ffc107;border-radius:6px;">' . phoenix_text('upgrade.error_not_active') . '</div>';
    }

    // Block hanya kalau SEMUA item adalah addon (pure addon subscription)
    $all_items_are_addon = true;
    foreach ($subscription->get_items() as $item) {
        if (!has_term('add-on', 'product_cat', $item->get_product_id())) {
            $all_items_are_addon = false;
            break;
        }
    }
    if ($all_items_are_addon) {
        return '<div style="padding:20px;background:#fff3cd;border-left:4px solid #ffc107;border-radius:6px;">⚠️ Add-on subscriptions cannot be upgraded here. <a href="' . esc_url(phoenix_wpml_url(home_url('/my-account/workspaces/'))) . '">' . phoenix_text('upgrade.back_link') . '</a></div>';
    }

    // ── WIZARD GATE ───────────────────────────────────────────────────────────
    // Blokir render upgrade page kalau user belum selesaikan setup wizard.
    // phoenix_is_wizard_complete() di-define di snippet my-saas-instances.php.
    // Fail open: kalau fungsi tidak ada (snippet mati untuk testing) → lanjut normal.
    // ON/OFF gate ini: matikan snippet my-saas-instances → fungsi tidak ada → fail open.
    if (function_exists('phoenix_is_wizard_complete') && !phoenix_is_wizard_complete($sub_id, $user_id)) {

        // Ambil wizard URL untuk ditampilkan di halaman gate
        global $wpdb;
        $wg_row = $wpdb->get_row($wpdb->prepare(
            "SELECT tenant_uuid, tenant_url FROM {$wpdb->prefix}wbssaas_tenants
             WHERE customer_id = %d AND tenant_uuid != '' AND tenant_uuid IS NOT NULL
             ORDER BY created ASC LIMIT 1",
            $user_id
        ));
        $wg_wizard_url = '';
        if ($wg_row && $wg_row->tenant_uuid) {
            $wg_user   = get_user_by('id', $user_id);
            $wg_email  = $wg_user ? hash_hmac('sha256', $wg_user->user_email, AUTH_KEY) : '';
            $wg_wizard_url = rtrim($wg_row->tenant_url, '/') . '/clients/new'
                . '?u=' . urlencode($wg_row->tenant_uuid)
                . '&m=' . urlencode($wg_email);
        }

        $wg_my_saas = esc_url(phoenix_wpml_url(home_url('/my-account/workspaces/')));

        ob_start();
        ?>
        <div style="max-width:520px;padding:28px 32px;background:#fff8e1;border-left:4px solid #f39c12;border-radius:8px;margin:0 auto;">
            <div style="font-size:28px;margin-bottom:12px;">⚙️
			  <h2 style="display:inline;margin:0 0 10px;color:#b26a00;font-size:18px;">
				<?php echo phoenix_text('my_saas.wizard_title'); ?>
			  </h2>
			</div>
            <p style="margin:0 0 20px;color:#555;font-size:13px;line-height:1.7;">
                <?php echo phoenix_text('my_saas.wizard_desc'); ?><br><strong style="color:#b26a00;"><?php echo phoenix_text('my_saas.wizard_desc2'); ?></strong>
            </p>
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                <?php if ($wg_wizard_url): ?>
                <a href="<?php echo esc_url($wg_wizard_url); ?>" target="_blank"
                   style="display:inline-block;padding:11px 22px;background:#f39c12;color:#fff;text-decoration:none;
                          border-radius:6px;font-weight:700;font-size:13px;">
                    <?php echo phoenix_text('my_saas.wizard_cta'); ?>
                </a>
                <?php endif; ?>
                <a href="<?php echo $wg_my_saas; ?>"
                   style="display:inline-block;padding:11px 18px;background:#f5f5f5;color:#555;text-decoration:none;
                          border-radius:6px;font-weight:600;font-size:13px;border:1px solid #ddd;">
                    <?php echo phoenix_text('my_saas.wizard_backplan'); ?>
                </a>
            </div>
            <p style="margin:16px 0 0;font-size:11px;color:#aaa;">
                <?php echo phoenix_text('my_saas.wizard_note'); ?>
            </p>
        </div>
        <?php
        return ob_get_clean();
    }
    // ── END WIZARD GATE ───────────────────────────────────────────────────────

    $current_level = function_exists('phoenix_get_subscription_plan_level')
        ? phoenix_get_subscription_plan_level($subscription) : 0;
    $is_yearly     = function_exists('phoenix_is_yearly_subscription')
        ? phoenix_is_yearly_subscription($subscription) : false;

    $tenant   = function_exists('phoenix_get_tenant_by_subscription')
        ? phoenix_get_tenant_by_subscription($sub_id) : null;
    $org_name = $tenant ? $tenant->tenant_name : '—';

    $options        = get_option('wbssaas_options');
    $is_staging_env = isset($options['environment']) && $options['environment'] == 'staging';

    $saas_url_raw = ''; $saas_url = '';
    $org_data     = [];
    $pup_debug_html = '';
    if ($tenant) {
        global $wpdb;
        $oldest_row = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wbssaas_tenants
             WHERE customer_id = %d AND tenant_name = %s
             ORDER BY created ASC LIMIT 1",
            $user_id, $tenant->tenant_name
        ));
        $use_row      = $oldest_row ?: $tenant;
        $saas_url_raw = esc_url(rtrim($use_row->tenant_url, '/'));
        $saas_url     = preg_replace('/\.stg\./i', '.', $saas_url_raw);
        $org_data = [
            'org'    => $tenant->tenant_name,
            'server' => $tenant->tenant_location !== 'staging' ? ucfirst($tenant->tenant_location) : '',
        ];
    }

    if ($current_level >= 3) {
        $my_saas_url = esc_url(phoenix_wpml_url(home_url('/my-account/workspaces/')));
        $contact_url = esc_url(add_query_arg(['sub_id' => $sub_id, 'org' => urlencode($org_name)],
            $is_staging_env
                ? 'https://staging.phoenix-whistleblowing.com/contact-enterprise/?swcfpc=1'
                : 'https://phoenix-whistleblowing.com/contact-enterprise/'
        ));
        $period_label = $is_yearly ? phoenix_text('upgrade.cycle_yearly') : phoenix_text('upgrade.cycle_monthly');
        $display_url  = $saas_url ?: '—';

        $details = '';
        if (!empty($org_data['org']))       $details .= '<div class="pup-idet-row"><span class="pup-idet-label">' . phoenix_text('upgrade.label_organisation') . '</span><span class="pup-idet-val">' . esc_html($org_data['org']) . '</span></div>';
        if (!empty($org_data['server']))    $details .= '<div class="pup-idet-row"><span class="pup-idet-label">' . phoenix_text('upgrade.label_server') . '</span><span class="pup-idet-val">' . esc_html(ucfirst($org_data['server'])) . '</span></div>';
        if (!empty($org_data['subdomain'])) $details .= '<div class="pup-idet-row"><span class="pup-idet-label">' . phoenix_text('upgrade.label_subdomain') . '</span><span class="pup-idet-val">' . esc_html($org_data['subdomain']) . '</span></div>';
        if (!empty($org_data['domain']))    $details .= '<div class="pup-idet-row"><span class="pup-idet-label">' . phoenix_text('upgrade.label_domain') . '</span><span class="pup-idet-val">' . esc_html($org_data['domain']) . '</span></div>';

        ob_start();
        ?>
        <style>
        #pup{font-family:inherit;max-width:860px;margin:0 auto}#pup *{box-sizing:border-box}
        #pup a.pup-back{display:inline-flex;align-items:center;gap:5px;color:#666;text-decoration:none;font-size:13px;margin-bottom:18px}
        #pup a.pup-back:hover{color:#e53935}
        #pup .pup-ibar{display:flex;align-items:center;gap:12px;background:#f8f9fa;border:1px solid #e0e0e0;border-radius:10px;padding:13px 16px;margin-bottom:20px;flex-wrap:wrap}
        #pup .pup-iicon{width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
        #pup .pup-iname{font-size:14px;font-weight:700;color:#2c3e50;margin:0 0 2px}
        #pup .pup-iurl{font-size:12px;color:#999;margin:0}#pup .pup-iurl a{color:#1565c0;text-decoration:none}
        #pup .pup-ibadge{margin-left:auto;font-size:11px;font-weight:700;padding:4px 11px;border-radius:20px;flex-shrink:0;white-space:nowrap}
        #pup .pup-premium-box{background:#e8f5e9;border:1px solid #c8e6c9;border-radius:10px;padding:18px;margin-bottom:18px}
        #pup .pup-premium-title{font-size:13px;font-weight:700;color:#2e7d32;margin-bottom:4px}
        #pup .pup-premium-sub{font-size:13px;color:#555}
        #pup .pup-idet{background:#fff;border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:18px}
        #pup .pup-idet-title{font-size:11px;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:.6px;padding:12px 16px;background:#f8f9fa;border-bottom:1px solid #e0e0e0}
        #pup .pup-idet-row{display:flex;align-items:center;padding:10px 16px;border-bottom:1px solid #f5f5f5;font-size:13px}
        #pup .pup-idet-row:last-child{border-bottom:none}
        #pup .pup-idet-label{color:#999;min-width:140px;flex-shrink:0}
        #pup .pup-idet-val{color:#2c3e50;font-weight:600}
        #pup .pup-ent-section{background:#faf5ff;border:1px solid #e1bee7;border-radius:10px;padding:18px;display:flex;align-items:center;gap:16px;flex-wrap:wrap}
        #pup .pup-ent-left{flex:1;min-width:200px}
        #pup .pup-ent-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#7b1fa2;margin-bottom:4px}
        #pup .pup-ent-desc{font-size:13px;color:#555}
        #pup .pup-ent-cta{display:inline-block;padding:10px 20px;background:linear-gradient(135deg,#6a1b9a,#ab47bc);color:#fff!important;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap}
        </style>
        <div id="pup">
            <h1 style="margin-bottom:5px;"><?php echo phoenix_text('upgrade.page_title'); ?></h1>
            <a href="<?php echo $my_saas_url; ?>" class="pup-back"><?php echo phoenix_text('upgrade.btn_back_workspaces'); ?></a>
            <div class="pup-ibar">
                <div class="pup-iicon" style="background:#fff8e1">⭐</div>
                <div style="flex:1;min-width:0">
                    <div class="pup-iname"><?php echo esc_html($org_name); ?></div>
                    <div class="pup-iurl"><a href="<?php echo $saas_url_raw; ?>" target="_blank"><?php echo esc_html($display_url); ?> ↗</a></div>
                </div>
                <span class="pup-ibadge" style="background:#fce4ec;color:#c62828"><?php echo phoenix_text('upgrade.badge_premium') . ' · ' . $period_label; ?></span>
            </div>

            <div class="pup-premium-box">
                <div class="pup-premium-title"><?php echo phoenix_text('upgrade.already_premium_title'); ?></div>
                <div class="pup-premium-sub"><?php echo phoenix_text('upgrade.already_premium_sub'); ?></div>
            </div>

            <?php if ($details): ?>
            <div class="pup-idet">
                <div class="pup-idet-title"><?php echo phoenix_text('upgrade.instance_details'); ?></div>
                <?php echo $details; ?>
            </div>
            <?php endif; ?>

            <div class="pup-ent-section">
                <div class="pup-ent-left">
                    <div class="pup-ent-label"><?php echo phoenix_text('upgrade.ent_label'); ?></div>
                    <div class="pup-ent-desc"><?php echo phoenix_text('upgrade.ent_desc_premium'); ?></div>
                </div>
                <a href="<?php echo $contact_url; ?>" target="_blank" class="pup-ent-cta"><?php echo phoenix_text('upgrade.btn_contact'); ?></a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    $enterprise_url = add_query_arg(
        ['sub_id' => $sub_id, 'org' => urlencode($org_name), 'source' => 'upgrade-page'],
        $is_staging_env
            ? 'https://staging.phoenix-whistleblowing.com/contact-enterprise/?swcfpc=1'
            : 'https://phoenix-whistleblowing.com/contact-enterprise/'
    );

    $p_basic_mo      = do_shortcode('[product_price id="61"]');
    $p_basic_yr      = do_shortcode('[product_price id="62"]');
    $p_premium_mo    = do_shortcode('[product_price id="78"]');
    $p_premium_yr    = do_shortcode('[product_price id="79"]');
    $p_basic_mo_eq   = do_shortcode('[yearlt2monthly id="62"]');
    $p_premium_mo_eq = do_shortcode('[yearlt2monthly id="79"]');
    $basic_save      = do_shortcode('[discount id_month="61" id_year="62"]');
    $premium_save    = do_shortcode('[discount id_month="78" id_year="79"]');
    $basic_save_num  = (int) str_replace('%', '', $basic_save);
    $premium_save_num= (int) str_replace('%', '', $premium_save);

    $available_plans = [];
    if ($current_level < 2) {
        $available_plans['basic'] = [
            'type'    => 'wc', 'label' => phoenix_text('upgrade.plan_label_basic'),
            'monthly' => ['price'=>$p_basic_mo,    'vid'=>61, 'form'=>58],
            'yearly'  => ['price'=>$p_basic_mo_eq, 'vid'=>62, 'form'=>58,
                          'save'=>$basic_save, 'save_num'=>$basic_save_num, 'total'=>$p_basic_yr],
        ];
    }
    if ($current_level < 3) {
        $available_plans['premium'] = [
            'type'    => 'wc', 'label' => phoenix_text('upgrade.plan_label_premium'),
            'monthly' => ['price'=>$p_premium_mo,    'vid'=>78, 'form'=>61],
            'yearly'  => ['price'=>$p_premium_mo_eq, 'vid'=>79, 'form'=>61,
                          'save'=>$premium_save, 'save_num'=>$premium_save_num, 'total'=>$p_premium_yr],
        ];
    }
    $available_plans['enterprise'] = [
        'type' => 'enterprise', 'url' => $enterprise_url,
        'features' => ['Customizable Reporting Channels','Advanced User Accounts','Many Language Options',
                       'Fully Customizable Themes','High-End Server & Security',
                       'Premium Assistance & Support','Flexible Payment Terms'],
    ];

    $gains_basic_from_free    = phoenix_text_list('upgrade.gains_basic_from_free');
    $gains_premium_from_basic = phoenix_text_list('upgrade.gains_premium_from_basic');
    $gains_premium_from_free  = phoenix_text_list('upgrade.gains_premium_from_free');

    $wc_config  = file_exists(WP_PLUGIN_DIR.'/wbs-saas-wp/config/wc.php')
        ? include WP_PLUGIN_DIR.'/wbs-saas-wp/config/wc.php' : [];
    $addon_ids  = $wc_config['addon'] ?? [];

    $upgrade_content = [
        'channels' => [
            'phone_display' => ['title'=>'Phone Numbers (Display)','price'=>'$2.10','description'=>'List phone numbers among the available channels...','note'=>'Display only.'],
            'email'         => ['title'=>'Email Inbox','price'=>'$10.50','description'=>'Integrate a secure email address...'],
            'im_display'    => ['title'=>'Instant Messaging (Display)','price'=>'$2.10','description'=>'Display IM accounts...','note'=>'No integration. Display only.'],
        ],
        'core'  => ['website' => ['title'=>'Dedicated website + Questionnaire','price'=>'$23.52','description'=>'A dedicated platform...']],
        'users' => ['manager' => ['title'=>'Account as Manager','price'=>'$10.50','description'=>'Managers oversee the entire system...']],
    ];

    $active_addon_ids = [];
    $instance_np = $subscription->get_date('next_payment');
    if ($instance_np && function_exists('wcs_get_users_subscriptions')) {
        $np_key = date('Y-m-d', strtotime($instance_np));
        $canon  = $tenant->tenant_uuid ?? '';
        foreach (wcs_get_users_subscriptions($user_id) as $asub) {
            if (!$asub->has_status('active')) continue;
            $is_addon=false;
            foreach ($asub->get_items() as $ai) { if (has_term('add-on','product_cat',$ai->get_product_id())) { $is_addon=true; break; } }
            if (!$is_addon) continue;
            $matched=false;
            if (!$matched&&$canon&&function_exists('GFAPI')) {
                $oid=$asub->get_parent_id();
                $ents=$oid?GFAPI::get_entries(64,['field_filters'=>[['key'=>'woocommerce_order_number','value'=>$oid]]]):[];
                if (!empty($ents)&&$canon===($ents[0]['1']??'')) $matched=true;
            }
            if (!$matched&&$canon) {
                $oid=$asub->get_parent_id(); $ord=$oid?wc_get_order($oid):null;
                if ($ord) { foreach ($ord->get_items() as $oi) { foreach ($oi->get_meta_data() as $om) { if ((string)$om->value===$canon){$matched=true;break 2;} } } }
            }
            if (!$matched) continue;
            foreach ($asub->get_items() as $ai) { if (has_term('add-on','product_cat',$ai->get_product_id())) $active_addon_ids[]=$ai->get_product_id(); }
        }
    }
    $active_addon_ids = array_unique($active_addon_ids);

    $ch_labels = [
        'phone'     => phoenix_text('upgrade.addon_phone'),
        'email'     => phoenix_text('upgrade.addon_email'),
        'im'        => phoenix_text('upgrade.addon_im'),
        'postmail'  => phoenix_text('upgrade.addon_postmail'),
        'chat'      => phoenix_text('upgrade.addon_chat'),
        'mobileapp' => phoenix_text('upgrade.addon_mobileapp'),
    ];

    $addon_diff = [];
    foreach ($ch_labels as $k=>$l) {
        $pid=(int)($addon_ids[$k]??0); if (!$pid) continue;
        $addon_diff[]=['pid'=>$pid,'label'=>$l,'owned'=>in_array($pid,$active_addon_ids)];
    }
    $ua=$addon_ids['users']??0; if ($ua) $addon_diff[]=['pid'=>(int)$ua,'label'=>phoenix_text('upgrade.addon_users'),'owned'=>in_array((int)$ua,$active_addon_ids)];
    $la=$addon_ids['languages']??0; if ($la) $addon_diff[]=['pid'=>(int)$la,'label'=>phoenix_text('upgrade.addon_languages'),'owned'=>in_array((int)$la,$active_addon_ids)];

    $plan_diffs = [];
    if ($current_level < 2) {
        $plan_diffs['basic']   = $gains_basic_from_free;
        $plan_diffs['premium'] = $gains_premium_from_free;
    } else {
        $plan_diffs['premium'] = $gains_premium_from_basic;
    }

    $wc_plans = array_filter($available_plans, function($p, $key) use ($current_level) {
        if ($p['type'] !== 'wc') return false;
        if ($current_level < 2) return true;
        return $key === 'premium';
    }, ARRAY_FILTER_USE_BOTH);

    $period_str = $is_yearly ? 'yearly' : 'monthly';
    $ajax_nonce = wp_create_nonce('phoenix_upgrade_gf');

    ob_start();
    ?>
    <style>
    #pup{font-family:inherit;max-width:860px;margin:0 auto}#pup *{box-sizing:border-box}
    #pup a.pup-back{display:inline-flex;align-items:center;gap:5px;color:#666;text-decoration:none;font-size:13px;margin-bottom:18px}
    #pup a.pup-back:hover{color:#e53935}
    #pup .pup-ibar{display:flex;align-items:center;gap:12px;background:#f8f9fa;border:1px solid #e0e0e0;border-radius:10px;padding:13px 16px;margin-bottom:24px;flex-wrap:wrap}
    #pup .pup-iicon{width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
    #pup .pup-iname{font-size:14px;font-weight:700;color:#2c3e50;margin:0 0 2px}
    #pup .pup-iurl{font-size:12px;color:#999;margin:0}#pup .pup-iurl a{color:#1565c0;text-decoration:none}
    #pup .pup-ibadge{margin-left:auto;font-size:11px;font-weight:700;padding:4px 11px;border-radius:20px;flex-shrink:0;white-space:nowrap}
    #pup .pup-btoggle{display:flex;align-items:center;gap:12px;margin-bottom:24px;flex-wrap:wrap}
    #pup .pup-btoggle-label{font-size:13px;color:#666;font-weight:600}
    #pup .pup-btns{display:flex;background:#f0f0f0;border-radius:8px;padding:3px;gap:3px}
    #pup .pup-btn{padding:7px 16px;border-radius:6px;border:none;background:transparent;cursor:pointer;font-size:13px;font-weight:600;color:#666;transition:all .2s}
    #pup .pup-btn.active{background:#fff;color:#e53935;box-shadow:0 1px 4px rgba(0,0,0,.1)}
    #pup .pup-grid{display:grid;gap:14px;margin-bottom:28px}
    #pup .pup-grid.g1{grid-template-columns:1fr}#pup .pup-grid.g2{grid-template-columns:repeat(2,1fr)}
    @media(max-width:620px){#pup .pup-grid.g2{grid-template-columns:1fr}}
    #pup .pup-card{background:#fff;border:1px solid #e0e0e0;border-radius:12px;padding:24px;display:flex;flex-direction:column}
    #pup .pup-card.hot{border:2px solid #e53935;background:#fff8f8}
    #pup .pup-badge-hot{font-size:10px;font-weight:700;color:#e53935;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;min-height:16px}
    #pup .pup-badge-spc{min-height:22px}
    #pup .pup-plan-label{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;padding:5px 12px;border-radius:6px;display:inline-block;background:#f5f5f5;color:#2c3e50;margin-bottom:10px}
    #pup .pup-price{font-size:38px;font-weight:900;color:#1a1a2e;line-height:1;margin-bottom:4px}
    #pup .pup-price span{font-size:15px;color:#e53935;font-weight:700;min-height:18px;margin-bottom:14px}
    #pup .pup-price-alt{font-size:16px;color:#555;min-height:18px;margin-bottom:2px}
    #pup .pup-price-save{font-size:14px;color:#e53935;font-weight:700;min-height:18px;margin-bottom:14px}
    #pup .pup-divider{height:1px;background:#f0f0f0;margin:14px 0}
    #pup .pup-gains-title{font-size:11px;font-weight:700;color:#27ae60;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px}
    #pup .pup-gains{list-style:none;padding:0;margin:0 0 18px;flex:1}
    #pup .pup-gains li{padding:4px 0;font-size:13px;color:#444;display:flex;align-items:flex-start;gap:6px;border-bottom:1px solid #f5f5f5}
    #pup .pup-gains li:last-child{border:none}#pup .pup-gains li::before{content:'+';color:#27ae60;font-weight:700;flex-shrink:0}
    #pup .pup-cta{display:block;text-align:center;padding:12px;border-radius:8px;font-size:13px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;margin-top:auto;border:none;cursor:pointer;transition:opacity .2s;width:100%;color:#fff}
    #pup .pup-cta:hover{opacity:.85}
    #pup .pup-cta-red{background:linear-gradient(135deg,#e53935,#ff7043)}
    #pup .pup-cta-loading{background:#aaa!important;cursor:not-allowed!important;pointer-events:none}
    #pup .pup-hidden{display:none!important}
    #pup .pup-addon-wrap{background:#f8f9fa;border:1px solid #e0e0e0;border-radius:10px;padding:18px;margin-bottom:24px}
    #pup .pup-addon-title{font-size:11px;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px}
    #pup .pup-addon-sub{font-size:12px;color:#888;margin-bottom:12px}#pup .pup-addon-sub a{color:#1565c0;text-decoration:none}
    #pup .pup-addon-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:8px}
    #pup .pup-addon-item{background:#fff;border-radius:8px;padding:9px 12px;border:1px solid #e0e0e0}
    #pup .pup-addon-item.owned{border-color:#c8e6c9;opacity:.65}
    #pup .pup-addon-name{font-size:12px;font-weight:600;color:#2c3e50;margin-bottom:2px}
    #pup .pup-addon-status{font-size:10px;font-weight:700}
    #pup .pup-ent-section{background:#faf5ff;border:1px solid #e1bee7;border-radius:10px;padding:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap}
    #pup .pup-ent-left{flex:1;min-width:200px}
    #pup .pup-ent-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#7b1fa2;margin-bottom:4px}
    #pup .pup-ent-desc{font-size:13px;color:#555}
    #pup .pup-ent-cta{display:inline-block;padding:10px 20px;background:linear-gradient(135deg,#6a1b9a,#ab47bc);color:#fff!important;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;transition:opacity .2s}
    #pup .pup-ent-cta:hover{opacity:.85}
    #pup .pup-notice{padding:14px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;display:none}
    #pup .pup-notice.show{display:block}
    #pup .pup-notice-error{background:#ffebee;border-left:4px solid #e53935;color:#c62828}
    #pup .pup-notice-info{background:#e3f2fd;border-left:4px solid #1565c0;color:#1565c0}
    </style>

    <div id="pup">
        <h2 style="margin-bottom:5px;"><?php echo phoenix_text('upgrade.page_title'); ?></h2>
        <a href="<?php echo esc_url(phoenix_wpml_url(home_url('/my-account/workspaces/'))); ?>" class="pup-back"><?php echo phoenix_text('upgrade.btn_back_workspaces'); ?></a>
        <div id="pup-notice" class="pup-notice"></div>

        <div class="pup-ibar">
            <div class="pup-iicon" style="background:<?php echo $current_level>=2?'#fff8e1':'#e3f2fd';?>"><?php echo $current_level>=2?'📦':'🌱';?></div>
            <div style="flex:1;min-width:0">
                <div class="pup-iname"><?php echo esc_html($org_name);?></div>
                <div class="pup-iurl"><a href="<?php echo $saas_url_raw;?>" target="_blank"><?php echo esc_html($saas_url);?> ↗</a></div>
            </div>
            <span class="pup-ibadge" style="background:<?php echo $current_level>=2?'#fff8e1':'#e3f2fd';?>;color:<?php echo $current_level>=2?'#e65100':'#1565c0';?>">
                <?php echo $current_level>=2?phoenix_text('upgrade.badge_basic'):phoenix_text('upgrade.badge_free');?> · <?php echo $is_yearly?phoenix_text('upgrade.billing_yearly'):phoenix_text('upgrade.billing_monthly');?>
            </span>
        </div>

        <?php if (!empty($wc_plans)): ?>

        <?php
        // ── Prorate extend preview ────────────────────────────────────────────
        // Tampilkan info kepada user: sisa hari old plan akan di-extend ke new plan.
        // credit_days = floor((old_next_payment - now) / DAY)
        $prorate_credit_days = 0;
        $prorate_preview_html = '';
        if ($instance_np && $current_level >= 2) {
            $now_ts              = current_time('timestamp');
            $old_np_ts           = strtotime($instance_np);
            $prorate_credit_days = max(0, (int) floor(($old_np_ts - $now_ts) / DAY_IN_SECONDS));

            if ($prorate_credit_days > 0) {
                // Preview untuk monthly upgrade
                $mo_period_days = (int) round((strtotime('+1 month', $old_np_ts) - $old_np_ts) / DAY_IN_SECONDS);
                $mo_extended_ts = $old_np_ts + (($mo_period_days + $prorate_credit_days) * DAY_IN_SECONDS);
                // Preview untuk yearly upgrade
                $yr_extended_ts = $old_np_ts + ((365 + $prorate_credit_days) * DAY_IN_SECONDS);

                $prorate_preview_html = sprintf(
                    '<div id="pup-prorate-notice" style="background:#e8f5e9;border:1px solid #a5d6a7;border-left:4px solid #27ae60;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:13px;">
                        <div style="font-weight:700;color:#1b5e20;margin-bottom:4px;">%s</div>
                        <div style="color:#2e7d32;line-height:1.6;">%s</div>
                        <div id="pup-prorate-mo" style="margin-top:8px;color:#555;font-size:12px;">%s</div>
                        <div id="pup-prorate-yr" style="margin-top:4px;color:#555;font-size:12px;display:none;">%s</div>
                    </div>',
                    phoenix_text('upgrade.prorate_title'),
                    sprintf(phoenix_text('upgrade.prorate_days_remaining'), $prorate_credit_days, date('d M Y', $old_np_ts)),
                    sprintf(phoenix_text('upgrade.prorate_mo_renewal'), date('d M Y', $mo_extended_ts), $prorate_credit_days),
                    sprintf(phoenix_text('upgrade.prorate_yr_renewal'), date('d M Y', $yr_extended_ts), $prorate_credit_days)
                );
            }
        }
        echo $prorate_preview_html;
        ?>

        <div class="pup-btoggle">
            <span class="pup-btoggle-label"><?php echo phoenix_text('upgrade.cycle_label'); ?></span>
            <div class="pup-btns">
                <?php if (!$is_yearly): ?>
                    <button class="pup-btn active" id="btn-mo" onclick="pupBilling('monthly')"><?php echo phoenix_text('upgrade.billing_monthly'); ?></button>
                <?php endif; ?>
                <button class="pup-btn <?php echo $is_yearly ? 'active' : ''; ?>" id="btn-yr" onclick="pupBilling('yearly')"><?php echo phoenix_text('upgrade.billing_yearly'); ?></button>
            </div>
        </div>

        <div class="pup-grid <?php echo count($wc_plans)===1?'g1':'g2';?>">
        <?php foreach ($wc_plans as $plan_key => $plan):
            $is_hot = $plan_key==='premium';
            $gains  = $plan_diffs[$plan_key] ?? [];
        ?>
            <div class="pup-card <?php echo $is_hot?'hot':'';?>">
                <?php if ($is_hot): ?><div class="pup-badge-hot"><?php echo phoenix_text('upgrade.popular_badge'); ?></div>
                <?php else: ?><div class="pup-badge-spc"></div><?php endif; ?>
                <div class="pup-plan-label"><?php echo esc_html($plan['label']);?></div>

                <div id="pr-<?php echo $plan_key;?>-mo">
                    <div class="pup-price-alt"><?php echo $plan['monthly']['price'];?><span><?php echo phoenix_text('upgrade.price_per_month'); ?></span></div>
                    <div class="pup-price-save">&nbsp;</div><div class="pup-price-save">&nbsp;</div>
                </div>
                <div id="pr-<?php echo $plan_key;?>-yr" class="pup-hidden">
                    <div class="pup-price-alt"><?php echo $plan['yearly']['total'] . phoenix_text('upgrade.price_per_year_note'); ?></div>
                    <div class="pup-price-save"><?php if(!empty($plan['yearly']['save_num'])&&$plan['yearly']['save_num']>0): echo sprintf(phoenix_text('upgrade.price_save_fmt'), $plan['yearly']['save'], $plan['yearly']['price']); else: ?>&nbsp;<?php endif;?></div>
                </div>

                <?php if (!empty($gains)):?>
                <div class="pup-divider"></div>
                <div class="pup-gains-title"><?php echo sprintf(phoenix_text('upgrade.gains_title'), esc_html($plan['label'])); ?></div>
                <ul class="pup-gains"><?php foreach ($gains as $g): ?><li><?php echo esc_html($g);?></li><?php endforeach;?></ul>
                <?php endif;?>

                <button class="pup-cta pup-cta-red" id="cta-<?php echo $plan_key;?>-mo"
                    data-vid="<?php echo $plan['monthly']['vid'];?>"
                    data-form="<?php echo $plan['monthly']['form'];?>"
                    data-period="Monthly" onclick="pupPurchase(this)"><?php echo phoenix_text('upgrade.btn_upgrade'); ?></button>
                <button class="pup-cta pup-cta-red pup-hidden" id="cta-<?php echo $plan_key;?>-yr"
                    data-vid="<?php echo $plan['yearly']['vid'];?>"
                    data-form="<?php echo $plan['yearly']['form'];?>"
                    data-period="Yearly" onclick="pupPurchase(this)"><?php echo phoenix_text('upgrade.btn_upgrade'); ?></button>
            </div>
        <?php endforeach; ?>
        </div>
        <?php endif;?>

        <?php if (!empty($addon_diff)):?>
        <div style="margin-bottom:30px;padding:5px;">
            <div class="pup-addon-title"><?php echo phoenix_text('upgrade.addons_section_title'); ?></div>
            <?php if ($current_level >= 2):
                $owned_count = count(array_filter($addon_diff, fn($a) => $a['owned']));
            ?>
            <div>
                <div style="font-size:13px;color:#2e7d32;line-height:1.6">
                    <?php if ($owned_count > 0): ?>
                        <strong><?php echo sprintf(phoenix_text('upgrade.addons_basic_owned'), $owned_count, $owned_count > 1 ? 's' : ''); ?></strong><br>
                        <span style="color:#555;"><?php echo phoenix_text('upgrade.addons_basic_owned_note'); ?></span>
                    <?php else: ?>
                        <strong><?php echo phoenix_text('upgrade.addons_basic_none'); ?></strong><br>
                        <span style="color:#555;"><?php echo phoenix_text('upgrade.addons_basic_none_note'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="pup-addon-sub"><?php echo phoenix_text('upgrade.addons_free_subtitle'); ?></div>
            <div class="pup-addon-grid">
                <?php foreach ($addon_diff as $ad):?>
                <div class="pup-addon-item">
                    <div class="pup-addon-name"><?php echo esc_html($ad['label']);?></div>
                    <div class="pup-addon-status" style="color:#bbb;font-size:10px;"><?php
                        if (!empty($ad['for_premium']) && empty($ad['for_basic'])) echo phoenix_text('upgrade.addon_premium_only');
                        else echo phoenix_text('upgrade.addon_available_after');
                    ?></div>
                    <?php if (!empty($ad['for_basic']) || !empty($ad['for_premium'])): ?>
                    <div class="pup-addon-badges" style="margin-top:4px;display:flex;gap:3px;flex-wrap:wrap">
                        <?php if (!empty($ad['for_basic'])): ?><span style="font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px;background:#e3f2fd;color:#1565c0"><?php echo phoenix_text('upgrade.plan_label_basic'); ?></span><?php endif;?>
                        <?php if (!empty($ad['for_premium'])): ?><span style="font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px;background:#f3e5f5;color:#7b1fa2"><?php echo phoenix_text('upgrade.plan_label_premium'); ?></span><?php endif;?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif;?>

        <div class="pup-ent-section">
            <div class="pup-ent-left">
                <div class="pup-ent-label"><?php echo phoenix_text('upgrade.ent_label'); ?></div>
                <div class="pup-ent-desc"><?php echo phoenix_text('upgrade.ent_desc_from_basic'); ?></div>
            </div>
            <a href="<?php echo esc_url($enterprise_url);?>" target="_blank" class="pup-ent-cta"><?php echo phoenix_text('upgrade.btn_contact'); ?></a>
        </div>
    </div>

    <script>
    var pupB     = '<?php echo $is_yearly ? "yearly" : "monthly"; ?>';
    var pupSubId = <?php echo (int)$sub_id;?>;
    var pupNonce = '<?php echo esc_js($ajax_nonce);?>';
    var pupAjax  = '<?php echo esc_js(admin_url('admin-ajax.php'));?>';

    document.addEventListener('DOMContentLoaded', function(){ pupBilling(pupB); });

    function pupBilling(type) {
        pupB = type;
        var btnMo = document.getElementById('btn-mo');
        if (btnMo) btnMo.classList.toggle('active', type==='monthly');
        document.getElementById('btn-yr').classList.toggle('active', type==='yearly');
        var plans = <?php echo json_encode(array_keys($wc_plans));?>;
        plans.forEach(function(p){
            var m=type==='monthly';
            ['pr-'+p+'-mo','cta-'+p+'-mo'].forEach(function(id){ var el=document.getElementById(id); if(el) el.classList.toggle('pup-hidden',!m); });
            ['pr-'+p+'-yr','cta-'+p+'-yr'].forEach(function(id){ var el=document.getElementById(id); if(el) el.classList.toggle('pup-hidden',m); });
        });
        // Toggle prorate notice rows
        var pMo = document.getElementById('pup-prorate-mo');
        var pYr = document.getElementById('pup-prorate-yr');
        if (pMo) pMo.style.display = type === 'monthly' ? 'block' : 'none';
        if (pYr) pYr.style.display = type === 'yearly'  ? 'block' : 'none';
    }

    function pupShowNotice(msg, type) {
        var n=document.getElementById('pup-notice');
        n.textContent=msg; n.className='pup-notice show pup-notice-'+(type||'info');
        n.scrollIntoView({behavior:'smooth',block:'nearest'});
    }

    function pupPurchase(btn) {
        if (btn.classList.contains('pup-cta-loading')) return;
        var vid    = parseInt(btn.getAttribute('data-vid'), 10);
        var formId = parseInt(btn.getAttribute('data-form'), 10);
        var period = btn.getAttribute('data-period');

        btn.classList.add('pup-cta-loading');
        btn.textContent = '<?php echo esc_js(phoenix_text("upgrade.btn_processing")); ?>';
        pupShowNotice('<?php echo esc_js(phoenix_text("upgrade.notice_preparing")); ?>', 'info');

        var body = new URLSearchParams({
            action:       'phoenix_submit_upgrade_gf',
            nonce:        pupNonce,
            sub_id:       pupSubId,
            form_id:      formId,
            variation_id: vid,
            period:       period,
        });

        fetch(pupAjax, {
            method:      'POST',
            headers:     {'Content-Type':'application/x-www-form-urlencoded'},
            body:        body.toString(),
            credentials: 'same-origin',
        })
        .then(function(r){ return r.json(); })
        .then(function(res) {
            console.log('[Upgrade v4] response:', res);
            if (res.success) {
                if (res.data.fallback) {
                    pupShowNotice('<?php echo esc_js(phoenix_text("upgrade.notice_redirecting")); ?>', 'info');
                } else {
                    pupShowNotice('<?php echo esc_js(phoenix_text("upgrade.notice_ready")); ?>', 'info');
                }
                setTimeout(function(){ window.location.href = res.data.redirect_url; }, 500);
            } else {
                btn.classList.remove('pup-cta-loading');
                btn.textContent = '<?php echo esc_js(phoenix_text("upgrade.btn_upgrade")); ?>';
                pupShowNotice('Error: ' + (res.data ? res.data.msg : 'Unknown error'), 'error');
                console.error('[Upgrade v4] error:', res.data);
            }
        })
        .catch(function(err) {
            btn.classList.remove('pup-cta-loading');
            btn.textContent = '<?php echo esc_js(phoenix_text("upgrade.btn_upgrade")); ?>';
            pupShowNotice('<?php echo esc_js(phoenix_text("upgrade.notice_network")); ?>', 'error');
            console.error('[Upgrade v4] fetch error:', err);
        });
    }
    </script>
    <?php
    return ob_get_clean();
}