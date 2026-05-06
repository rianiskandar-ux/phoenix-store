// ================================================================
// SECTION 1: UPDATE HARGA DISPLAY DI PRODUCT PAGE (JS)
// Real-time update "$12/month" → "$72/month" saat qty diubah
// ================================================================
add_action('woocommerce_after_add_to_cart_button', 'phoenix_addon_qty_price_js');
function phoenix_addon_qty_price_js() {
    global $product;
    if (!$product) return;

    // Hanya untuk produk kategori add-on
    if (!has_term('add-on', 'product_cat', $product->get_id())) return;

    ?>
    <script>
        (function($){
            let BASE_PRICE = null;

            function getQty() {
                var el = document.querySelector('input.qty');
                return el ? parseInt(el.value) || 1 : 1;
            }

            function updatePriceDisplay() {
                if (BASE_PRICE === null) return;

                var container = document.querySelector('.woocommerce-variation-price');
                if (!container) return;

                var amount = container.querySelector('.woocommerce-Price-amount bdi');
                if (!amount) return;

                var qty = getQty();
                var total = BASE_PRICE * qty;

                var symbolEl = amount.querySelector('.woocommerce-Price-currencySymbol');
                var symbol = symbolEl ? symbolEl.outerHTML : '';

                amount.innerHTML = symbol + total;
            }

            // Ambil harga dari WooCommerce variation
            $(document).on('found_variation', function(event, variation){
                if (variation && variation.display_price) {
                    BASE_PRICE = parseFloat(variation.display_price);
                } else {
                    BASE_PRICE = null;
                }

                setTimeout(updatePriceDisplay, 100);
            });

            // Update saat user ubah qty manual
            document.addEventListener('input', function(e){
                if (e.target.matches('input.qty')) {
                    updatePriceDisplay();
                }
            });

            // Update saat user klik plus/minus button
            document.addEventListener('click', function(e){
                if (e.target.matches('.plus, .minus')) {
                    setTimeout(updatePriceDisplay, 50);
                }
            });

            // Gravity Forms (jika ada) trigger
            $(document).on('gform_post_render', function(){
                setTimeout(updatePriceDisplay, 200);
            });

        })(jQuery);
    </script>
    <?php
}