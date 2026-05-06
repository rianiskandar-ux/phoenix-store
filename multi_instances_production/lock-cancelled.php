// ===============================
// 🔒 FULL LOCK CANCEL (ADMIN ONLY)
// ===============================

// 1. Block user cancel dari WooCommerce
add_filter('wcs_user_can_cancel_subscription', function($can, $subscription) {
    if (current_user_can('manage_options')) return true;
    return false;
}, 999, 2);

// 2. Hard block (anti bypass)
add_action('woocommerce_before_subscription_object_save', function($subscription) {
    if (!current_user_can('manage_options') && $subscription->get_status() === 'pending-cancel') {
        throw new Exception('Cancel not allowed');
    }
});

// 3. Disable AJAX cancel dari sistem kamu
add_action('wp_ajax_phoenix_cancel_subscription', function() {
    wp_send_json_error(['message' => 'Cancel disabled.']);
}, 1);

// 4. Disable yearly cancel logic (kalau masih ada)
add_filter('phoenix_yearly_allow_cancel', '__return_false', 999);