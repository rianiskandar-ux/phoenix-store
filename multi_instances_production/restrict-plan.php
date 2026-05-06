/**
 * SNIPPET: Plan Page Smart Gate v3 - Multi-Instance Support
 * 
 * UX Flow:
 * - User BISA akses semua plan pages (tidak ada redirect)
 * - Show notice banner kalau plan <= current plan
 * - Disable "Add to Cart" untuk lower/equal plans
 * 
 * Multi-Instance Logic:
 * - Kalau ADA ?upgrade_subscription=XXX → cek plan INSTANCE itu saja
 * - Kalau TIDAK ADA → new instance → BYPASS gate (bebas beli apapun)
 * 
 * Testing mode: Bypass gate dengan ?testing_downgrade=1
 */

/**
 * 1. Show notice banner INSIDE product content (after title)
 */
add_action('woocommerce_single_product_summary', 'show_plan_access_notice', 6);
function show_plan_access_notice() {
    
    // TESTING MODE
    if (defined('PHOENIX_ALLOW_DOWNGRADE_TESTING') && PHOENIX_ALLOW_DOWNGRADE_TESTING) {
        if (isset($_GET['testing_downgrade'])) {
            ?>
            <div style="background:#ff9800;color:#fff;padding:12px 20px;margin-bottom:20px;border-radius:6px;font-weight:600;">
                <?php echo phoenix_text('restrict.testing_mode'); ?>
            </div>
            <?php
            return;
        }
    }
    
    if (!is_product()) return;
    if (!is_user_logged_in()) return;
    if (!function_exists('wcs_get_users_subscriptions')) return;
    
    global $post;
    $product_id = $post->ID;
    
    $plan_levels = [
        30688 => ['level' => 0, 'name' => 'Free', 'display' => 'Free Plan'],
        11    => ['level' => 0, 'name' => 'Free', 'display' => 'Starter Plan'],
        58    => ['level' => 1, 'name' => 'Basic', 'display' => 'Basic Plan'],
        22    => ['level' => 1, 'name' => 'Basic', 'display' => 'Standard Plan'],
        76    => ['level' => 2, 'name' => 'Premium', 'display' => 'Premium Plan'],
        33    => ['level' => 2, 'name' => 'Premium', 'display' => 'Custom Plan'],
    ];
    
    if (!isset($plan_levels[$product_id])) return;
    
    $page_plan = $plan_levels[$product_id];
    $user_id = get_current_user_id();
    
    // ══════════════════════════════════════════════════════════════════════
    // MULTI-INSTANCE LOGIC
    // ══════════════════════════════════════════════════════════════════════
    
    $upgrade_sub_id = null;
    
    // Check for upgrade parameter
    if (isset($_GET['upgrade_subscription']) && absint($_GET['upgrade_subscription']) > 0) {
        $upgrade_sub_id = absint($_GET['upgrade_subscription']);
    } elseif (isset($_GET['switch-subscription']) && absint($_GET['switch-subscription']) > 0) {
        $upgrade_sub_id = absint($_GET['switch-subscription']);
    }
    
    if (!$upgrade_sub_id) {
        // NO UPGRADE PARAMETER = NEW INSTANCE
        // No notice needed — user can buy any plan for new instance
        return;
    }
    
    // WITH UPGRADE PARAMETER = UPGRADING EXISTING INSTANCE
    // Get plan level of THAT SPECIFIC INSTANCE only
    
    $instance_subscription = function_exists('wcs_get_subscription') 
        ? wcs_get_subscription($upgrade_sub_id) 
        : null;
    
    if (!$instance_subscription || !$instance_subscription->has_status('active')) {
        // Subscription not found or not active — no notice
        return;
    }
    
    // Get plan level of this specific instance
    $instance_level = -1;
    $instance_plan_name = '';
    $instance_plan_display = '';
    
    foreach ($instance_subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) continue;
        
        $item_pid = $item->get_product_id();
        
        if (isset($plan_levels[$item_pid])) {
            $instance_level = $plan_levels[$item_pid]['level'];
            $instance_plan_name = $plan_levels[$item_pid]['name'];
            $instance_plan_display = $item->get_name();
            break;
        }
    }
    
    if ($instance_level === -1) return; // No plan found in instance
    
    // ══════════════════════════════════════════════════════════════════════
    // NOTICE DISPLAY (based on instance plan, not global)
    // ══════════════════════════════════════════════════════════════════════
    
    if ($page_plan['level'] < $instance_level) {
        // Viewing LOWER plan than instance's current plan
        ?>
        <div style="background:#f3e5f5;border-left:4px solid #9c27b0;padding:16px;margin-bottom:20px;margin-top:40px;border-radius:0 6px 6px 0;">
            <div style="display:flex;align-items:flex-start;gap:10px;">
                <span style="font-size:20px;">✨</span>
                <div style="flex:1;">
                    <strong style="color:#6a1b9a;font-size:14px;"><?php echo phoenix_text('restrict.notice_on_lower', esc_html($instance_plan_display)); ?></strong>
                    <p style="margin:4px 0 8px 0;font-size:13px;color:#424242;">
                        <?php echo phoenix_text('restrict.notice_downgrade'); ?>
                    </p>
                    
                    <?php
                    // Find next higher plan for this instance
                    $next_plan_url = '';
                    $next_plan_name = '';
                    
                    foreach ($plan_levels as $pid => $pdata) {
                        if ($pdata['level'] > $instance_level) {
                            $next_plan_url = get_permalink($pid) . '?upgrade_subscription=' . $upgrade_sub_id;
                            $next_plan_name = $pdata['display'];
                            break;
                        }
                    }
                    ?>
                    
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;">
                        <?php if ($next_plan_url): ?>
                            <a href="<?php echo esc_url($next_plan_url); ?>"
                               style="display:inline-block;padding:6px 14px;background:#9c27b0;color:#fff;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">
                                <?php echo phoenix_text('restrict.btn_upgrade_to', esc_html($next_plan_name)); ?>
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo esc_url(wc_get_endpoint_url('view-subscription', $upgrade_sub_id, wc_get_page_permalink('myaccount'))); ?>"
                           style="display:inline-block;padding:6px 14px;background:#e1bee7;color:#6a1b9a;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">
                            <?php echo phoenix_text('restrict.btn_view_sub'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
    } elseif ($page_plan['level'] === $instance_level) {
        // Viewing SAME plan as instance
        ?>
        <div style="background:#e3f2fd;border-left:4px solid #2196f3;padding:16px;margin-bottom:20px;margin-top:40px;border-radius:0 6px 6px 0;">
            <div style="display:flex;align-items:flex-start;gap:10px;">
                <span style="font-size:20px;">✅</span>
                <div style="flex:1;">
                    <strong style="color:#1565c0;font-size:14px;"><?php echo phoenix_text('restrict.notice_already_has', esc_html($instance_plan_display)); ?></strong>
                    <p style="margin:4px 0 8px 0;font-size:13px;color:#424242;">
                        <?php echo phoenix_text('restrict.notice_manage_in'); ?>
                        <a href="<?php echo esc_url(wc_get_endpoint_url('view-subscription', $upgrade_sub_id, wc_get_page_permalink('myaccount'))); ?>"
                           style="color:#1976d2;"><?php echo phoenix_text('restrict.link_sub_details'); ?></a>
                    </p>
                    
                    <?php
                    // Find next higher plan
                    $next_plan_url = '';
                    $next_plan_name = '';
                    
                    foreach ($plan_levels as $pid => $pdata) {
                        if ($pdata['level'] > $instance_level) {
                            $next_plan_url = get_permalink($pid) . '?upgrade_subscription=' . $upgrade_sub_id;
                            $next_plan_name = $pdata['display'];
                            break;
                        }
                    }
                    
                    if ($next_plan_url):
                    ?>
                        <a href="<?php echo esc_url($next_plan_url); ?>"
                           style="display:inline-block;padding:6px 14px;background:#2196f3;color:#fff;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">
                            <?php echo phoenix_text('restrict.btn_upgrade_to', esc_html($next_plan_name)); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
    // else: page_plan['level'] > instance_level → viewing HIGHER plan → no notice (allow upgrade)
}

/**
 * 2. Disable Add to Cart untuk lower/equal plans
 *    PER-INSTANCE CHECK!
 */
add_filter('woocommerce_is_purchasable', 'gate_make_lower_plan_non_purchasable', 10, 2);
function gate_make_lower_plan_non_purchasable($purchasable, $product) {
    
    // TESTING MODE
    if (defined('PHOENIX_ALLOW_DOWNGRADE_TESTING') && PHOENIX_ALLOW_DOWNGRADE_TESTING) {
        if (isset($_GET['testing_downgrade'])) {
            return true;
        }
    }
    
    if (!is_user_logged_in()) return $purchasable;
    if (!function_exists('wcs_get_users_subscriptions')) return $purchasable;
    
    $product_id = $product->get_id();
    $parent_id = $product->get_parent_id() ?: $product_id;
    
    $plan_levels = [
        30688 => 0, 11 => 0,
        58 => 1, 22 => 1,
        76 => 2, 33 => 2,
    ];
    
    if (!isset($plan_levels[$parent_id])) return $purchasable;
    
    $product_level = $plan_levels[$parent_id];
    
    // ══════════════════════════════════════════════════════════════════════
    // MULTI-INSTANCE LOGIC
    // ══════════════════════════════════════════════════════════════════════
    
    $upgrade_sub_id = null;
    
    // Check GET (page load) AND POST (GF form submission)
    if (isset($_GET['upgrade_subscription']) && absint($_GET['upgrade_subscription']) > 0) {
        $upgrade_sub_id = absint($_GET['upgrade_subscription']);
    } elseif (isset($_GET['switch-subscription']) && absint($_GET['switch-subscription']) > 0) {
        $upgrade_sub_id = absint($_GET['switch-subscription']);
    } elseif (isset($_POST['upgrade_subscription']) && absint($_POST['upgrade_subscription']) > 0) {
        $upgrade_sub_id = absint($_POST['upgrade_subscription']);
    } elseif (isset($_POST['switch-subscription']) && absint($_POST['switch-subscription']) > 0) {
        $upgrade_sub_id = absint($_POST['switch-subscription']);
    }
    
    if (!$upgrade_sub_id) {
        // NO UPGRADE PARAMETER = NEW INSTANCE
        // User can buy any plan for new instance — BYPASS GATE
        return $purchasable;
    }
    
    // WITH UPGRADE PARAMETER = UPGRADING EXISTING INSTANCE
    // Check plan level of THAT SPECIFIC INSTANCE only
    
    $instance_subscription = function_exists('wcs_get_subscription') 
        ? wcs_get_subscription($upgrade_sub_id) 
        : null;
    
    if (!$instance_subscription || !$instance_subscription->has_status('active')) {
        // Subscription not found or not active — allow purchase
        return $purchasable;
    }
    
    // Get plan level of this specific instance
    $instance_level = -1;
    
    foreach ($instance_subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) continue;
        
        $item_pid = $item->get_product_id();
        
        if (isset($plan_levels[$item_pid])) {
            $instance_level = $plan_levels[$item_pid];
            break;
        }
    }
    
    // Block purchase if product level <= INSTANCE's current level
    if ($instance_level >= 0 && $product_level <= $instance_level) {
        return false;
    }
    
    return $purchasable;
}

/**
 * 3. Hide price/add to cart button visual untuk lower/equal plans
 *    PER-INSTANCE CHECK!
 */
add_action('woocommerce_single_product_summary', 'gate_hide_purchase_section_for_blocked_plans', 25);
function gate_hide_purchase_section_for_blocked_plans() {
    
    // TESTING MODE
    if (defined('PHOENIX_ALLOW_DOWNGRADE_TESTING') && PHOENIX_ALLOW_DOWNGRADE_TESTING) {
        if (isset($_GET['testing_downgrade'])) {
            return;
        }
    }
    
    if (!is_user_logged_in()) return;
    if (!function_exists('wcs_get_users_subscriptions')) return;
    
    global $post;
    $product_id = $post->ID;
    
    $plan_levels = [
        30688 => 0, 11 => 0,
        58 => 1, 22 => 1,
        76 => 2, 33 => 2,
    ];
    
    if (!isset($plan_levels[$product_id])) return;
    
    $product_level = $plan_levels[$product_id];
    
    // ══════════════════════════════════════════════════════════════════════
    // MULTI-INSTANCE LOGIC
    // ══════════════════════════════════════════════════════════════════════
    
    $upgrade_sub_id = null;
    
    if (isset($_GET['upgrade_subscription']) && absint($_GET['upgrade_subscription']) > 0) {
        $upgrade_sub_id = absint($_GET['upgrade_subscription']);
    } elseif (isset($_GET['switch-subscription']) && absint($_GET['switch-subscription']) > 0) {
        $upgrade_sub_id = absint($_GET['switch-subscription']);
    }
    
    if (!$upgrade_sub_id) {
        // NO UPGRADE PARAMETER = NEW INSTANCE
        // No visual gate — user can see purchase section
        return;
    }
    
    // WITH UPGRADE PARAMETER = UPGRADING EXISTING INSTANCE
    // Check plan level of THAT SPECIFIC INSTANCE
    
    $instance_subscription = function_exists('wcs_get_subscription') 
        ? wcs_get_subscription($upgrade_sub_id) 
        : null;
    
    if (!$instance_subscription || !$instance_subscription->has_status('active')) {
        return;
    }
    
    // Get plan level of this specific instance
    $instance_level = -1;
    
    foreach ($instance_subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) continue;
        
        $item_pid = $item->get_product_id();
        
        if (isset($plan_levels[$item_pid])) {
            $instance_level = $plan_levels[$item_pid];
            break;
        }
    }
    
    // Hide purchase section if viewing lower/equal plan than INSTANCE
    if ($instance_level >= 0 && $product_level <= $instance_level) {
        ?>
        <style>
        .product .price,
        .product .single_variation_wrap,
        .product .variations_form,
        .product .single_add_to_cart_button,
        .product form.cart {
            display: none !important;
        }
        </style>
        <script>
        window.alert = function(msg) {
            if (msg && (
                msg.indexOf('unavailable') !== -1 || 
                msg.indexOf('different combination') !== -1 ||
                msg.indexOf('Sorry') !== -1
            )) {
                console.log('[Blocked alert]:', msg);
                return;
            }
            return window.originalAlert ? window.originalAlert(msg) : undefined;
        };
        window.originalAlert = window.alert;
        </script>
        <?php
    }
}