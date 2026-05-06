
/**
 * SNIPPET: Pre-fill GF Form 64 (Addon & Theme Checkout) dengan tenant_uuid
 * v2: + auto-select dropdown instance + auto-set payment period (Monthly/Yearly)
 */

// ================================================================
// 1. Simpan tenant_uuid ke session kapanpun ada di URL
// ================================================================
add_action('wp_loaded', function() {
    if (!isset($_GET['tenant_uuid'])) return;
    if (!is_user_logged_in()) return;

    $tenant_uuid = sanitize_text_field($_GET['tenant_uuid']);
    if (empty($tenant_uuid)) return;

    global $wpdb;
    $table  = $wpdb->prefix . 'wbssaas_tenants';
    $tenant = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE tenant_uuid = %s AND customer_id = %d LIMIT 1",
        $tenant_uuid, get_current_user_id()
    ));
    if (!$tenant) return;

    if (WC()->session) WC()->session->set('addon_tenant_uuid', $tenant_uuid);
});

// ================================================================
// 2. Pre-fill GF Form 64 Field 1 dari URL parameter
// ================================================================
add_filter('gform_field_value', 'prefill_addon_gf_tenant_uuid', 10, 3);
function prefill_addon_gf_tenant_uuid($value, $field, $name) {
    if ((int) $field->formId !== 64) return $value;
    if ((int) $field->id !== 1) return $value;

    $tenant_uuid = isset($_GET['tenant_uuid']) ? sanitize_text_field($_GET['tenant_uuid']) : '';
    if (empty($tenant_uuid) || !is_user_logged_in()) return $value;

    global $wpdb;
    $table  = $wpdb->prefix . 'wbssaas_tenants';
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE tenant_uuid = %s AND customer_id = %d LIMIT 1",
        $tenant_uuid, get_current_user_id()
    ));

    return $exists ? $tenant_uuid : $value;
}

// ================================================================
// 3. Fallback: pre-fill dari session
// ================================================================
add_filter('gform_field_value', 'prefill_addon_gf_tenant_uuid_session', 9, 3);
function prefill_addon_gf_tenant_uuid_session($value, $field, $name) {
    if ((int) $field->formId !== 64) return $value;
    if ((int) $field->id !== 1) return $value;
    if (!empty($_GET['tenant_uuid'])) return $value;
    if (!WC()->session || !is_user_logged_in()) return $value;

    $uuid = WC()->session->get('addon_tenant_uuid');
    if (empty($uuid)) return $value;

    global $wpdb;
    $table  = $wpdb->prefix . 'wbssaas_tenants';
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE tenant_uuid = %s AND customer_id = %d LIMIT 1",
        $uuid, get_current_user_id()
    ));

    return $exists ? $uuid : $value;
}

// ================================================================
// 4. Clear session setelah submit
// ================================================================
add_action('gform_after_submission_64', function($entry, $form) {
    if (WC()->session) WC()->session->set('addon_tenant_uuid', null);
}, 10, 2);

// ================================================================
// 5. Auto-select dropdown instance + auto-set payment period via JS
// ================================================================
// ================================================================
// 5a. Hide Field 1 via CSS di wp_head — prevent flash sebelum JS jalan
// ================================================================
add_action('wp_head', 'phoenix_gf64_hide_field_css');
function phoenix_gf64_hide_field_css() {
    if (!is_product()) return;
    if (!is_user_logged_in()) return;

    $tenant_uuid = sanitize_text_field($_GET['tenant_uuid'] ?? '');
    if (empty($tenant_uuid) && WC()->session) {
        $tenant_uuid = WC()->session->get('addon_tenant_uuid') ?? '';
    }
    if (empty($tenant_uuid)) return;
    ?>
    <style>
    /* Field 1 dropdown — readonly, tidak bisa diklik/diubah */
    select[id^="input_64_1"] {
        pointer-events: none !important;
        background: #f5f5f5 !important;
        color: #444 !important;
        cursor: default !important;
        border-color: #ddd !important;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    </style>
    <?php
}

// ================================================================
// 5b. Auto-select value + payment period via JS
// ================================================================
add_action('wp_footer', 'phoenix_gf64_autoselect_js');
function phoenix_gf64_autoselect_js() {
    if (!is_product()) return;
    if (!is_user_logged_in()) return;

    $tenant_uuid = sanitize_text_field($_GET['tenant_uuid'] ?? '');
    if (empty($tenant_uuid) && WC()->session) {
        $tenant_uuid = WC()->session->get('addon_tenant_uuid') ?? '';
    }
    if (empty($tenant_uuid)) return;

    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    $base  = $wpdb->get_row($wpdb->prepare(
        "SELECT customer_id, tenant_name FROM $table WHERE tenant_uuid = %s AND customer_id = %d LIMIT 1",
        $tenant_uuid, get_current_user_id()
    ));
    if (!$base) return;

    // Cari billing period dari subscription aktif tertinggi
    $all_rows = $wpdb->get_results($wpdb->prepare(
        "SELECT subscription_wc_id FROM $table WHERE customer_id = %d AND tenant_name = %s",
        (int) $base->customer_id, $base->tenant_name
    ));

    $billing_period = 'Monthly';
    $highest_level  = 0;
    foreach ($all_rows as $r) {
        if (!$r->subscription_wc_id) continue;
        $sub = function_exists('wcs_get_subscription') ? wcs_get_subscription((int)$r->subscription_wc_id) : null;
        if (!$sub || !$sub->has_status(['active', 'pending-cancel'])) continue;
        $level = function_exists('phoenix_get_subscription_plan_level') ? phoenix_get_subscription_plan_level($sub) : 0;
        if ($level > $highest_level) {
            $highest_level  = $level;
            $billing_period = (function_exists('phoenix_is_yearly_subscription') && phoenix_is_yearly_subscription($sub))
                ? 'Yearly' : 'Monthly';
        }
    }
    ?>
    <script>
    (function() {
        var tenantUuid    = <?php echo json_encode($tenant_uuid); ?>;
        var tenantName    = <?php echo json_encode($base->tenant_name); ?>;
        var billingPeriod = <?php echo json_encode($billing_period); ?>;

        function autoFill() {
            // ── Auto-select + hide + lock instance dropdown (Field 1) ──
            var sel = document.querySelector('select[id^="input_64_1"]');
            if (sel) {
                var matched = false;
                for (var i = 0; i < sel.options.length; i++) {
                    if (sel.options[i].value === tenantUuid) {
                        sel.selectedIndex = i; matched = true; break;
                    }
                }
                if (!matched) {
                    for (var i = 0; i < sel.options.length; i++) {
                        if (sel.options[i].text.trim().toLowerCase() === tenantName.toLowerCase()) {
                            sel.selectedIndex = i; matched = true; break;
                        }
                    }
                }
                if (matched) sel.dispatchEvent(new Event('change', { bubbles: true }));

                // Remove dropdown arrow to look readonly
                sel.style.backgroundImage = 'none';
            }

            // ── Auto-set payment period (radio Monthly/Yearly) ──
            var radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(function(radio) {
                var container = radio.closest('li') || radio.closest('.gchoice') || radio.parentElement;
                var text = container ? container.textContent.trim().toLowerCase() : '';
                if (text === billingPeriod.toLowerCase()) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }

        // Run setelah DOM ready + delay untuk GF render
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                autoFill();
                setTimeout(autoFill, 600);
            });
        } else {
            autoFill();
            setTimeout(autoFill, 600);
        }

        // Hook GF post render (AJAX)
        if (typeof jQuery !== 'undefined') {
            jQuery(document).on('gform_post_render', function(e, formId) {
                if (formId === 64) setTimeout(autoFill, 300);
            });
        }
    })();
    </script>
    <?php
}