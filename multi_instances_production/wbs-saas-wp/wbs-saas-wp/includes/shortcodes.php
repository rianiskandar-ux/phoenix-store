<?php

/**
 * All shortcodes called in Phoenix
 *
 * @since 1.0.0
 *
 * @link https://codex.wordpress.org/Shortcode_API
 *
 */

/**
 * Shortcode used in ~/my-account/phoenix
 *
 * @since 2.0.0
 *
 * @link https://www.figma.com/file/7JcQXo9XCxmrN3V7NKnmXM/Phoenix-WBS-Website?type=design&node-id=6194-28809&mode=design&t=PSDmNSkOi7B6y1VB-0
 *
 * @see ./assets/css/wbs-saas.css
 *
 */

add_shortcode('wbsaas_tenants', 'wbsaas_shortcode_tenants');

function wbsaas_shortcode_tenants()
{
    $shortcode = '';
    $tenant = new \WBSSaaS\Tenant();
    $tenant->prepareTenants( get_current_user_id() );
    
    // Display Notifications before the accordions
    foreach( $tenant->notification as $notification ) {
        $shortcode .= $notification;
    }
    
    $shortcode .= $tenant->display();

    $output = do_shortcode( $shortcode );

    return $output;
    
}

/**
 * Shortcode to get the price of a WC Product.
 * It divides by 12 the price if the payment is yearly
 * 
 * @since 2.0.0
 * 
 * @see hook woocommerce_subscriptions_product_price_string to execute the shortcode
 * in the plugin https://github.com/woocommerce/woocommerce-subscriptions-custom-price-string
 * 
 */
add_shortcode( 'product_price', 'wbsaas_shortcode_get_price' );
function wbsaas_shortcode_get_price( $atts ) {

    // $log =  new \WBSSaaS\Logger();
    // $log->info( [__METHOD__, __LINE__], 'Start Shortcode product_price');
    
    $atts = shortcode_atts( array(
        'id' => null,
    ), $atts, 'product_price' );
    
    $output = '';
    
    // Validate ID and WooCommerce existence
    if( intval( $atts['id'] ) > 0 && function_exists( 'wc_get_product' ) ) {
        // Get an instance of the WC_Product object
        $product = wc_get_product( intval( $atts['id'] ) );
        // $log->debug( [__METHOD__, __LINE__], $product );
    }

    if( empty( $product ) ) {
        return "No Product with the ID #" . esc_html( $atts['id'] );
    }

    // Get the product prices
    // $product->get_price()         = Get the active price
    // $product->get_regular_price() = Get the regular price
    // $product->get_sale_price()    = Get the sale price
    $price = wc_get_price_to_display( $product, array( 'price' => $product->get_price() ) ); // Get the active price

    // Get the store's default number of decimals instead of calculating it
    $decimals = ($price == round($price)) ? 0 : 2;
    $current_currency = get_woocommerce_currency();

    // Set base arguments to avoid repeating 'ex_tax_label' and 'in_span'
    $args = array(
        'ex_tax_label'       => false,
        'currency'           => $current_currency,
        'decimals'           => $decimals,
        'in_span'            => true,
    );

    switch ( $current_currency ) {
        case 'CHF':
            $args['decimal_separator']  = '.';
            $args['thousand_separator'] = '\'';
            $args['price_format']       = '%1$s&thinsp;%2$s'; // Symbol first
            break;
        case 'EUR':
            $args['decimal_separator']  = ',';
            $args['thousand_separator'] = '.';
            $args['price_format']       = '%2$s&thinsp;%1$s'; // Symbol last
            break;
        case 'USD':
            $args['decimal_separator']  = '.';
            $args['thousand_separator'] = ',';
            $args['price_format']       = '%2$s&thinsp;%1$s'; // Symbol last
            break;
    }
        
    // Force the trim of zeros if the price is an integer (e.g. 10.00 => 10)
    add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
    $output .= wc_price( $price, $args );
    remove_filter( 'woocommerce_price_trim_zeros', '__return_true' );
    return $output;
}

/**
 * Execute the shortcode in the field Custom price string
 * 
 * @since 2.0.0
 * 
 * @link https://github.com/woocommerce/woocommerce-subscriptions-custom-price-string
 * 
 */
add_filter( 'woocommerce_subscriptions_product_price_string', 'wbssaas_execute_shortcode', 100, 1 );

function wbssaas_execute_shortcode( $subscription_string ) {
	return do_shortcode( $subscription_string );
}

/**
 * Shortcode Translations Composite
 * Key for Translations:
 *     - core
 *     - theme_library
 *     - theme_whitelabel
 *     - language
 *     - mobile_app
 *     - email
 *     - phone
 *     - im
 *     - postmail
 *     - chat
 *     - manager
 *     - operator
 *     - agent
 * 
 * @since 2.0.0
 * 
 * @see languages/composite.php
 * 
 */
add_shortcode('wbs_composite', 'wbs_composite_shortcode');
function wbs_composite_shortcode( $atts ) {

    $a = shortcode_atts( array(
        'channel' => null,
        'label' => false,
        'desc' => false,
    ), $atts );

    $language_code = get_locale();

    $translation = require WBSSAAS_PLUGIN_DIR . 'languages/composite.php';

    // Check if channel exists and if the language code exists for the channel
    if (isset($translation[$a['channel']]) && isset($translation[$a['channel']][$language_code])) {
        if ($a['label'] == true) {
            return $translation[$a['channel']][$language_code]['label'];
        } else {
            return $translation[$a['channel']][$language_code]['desc'];
        }
    } else {
        return "Translation not available for the selected channel and language.";
    }
}

/**
 * Get metadata from a WC_Object
 * 
 * @since 2.0.0
 * 
 * @link https://developer.wordpress.org/apis/shortcode/
 */

add_shortcode( 'wc_product', 'wbsaas_shortcode_get_wc_metadata' );
function wbsaas_shortcode_get_wc_metadata( $atts ) {

    $a = shortcode_atts( array(
        'id'     => null,
        'meta'   => null,
        'option' => null
	), $atts );

    $meta = array( 'title', 'price', 'desc' );
    $current_language = apply_filters( 'wpml_current_language', NULL );
    $output = '';

    if ( intval( $a['id'] ) > 0 && function_exists( 'wc_get_product' ) ) {
        // Get an instance of the WC_Product object
        $product = wc_get_product( intval( $a['id'] ) );
    }
    
    /**
     * Error Handlers
     */

    if ( empty( $product ) ) {

        return "No WC Product found with the ID: " . $a['id'];
    }

    if (!in_array( $a['meta'], $meta ) ) {

        return "Wrong keyword used to call a WC Metadata.";
    }

    // Check if the WC Product is a variation. If not throw an error
    if ( $product->is_type('variation') ) {

        $parent = wc_get_product( $product->get_parent_id() );
        $payment = $product->get_variation_attributes();
        $translated_parent_id = apply_filters( 'wpml_object_id', $parent->get_id(), 'product_variation', true, $current_language );
        $translated_parent = wc_get_product( $translated_parent_id );

    } else {

        return "The WC Product must be a variation.";
    }

    /**
     * Get and Display metadata Title
     */

    if ( $a['meta'] == 'title' ) {

        $output = $translated_parent->get_name();
    }

    /**
     * Get and Display metadata Price
     */
    
    if ( $a['meta'] == 'price' ) {

        $price = wc_get_price_to_display( $product, array( 'price' => $product->get_price() ) ); // Get the active price

        if ( $payment['attribute_payment'] == 'Yearly' && $a['option'] == 'yearly-per-month') {
            $price = $price / 12;
        }

        if ( $a['option'] == 'raw' ) {

            $output = $price;

        } else {

            // Formatting price settings (for the wc_price() function)
            // $current_currency = ( isset( $_COOKIE['wcml_client_currency'] ) ) ? $_COOKIE['wcml_client_currency'] : 'EUR';
            $current_currency = get_woocommerce_currency();

            switch ( $current_currency ) {
                case 'CHF':
                    $args = array(
                        'ex_tax_label'       => false,
                        'currency'           => 'CHF',
                        'decimal_separator'  => '.',
                        'thousand_separator' => '\'',
                        'decimals'           => 2,
                        'price_format'       => '%1$s&nbsp;%2$s',
                    );
                    break;
                case 'EUR':
                    $args = array(
                        'ex_tax_label'       => false,
                        'currency'           => 'EUR',
                        'decimal_separator'  => ',',
                        'thousand_separator' => '.',
                        'decimals'           => 2,
                        'price_format'       => '%2$s&nbsp;%1$s',
                    );
                    break;
                case 'USD':
                    $args = array(
                        'ex_tax_label'       => false,
                        'currency'           => 'USD',
                        'decimal_separator'  => '.',
                        'thousand_separator' => ',',
                        'decimals'           => 2,
                        'price_format'       => '%2$s&nbsp;%1$s',
                    );
                    break;
            }
            
            $output = wc_price( $price, $args );
        }
    }

    /**
     * Get and Display metadata Description 
     * 
     * @link https://wpml.org/documentation/support/wpml-coding-api/wpml-hooks-reference/#hook-605256
     */

    if ( $a['meta'] == 'desc' ) {

        $output = $translated_parent->get_short_description();
    }

    /**
     * Get and Display debug info
     */
    if ( $a['option'] == 'debug' ) {

        // $locale = get_locale();
        // $languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );

        $output = '<pre>';
        $output .= 'ID              : ' . $a['id'] . PHP_EOL;
        $output .= 'Current Language: ' . $current_language . PHP_EOL;
        $output .= 'Current Currency: ' . get_woocommerce_currency() . PHP_EOL;
        $output .= 'Payment Meta    : ' . $payment['attribute_payment'] . PHP_EOL;
        $output .= 'Parameters      : meta=title|price|desc option=yearly-per-month|raw|debug'. PHP_EOL;
        $output .= '</pre>';

    }

    return $output;
}
