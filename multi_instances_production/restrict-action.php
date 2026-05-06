
/**
 * SNIPPET: Hide Shop UI Links
 * v2: + hide /product-category/add-on/ links + Related Products section
 */

// ── CSS ──────────────────────────────────────────────────────────────────────
add_action('wp_head', 'hide_shop_ui_css');
function hide_shop_ui_css() {
    ?>
    <style id="phoenix-hide-shop-ui">
    /* Hide link ke /shop/ */
    a[href*="/shop/"],
    a[href*="/product-category/add-on"],
    a[href*="post_type=product"],
    .wc-forward,
    a.wc-forward,
    .return-to-shop,
    .return-to-shop a,
    .woocommerce-continue-shopping,
    .woocommerce-info .button,
    .woocommerce-message .button,
    .woocommerce-info:has(a[href*="/shop/"]),
    .woocommerce-message:has(a[href*="/shop/"]) {
        display: none !important;
    }

    /* Hide link dan label category add-on */
    a[href*="/product-category/add-on"],
    a[href*="/product-category/add-on/"],
    .category-list:has(a[href*="/product-category/add-on"]),
    .posted_in:has(a[href*="/product-category/add-on"]),
    .product_meta .posted_in:has(a[href*="/product-category/add-on"]) {
        display: none !important;
    }

    /* Hide Related Products heading Porto (class spesifik dari template add-on) */
    .vc_custom_1659486256331 {
        display: none !important;
    }
    </style>
    <?php
}

// ── JS backstop ───────────────────────────────────────────────────────────────
add_action('wp_footer', 'hide_shop_ui_js');
function hide_shop_ui_js() {
    ?>
    <script>
    (function() {
        var shopTexts = [
            'browse products', 'go to shop', 'shop now', 'start shopping',
            'return to shop', 'continue shopping', 'view products', 'go shop'
        ];

        function removeShopUI() {
            // Hide link ke /shop/ dan /product-category/add-on/
            document.querySelectorAll('a').forEach(function(link) {
                var href = link.getAttribute('href') || '';
                if (
                    href.indexOf('/shop/') !== -1 ||
                    href.indexOf('post_type=product') !== -1 ||
                    href.indexOf('/product-category/add-on') !== -1
                ) {
                    link.style.display = 'none';
                    var parent = link.parentNode;
                    if (parent && ['P', 'LI'].indexOf(parent.tagName) !== -1) {
                        var tmp = parent.cloneNode(true);
                        tmp.querySelectorAll('a').forEach(function(a) { a.remove(); });
                        if (tmp.textContent.trim() === '') parent.style.display = 'none';
                    }
                }
            });

            // Hide button berdasarkan teks
            document.querySelectorAll('a, button, .button').forEach(function(btn) {
                if (shopTexts.indexOf(btn.textContent.trim().toLowerCase()) !== -1) {
                    btn.style.display = 'none';
                }
            });

            // Hide notice box yang contain link /shop/
            document.querySelectorAll('.woocommerce-info, .woocommerce-message').forEach(function(box) {
                if (box.querySelector('a[href*="/shop/"]')) box.style.display = 'none';
            });

            // Hide Related Products heading Porto (vc_custom_1659486256331) + row-nya
            document.querySelectorAll('.vc_custom_1659486256331').forEach(function(el) {
                var row = el.closest('.wpb_row, .porto-inner-container');
                if (row) row.style.display = 'none';
                else el.style.display = 'none';
            });

            // Hide .posted_in yang berisi link add-on
            document.querySelectorAll('.posted_in a[href*="/product-category/add-on"]').forEach(function(el) {
                var span = el.closest('.posted_in');
                if (span) span.style.display = 'none';
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', removeShopUI);
        } else {
            removeShopUI();
        }
        window.addEventListener('load', function() { setTimeout(removeShopUI, 500); });
        document.addEventListener('DOMContentLoaded', function() {
            new MutationObserver(removeShopUI).observe(document.body, { childList: true, subtree: true });
        });
    })();
    </script>
    <?php
}

// ── Strip link shop dari WC messages ─────────────────────────────────────────
add_filter('woocommerce_no_orders_message', 'strip_shop_link_from_messages');
add_filter('wcs_no_subscriptions_message',  'strip_shop_link_from_messages');
function strip_shop_link_from_messages($message) {
    $message = preg_replace('/<a[^>]*href=["\'][^"\']*\/shop\/[^"\']*["\'][^>]*>.*?<\/a>/is', '', $message);
    $message = preg_replace('/<a[^>]*class=["\'][^"\']*button[^"\']*["\'][^>]*>.*?<\/a>/is', '', $message);
    return $message;
}

// ── Remove shop dari account menu ─────────────────────────────────────────────
add_filter('woocommerce_account_menu_items', function($items) {
    unset($items['shop']);
    return $items;
});



// ── Remove Related Products & Upsells via WC filter ──────────────────────────
add_filter('woocommerce_related_products', '__return_empty_array', 99);
add_filter('woocommerce_upsell_display_args', function($args) {
    $args['posts_per_page'] = 0;
    return $args;
}, 99);