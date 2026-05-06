
/**
 * SNIPPET: Theme Order Auto-Complete
 *
 * Plugin WBS SaaS update tenant_settings hanya saat order status = 'completed'
 * Tapi WC default untuk virtual/downloadable products kadang stay di 'processing'
 * 
 * Snippet ini auto-complete WC orders yang berisi theme products
 * sehingga plugin bisa trigger dan update tenant_settings + Phoenix API
 */

// Auto-complete order kalau semua items adalah theme (category 'theme')
add_action('woocommerce_payment_complete', 'phoenix_autocomplete_theme_order', 10, 1);
add_action('woocommerce_order_status_processing', 'phoenix_autocomplete_theme_order', 10, 1);

function phoenix_autocomplete_theme_order($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    // Skip kalau sudah completed
    if ($order->has_status('completed')) return;

    // Skip kalau ini subscription renewal order
    if ($order->get_meta('_subscription_renewal')) return;

    $has_theme    = false;
    $all_theme    = true;

    foreach ($order->get_items() as $item) {
        $pid = $item->get_product_id();
        if (has_term('theme', 'product_cat', $pid)) {
            $has_theme = true;
        } else {
            $all_theme = false;
        }
    }

    // Only auto-complete kalau semua items adalah theme
    if ($has_theme && $all_theme) {
        $order->update_status('completed', __('Auto-completed: theme order.', 'wbs-saas-plugin'));
    }
}