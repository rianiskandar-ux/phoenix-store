/**
 * SNIPPET: Theme Owned Gate
 * (dengan debug output — admin only)
 */

add_action('wp_head', 'phoenix_theme_gate_css');
function phoenix_theme_gate_css() {
    if (!is_product_category('theme')) return;
    if (!sanitize_text_field($_GET['tenant_uuid'] ?? '')) return;
    ?>
    <style>
    .theme-card-disabled { position:relative; pointer-events:none; opacity:0.72; }
    .theme-card-disabled a, .theme-card-disabled button, .theme-card-disabled .button { pointer-events:none !important; cursor:default !important; }
    .theme-card-disabled img { filter:grayscale(15%); }
    .theme-owned-overlay { position:absolute; inset:0; z-index:10; cursor:default; pointer-events:all; }
    .theme-owned-badge { position:absolute; top:10px; left:10px; background:rgba(46,125,50,0.92); color:#fff; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; z-index:11; }
    </style>
    <?php
}

add_filter('woocommerce_loop_add_to_cart_link', 'phoenix_theme_owned_gate', 10, 2);
function phoenix_theme_owned_gate($html, $product) {
    if (!is_product_category('theme')) return $html;
    $tenant_uuid = sanitize_text_field($_GET['tenant_uuid'] ?? '');
    if (!$tenant_uuid) return $html;

    $plan_level  = phoenix_theme_gate_get_plan_level($tenant_uuid);
    $is_disabled = false;

    if ($plan_level >= 3) {
        $is_disabled = true;
    } elseif ($plan_level == 2) {
        $owned_ids   = phoenix_theme_get_owned_product_ids($tenant_uuid);
        $is_disabled = in_array($product->get_id(), $owned_ids, true);
    }

    // Debug — admin only
    if (current_user_can('manage_options')) {
        $owned_ids_debug = ($plan_level == 2) ? phoenix_theme_get_owned_product_ids($tenant_uuid) : ['premium-all'];
        error_log('[ThemeGate] product=' . $product->get_id() . ' sku=' . $product->get_sku() . ' plan=' . $plan_level . ' disabled=' . ($is_disabled?'YES':'NO') . ' owned_ids=' . implode(',', $owned_ids_debug));
    }

    if (!$is_disabled) return $html;

    $pid = $product->get_id();
    $btn = '<a class="button alt disabled" style="background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;cursor:default;pointer-events:none;width:100%;text-align:center;">✓ Active</a>';
    $btn .= '<script>
    document.addEventListener("DOMContentLoaded", function() {
        var card = document.querySelector("li.post-' . $pid . '");
        if (!card) return;
        card.classList.add("theme-card-disabled");
        var overlay = document.createElement("div"); overlay.className = "theme-owned-overlay"; card.appendChild(overlay);
        var badge = document.createElement("div"); badge.className = "theme-owned-badge"; badge.textContent = "✓ Active"; card.appendChild(badge);
    });
    </script>';
    return $btn;
}

// Debug bar — tampil di atas loop, admin only
add_action('woocommerce_before_shop_loop', 'phoenix_theme_gate_inline_debug');
function phoenix_theme_gate_inline_debug() {
    if (!is_product_category('theme')) return;
    if (!current_user_can('manage_options')) return;
    $tenant_uuid = sanitize_text_field($_GET['tenant_uuid'] ?? '');
    if (!$tenant_uuid) return;

    $plan_level = phoenix_theme_gate_get_plan_level($tenant_uuid);
    $owned_ids  = ($plan_level == 2) ? phoenix_theme_get_owned_product_ids($tenant_uuid) : [];

    echo '<div style="background:#e3f2fd;border:2px solid #1565c0;padding:14px;border-radius:8px;margin-bottom:20px;font-size:12px;font-family:monospace;">';
    echo '<strong>🐛 Theme Gate Inline Debug</strong><br><br>';
    echo 'tenant_uuid: ' . esc_html($tenant_uuid) . '<br>';
    echo 'plan_level: <strong>' . $plan_level . '</strong> (' . ($plan_level >= 3 ? 'Premium → semua disabled' : ($plan_level == 2 ? 'Basic → cek orders' : 'Free/unknown')) . ')<br>';
    if ($plan_level == 2) {
        echo 'owned_product_ids: <strong>' . (empty($owned_ids) ? '(kosong!)' : implode(', ', $owned_ids)) . '</strong> (' . count($owned_ids) . ' themes)<br>';
    }
    echo '</div>';
}

add_action('wp', 'phoenix_theme_single_product_gate');
function phoenix_theme_single_product_gate() {
    if (!is_product()) return;
    $tenant_uuid = sanitize_text_field($_GET['tenant_uuid'] ?? '');
    if (!$tenant_uuid) return;

    global $post;
    if (!has_term('theme', 'product_cat', $post->ID)) return;

    $plan_level  = phoenix_theme_gate_get_plan_level($tenant_uuid);
    $is_disabled = false;

    if ($plan_level >= 3) {
        $is_disabled = true;
    } elseif ($plan_level == 2) {
        $owned_ids   = phoenix_theme_get_owned_product_ids($tenant_uuid);
        $is_disabled = in_array($post->ID, $owned_ids, true);
    }

    if (!$is_disabled) return;

    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    add_action('woocommerce_single_product_summary', function() {
        echo '<div style="background:#e8f5e9;border:1px solid #a5d6a7;border-radius:8px;padding:14px 18px;display:flex;align-items:center;gap:10px;margin:16px 0;">
            <span style="font-size:20px;">✓</span>
            <div>
                <div style="font-weight:700;color:#2e7d32;font-size:14px;">Already Active</div>
                <div style="font-size:12px;color:#555;margin-top:2px;">This theme is already active on your instance.</div>
            </div>
        </div>';
    }, 30);
}

function phoenix_theme_gate_get_plan_level($tenant_uuid) {
    global $wpdb;
    static $cache = [];
    if (isset($cache[$tenant_uuid])) return $cache[$tenant_uuid];

    $table = $wpdb->prefix . 'wbssaas_tenants';

    // Ambil tenant_name dulu dari UUID ini
    $base = $wpdb->get_row($wpdb->prepare(
        "SELECT customer_id, tenant_name FROM $table WHERE tenant_uuid = %s LIMIT 1",
        $tenant_uuid
    ));
    if (!$base) return $cache[$tenant_uuid] = 0;

    // Ambil SEMUA rows instance ini (sama customer_id + tenant_name)
    $all_rows = $wpdb->get_results($wpdb->prepare(
        "SELECT subscription_wc_id FROM $table WHERE customer_id = %d AND tenant_name = %s",
        (int) $base->customer_id, $base->tenant_name
    ));

    // Ambil plan level tertinggi dari semua subscription aktif
    $highest = 0;
    foreach ($all_rows as $r) {
        if (!$r->subscription_wc_id) continue;
        $sub = function_exists('wcs_get_subscription') ? wcs_get_subscription((int)$r->subscription_wc_id) : null;
        if (!$sub || !$sub->has_status(['active', 'pending-cancel'])) continue;
        $level = function_exists('phoenix_get_subscription_plan_level')
            ? phoenix_get_subscription_plan_level($sub) : 0;
        if ($level > $highest) $highest = $level;
    }

    return $cache[$tenant_uuid] = $highest;
}

function phoenix_theme_get_owned_product_ids($tenant_uuid) {
    global $wpdb;

    // Static cache: berlaku selama 1 request (per page load)
    static $cache = [];
    if (isset($cache[$tenant_uuid])) return $cache[$tenant_uuid];

    // Transient cache: berlaku 5 menit lintas request
    // Di-invalidate saat user beli theme baru (lihat hook woocommerce_order_status_completed)
    $transient_key = 'phoenix_theme_owned_' . md5($tenant_uuid);
    $cached = get_transient($transient_key);
    if ($cached !== false) {
        return $cache[$tenant_uuid] = $cached;
    }

    $table = $wpdb->prefix . 'wbssaas_tenants';
    $row   = $wpdb->get_row($wpdb->prepare(
        "SELECT customer_id, tenant_name FROM $table WHERE tenant_uuid = %s LIMIT 1",
        $tenant_uuid
    ));
    if (!$row) return $cache[$tenant_uuid] = [];

    $customer_id = (int) $row->customer_id;
    $tenant_name = $row->tenant_name;

    // Ambil semua UUID untuk tenant ini (multi-instance dengan nama sama)
    $all_rows  = $wpdb->get_results($wpdb->prepare(
        "SELECT tenant_uuid, tenant_settings FROM $table WHERE customer_id = %d AND tenant_name = %s",
        $customer_id, $tenant_name
    ));
    $all_uuids = array_column($all_rows, 'tenant_uuid');

    $owned = [];

    // Source 1: tenant_settings['themes'] — SKU yang di-assign plugin (manual + order)
    // Digabung dengan query all_rows di atas — tidak perlu query terpisah lagi
    foreach ($all_rows as $_trow) {
        if (empty($_trow->tenant_settings)) continue;
        $_settings = @unserialize($_trow->tenant_settings);
        $_skus     = (is_array($_settings) && !empty($_settings['themes'])) ? $_settings['themes'] : [];
        foreach ($_skus as $_sku) {
            $_pid = wc_get_product_id_by_sku($_sku);
            if ($_pid) $owned[] = $_pid;
        }
    }

    // Source 2: WC orders (completed + processing) — theme yang dibeli via checkout
    // Satu call saja — tidak perlu dua kali
    $all_orders = wc_get_orders([
        'customer_id' => $customer_id,
        'limit'       => 200,
        'status'      => ['completed', 'processing'],
    ]);

    foreach ($all_orders as $_order) {
        // Skip kalau order tidak mengandung theme sama sekali
        $_has_theme = false;
        foreach ($_order->get_items() as $_item) {
            if (has_term('theme', 'product_cat', $_item->get_product_id())) {
                $_has_theme = true; break;
            }
        }
        if (!$_has_theme) continue;

        // Cek apakah order ini milik tenant ini
        $_uuid_match = false;

        // Check 1: item meta (paling cepat)
        foreach ($_order->get_items() as $_item) {
            foreach ($_item->get_meta_data() as $_meta) {
                $mv = (string) $_meta->value;
                if (in_array($mv, $all_uuids, true) || strtolower(trim($mv)) === strtolower(trim($tenant_name))) {
                    $_uuid_match = true; break 2;
                }
            }
        }

        // Check 2: GF entry meta via raw DB query (lebih ringan dari GFAPI::get_entries)
        if (!$_uuid_match) {
            $_gf_val = $wpdb->get_var($wpdb->prepare(
                "SELECT em.meta_value FROM {$wpdb->prefix}gf_entry_meta em
                 INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
                 WHERE e.id IN (
                     SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                     WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
                 )
                 AND em.meta_key = '1' AND e.form_id = 64 LIMIT 1",
                (string) $_order->get_id()
            ));
            if ($_gf_val && in_array((string) $_gf_val, $all_uuids, true)) $_uuid_match = true;
        }

        if (!$_uuid_match) continue;

        foreach ($_order->get_items() as $_item) {
            if (has_term('theme', 'product_cat', $_item->get_product_id())) {
                $owned[] = $_item->get_product_id();
            }
        }
    }

    $result = array_unique($owned);

    // Simpan ke transient 5 menit
    // Akan di-invalidate oleh hook theme_order_completed saat user beli theme baru
    set_transient($transient_key, $result, 5 * MINUTE_IN_SECONDS);

    return $cache[$tenant_uuid] = $result;
}

// Invalidate transient cache saat user beli theme baru
// Supaya halaman theme langsung reflect status terbaru
add_action('woocommerce_order_status_completed', 'phoenix_invalidate_theme_owned_cache', 12, 1);
function phoenix_invalidate_theme_owned_cache($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    $has_theme = false;
    foreach ($order->get_items() as $item) {
        if (has_term('theme', 'product_cat', $item->get_product_id())) {
            $has_theme = true; break;
        }
    }
    if (!$has_theme) return;

    // Hapus transient untuk customer ini
    $customer_id = $order->get_customer_id();
    if (!$customer_id) return;

    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    $uuids = $wpdb->get_col($wpdb->prepare(
        "SELECT tenant_uuid FROM $table WHERE customer_id = %d",
        $customer_id
    ));
    foreach ($uuids as $uuid) {
        delete_transient('phoenix_theme_owned_' . md5($uuid));
    }
}