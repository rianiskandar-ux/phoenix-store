<?php

/**
 * All the hooks for WooCommerce and WC Subscription
 * 
 * @link https://woocommerce.github.io/code-reference/hooks/hooks.html
 * 
 * @link https://woocommerce.com/document/subscriptions/develop/
 * @link https://woocommerce.com/document/subscriptions/develop/functions/
 * @link https://woocommerce.com/document/subscriptions/develop/action-reference/
 * @link https://woocommerce.com/document/subscriptions/develop/filter-reference/
 * 
 * @since 1.0.0
 * 
 */

/**
 * NEW SUBSCRIPTION: Hook triggered when a payment is completed for any new WC_Subscription Plan
 * 
 * @link https://woocommerce.com/document/subscriptions/develop/action-reference/#section-1
 * @link https://woocommerce.com/document/subscriptions/develop/functions/
 * 
 * @since 2.0.0
 * 
 * @param   object  $subscription   A WC_Subscription object representing the subscription which has just received a payment.
 * @return  void
 */

add_action( 'woocommerce_subscription_payment_complete', 'wbssaas_wcs_plan_payment_complete', 11, 1 );

function wbssaas_wcs_plan_payment_complete( $subscription )
{
    global $wc;
    global $gravity;

    $log = new \WBSSaaS\Logger();
    $log->info( [__METHOD__, __LINE__], 'Start hook Plan WC Subscription Payment complete' );

    foreach( $subscription->get_items() as $item_id => $product_subscription ) {

        // Get the type of subscription ID
        $parent_subscription_id = $product_subscription->get_product_id();

    }
    $log->debug( [__METHOD__, __LINE__], 'Get the WC_Subscription ID Phoenix Plan => ' . $parent_subscription_id );

    /**
     * We check if the Product ID belongs to any WC_Subscription
     * AND
     * how many renewals was made. If 0 it's a new WC Subscription
     * 
     * @link https://woocommerce.com/document/subscriptions/develop/functions/#section-16
     */
    $renewals = $subscription->get_related_orders( 'ids', 'renewal' );
    $log->debug( [__METHOD__, __LINE__], 'Current WC_Subscription: Get related renewal(s) =>' );
    $log->debug( [__METHOD__, __LINE__], $renewals );

    /**
     * We check if it's an upgrade of the subscription.
     * 
     * @link https://woocommerce.com/document/subscriptions/customers-view/subscribers-view-switch/
     * @link https://woocommerce.com/document/subscriptions/develop/action-reference/#subscription-switching-actions
     */
    $is_upgrade1 = $subscription->get_related_orders( 'ids', 'switch' );
    $is_upgrade2 = $subscription->get_meta( '_subscription_switch', true );

    $log->debug( [__METHOD__, __LINE__], 'Current WC_Subscription: Get switch order(s) =>' );
    $log->debug( [__METHOD__, __LINE__], $is_upgrade1 );
    $log->debug( [__METHOD__, __LINE__], $is_upgrade2 );


    /**
     * We get all the WC Subscription IDs to be sure that the current Subscription belongs to a Phoenix Plan Subscription
     */
    $all_subscriptions = array();
    foreach( $wc->subscription as $sub ) {
        foreach( $sub as $key => $value ) {
            if( $key == 'id' ) {
                $all_subscriptions[] = $value;
            }
        }
    }

    /**
     * It's not a renewal
     */
    if ( in_array( $parent_subscription_id, $all_subscriptions ) && count( $renewals ) == 0 )
    {
        $subscription_id = $subscription->get_id();
        $log->debug( [__METHOD__, __LINE__], 'Step #1: New WC_Subscription with the ID => ' . $subscription_id );

        $order_id = $subscription->get_parent_id();
        $log->debug( [__METHOD__, __LINE__], 'Step #2: New WC_Order with the ID from Subscription => ' . $order_id );

        /**
         * Get the GF entry just created by the GF in WC_Order
         * @link https://docs.gravityforms.com/searching-and-getting-entries-with-the-gfapi/
         */
        $search_criteria = array();
        $search_criteria['field_filters'][] = array( 'key' => 'woocommerce_order_number', 'value' => $order_id );
        $form_ids = array(
            $gravity->form->free->gid,
            $gravity->form->basic->gid,
            // $gravity->form->standard->gid,
            // $gravity->form->enhanced->gid,
            $gravity->form->premium->gid,
            // $gravity->form->composite->gid,
        );
        $entries = GFAPI::get_entries( $form_ids, $search_criteria );
        $entry = $entries[0];
        $log->debug( [__METHOD__, __LINE__], 'Step #3: Get GF Entry created during the checkout =>' );
        $log->debug( [__METHOD__, __LINE__], $entry );

        // Get the current plan
        foreach( $gravity->form as $plan => $cursor )
        {
            if( $cursor->gid == $entry['form_id'] )
            {
                $current_plan = $plan;
            }
        }
        $log->debug( [__METHOD__, __LINE__], 'Current plan => ' . $current_plan );

        // Get the location of the server
        $options = get_option('wbssaas_options');
        $location = ($options['environment'] == 'staging') ? 'staging' : $entry[$gravity->form->$current_plan->location];

        /**
         * We store all the must-have information into an array
         */
        if( !empty( $entry[$gravity->form->$current_plan->subdomain_phoenix] ) ) {
            
            // If location is staging, we add the subdomain ''stg'' for the phoenix domain
            // e.g. mywebsite.whistleblowing.direct -> mywebsite.stg.whistleblowing.direct

            if ( $location === 'staging' ) {
                $url = 'https://' . rgar( $entry, $gravity->form->$current_plan->subdomain_phoenix ) . '.stg.' . rgar( $entry, $gravity->form->$current_plan->domain_phoenix );
            } else {
                $url = 'https://' . rgar( $entry, $gravity->form->$current_plan->subdomain_phoenix ) . '.' . rgar( $entry, $gravity->form->$current_plan->domain_phoenix );
            }
            $log->debug( [__METHOD__, __LINE__], 'Value of the entry FQDN with Phoenix domain => ' . $url );
        }
    
        if( !empty( $entry[$gravity->form->$current_plan->subdomain_tenant] ) && !empty( $entry[$gravity->form->$current_plan->domain_tenant] ) ) {

            // If location is staging, we add the subdomain ''stg'' for the phoenix domain
            // e.g. mywebsite.whistleblowing.direct -> mywebsite.stg.whistleblowing.direct
            if ( $location === 'staging' ) {
                $url = 'https://' . rgar( $entry, $gravity->form->$current_plan->subdomain_tenant ) . '.stg.' . rgar( $entry, $gravity->form->$current_plan->domain_tenant );
            } else {
                $url = 'https://' . rgar( $entry, $gravity->form->$current_plan->subdomain_tenant ) . '.' . rgar( $entry, $gravity->form->$current_plan->domain_tenant );
            }
            $log->debug( [__METHOD__, __LINE__], 'Value of the entry FQDN with domain + subsdomain from Tenant => ' . $url );
        }
    
        if( !empty( $entry[$gravity->form->$current_plan->fqdn_custom] ) ) {
            $url = rgar( $entry, $gravity->form->$current_plan->fqdn_custom );
            $log->debug( [__METHOD__, __LINE__], 'Value of the entry Fully FQDN from tenant (own domain) => ' . $url );
        }

        if( empty( $url ) ) {
            $log->error( [__METHOD__, __LINE__], 'SaaS URL empty!!' );
        } else {
            $log->debug( [__METHOD__, __LINE__], 'SaaS URL => ' . $url );
        }
        
        $wp_user = get_user_by( 'id', $subscription->get_user_id() );

        /**
         * Step #4 -> Create an array with all the needed information to create a new Tenant in Phoenix:
         */
        $package = include WBSSAAS_PLUGIN_DIR . 'config/wc.php';
        $package = $package['subscription'][$current_plan]['default_package'];
        $log->info( [__METHOD__, __LINE__], 'Step #4: Default package => ' );
        $log->info( [__METHOD__, __LINE__], $package );

        $new_client = array(
            'company_name' => $entry[$gravity->form->$current_plan->name],
            'company_url'  => strtolower( $url ),
            'admin_email'  => $wp_user->user_email,
            'default_lang' => apply_filters( 'wpml_current_language', null ),
            'package'      => $package,
            'created'      => current_time( 'mysql' ),
            'modified'     => current_time( 'mysql' ),
            'expired'      => $subscription->get_date('next_payment', 'site'),
        );

        $log->debug( [__METHOD__, __LINE__], 'Data send to Phoenix API =>' );
		$log->debug( [__METHOD__, __LINE__], $new_client );

        /**
         * Step #5 -> Send $new_client to Phoenix API and Get the UUID
         */

        $api = new \WBSSaaS\PhoenixAPI( $location, $log );
        $response = $api->createTenant( $new_client );
        $log->debug( [__METHOD__, __LINE__], 'Step #5: Response from API after creating new Tenant =>' );
        $log->debug( [__METHOD__, __LINE__], $response );

        if( !isset( $response->data ) ) {
            $log->error( [__METHOD__, __LINE__], 'Error while get the UUID =>' );
            $log->error( [__METHOD__, __LINE__], $response->errors );
        }
        
        /**
         * Step #6 -> We update the Gravity Forms Entry with the UUID got from the API
         * and update the location if the environment is Staging
         */
        $entry[$gravity->form->$current_plan->uuid] = $response->data->uuid;
        $entry[$gravity->form->$current_plan->location] = $location;
        $result = GFAPI::update_entry( $entry );

        if( $result == 1 ) {
            $log->info( [__METHOD__, __LINE__], 'Step #6: Update the GF Entry with the UUID successfully' );
        } else {
            $log->error( [__METHOD__, __LINE__], 'Error while updating the GF Entry with the UUID =>' );
            $log->error( [__METHOD__, __LINE__], $result );
        }

        /**
         * Step #7 -> We create the Cloudflare Records IF location is NOT staging
         * We create 2 records:
         *     1. The alias FQDN: the last 12 digits of the UUID + phoenix-whistleblowing.com
         *     2. Optional: When the customer use one of Phoenix domain name
         */

        if ( $location === 'staging' ) {

            $log->info( [__METHOD__, __LINE__], 'Step #7: No need to create DNS records in Cloudflare because the location is Staging' );

        } else {

            $cloudflare = new \WBSSaaS\CloudflareAPI;
            $alias = $cloudflare->createDNSRecord(
                $location,
                substr( $response->data->uuid, -12 ),
                'phoenix-whistleblowing.com',
                'For client ' . $entry[$gravity->form->$current_plan->name]
            );
            
            if( $alias == false ) {
                $log->error( [__METHOD__, __LINE__], 'Error while creating Alias record in Cloudflare.' );
            } else {
                $log->info( [__METHOD__, __LINE__], 'Step #7: Alias created in Cloudflare' );
                $log->debug( [__METHOD__, __LINE__], 'Location: ' . $location . '. FQDN: ' .  substr( $response->data->uuid, -12 ) . '.phoenix-whistleblowing.com');
            }

            if( !empty( $entry[$gravity->form->$current_plan->subdomain_phoenix] ) ) {

                $record = $cloudflare->createDNSRecord(
                    $location,
                    rgar( $entry, $gravity->form->$current_plan->subdomain_phoenix ),
                    rgar( $entry, $gravity->form->$current_plan->domain_phoenix ),
                    'For client ' . $entry[$gravity->form->$current_plan->name]
                );

                if( $record == false ) {
                    $log->error( [__METHOD__, __LINE__], 'Error while creating new A record in Cloudflare.' );
                    $log->error( [__METHOD__, __LINE__], 'Location: ' . $location);
                    $log->error( [__METHOD__, __LINE__], 'Subdomain: ' . rgar( $entry, $gravity->form->$current_plan->subdomain_phoenix ) );
                    $log->error( [__METHOD__, __LINE__], 'Domain: ' . rgar( $entry, $gravity->form->$current_plan->domain_phoenix ) );
                } else {
                    $log->info( [__METHOD__, __LINE__], 'New A record created in Cloudflare' );
                    $log->debug( [__METHOD__, __LINE__], 'Location: ' . $location);
                    $log->debug( [__METHOD__, __LINE__], 'Subdomain: ' . rgar( $entry, $gravity->form->$current_plan->subdomain_phoenix ) );
                    $log->debug( [__METHOD__, __LINE__], 'Domain: ' . rgar( $entry, $gravity->form->$current_plan->domain_phoenix ) );
                }
            }
        }

        /**
         * Step #8 -> Save everything in DB:Table WBSSAAS_DB_TENANTS
         */
        $tenant = new \WBSSaaS\Tenant( $log );
        $insertion = $tenant->create( array(
            'customer_id'          => $subscription->get_user_id(),
            'tenant_uuid'          => $response->data->uuid,
            'tenant_name'          => $entry[$gravity->form->$current_plan->name],
            'tenant_url'           => strtolower( $url ),
            'tenant_location'      => $location,
            'tenant_settings'      => serialize( $package ),
            'subscription_wc_id'   => $subscription->get_id(),
            'subscription_expired' => $subscription->get_date('next_payment', 'site'),
            'created'              => current_time( 'mysql' ),
            'modified'             => current_time( 'mysql' ),
        ));

        if( $insertion == false ) {
            $log->error( [__METHOD__, __LINE__], 'Error while saved tenant in MySQL =>' );
            $log->error( [__METHOD__, __LINE__], $insertion );
        } else {
            $log->info( [__METHOD__, __LINE__], 'Step #8: New client saved in Database with the ID => ' . $insertion );
        }

        $log->info( [__METHOD__, __LINE__], 'End of WC Cycle. New client created successfully: ' . $entry[$gravity->form->$current_plan->name] );

    } else {

        $log->warning( [__METHOD__, __LINE__], 'This is not a renewal or a Phoenix Plan Subscriptions.' );
    }
}

/**
 * NEW ADDON: Hook triggered when a payment is completed for a Subscription Addons
 * 
 * @link https://woocommerce.com/document/subscriptions/develop/action-reference/#section-1
 * @link https://woocommerce.com/document/subscriptions/develop/functions/
 * 
 * @since 2.0.0
 * 
 * @param   object  $subscription   A WC_Subscription object representing the subscription which has just received a payment.
 * @return  void
 */

add_action( 'woocommerce_subscription_payment_complete', 'wbssaas_wcs_addon_payment_complete', 11, 1 );

function wbssaas_wcs_addon_payment_complete( $subscription )
{
    global $wc;
    global $gravity;

    foreach( $subscription->get_items() as $item_id => $addon_subscription ) {
        $addon_id       = $addon_subscription->get_product_id();
        $addon_quantity = $addon_subscription->get_quantity();

        $product = $addon_subscription->get_product();
        if( $product->is_type('variation') ) {
            $addon_attributes = $product->get_variation_attributes();
        }
    }

    $log = new \WBSSaaS\Logger( null, $subscription->get_user_id() );
    $log->info( [__METHOD__, __LINE__], 'Start hook Addon Payment complete' );

    $all_addons = array();
    foreach( $wc->addon as $addon ) {
        $all_addons[] = $addon;
    }

    if ( in_array( $addon_id, $all_addons ) ) {

        /**
         * Step 1: Get the GF Entry created 
         */
        $order_id = $subscription->get_parent_id();
        $log->debug( [__METHOD__, __LINE__], 'New WC_Order with the ID from Subscription => ' . $order_id );

        $search_criteria = array();
        $search_criteria['field_filters'][] = array( 'key' => 'woocommerce_order_number', 'value' => $order_id );
        $entries = GFAPI::get_entries( $gravity->form->customer->gid, $search_criteria );
        $entry = $entries[0];
        $log->debug( [__METHOD__, __LINE__], 'Get GF Entry created during the checkout =>' );
        $log->debug( [__METHOD__, __LINE__], $entry );

        /**
         * Step 2: Get the record according to the current WC_Subscription from table WBSSAAS_DB_TENANTS
         * return $tenant as object
         */
        global $wpdb;
        $table = WBSSAAS_DB_TENANTS;
        $tenant = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE tenant_uuid = %s", $entry[$gravity->form->customer->dropdown] ) );
        $log->debug( [__METHOD__, __LINE__], 'Get the row from WBS Tenants table =>' );
        $log->debug( [__METHOD__, __LINE__], $tenant );
        $settings = unserialize( $tenant->tenant_settings ); // Array
        $log->debug( [__METHOD__, __LINE__], $settings );

        /**
         * Step 2: Which addon was bought and increment/modify the $tenant
         */

        if( $addon_id == $wc->addon->phone ) {
            $settings['phone'] += $addon_quantity;
        }

        if( $addon_id == $wc->addon->email ) {
            $settings['email'] += $addon_quantity;
        }

        if( $addon_id == $wc->addon->im ) {
            $settings['im'] += $addon_quantity;
        }

        if( $addon_id == $wc->addon->postmail ) {
            $settings['postmail'] += $addon_quantity;
        }

        if( $addon_id == $wc->addon->chat ) {
            $settings['chat'] += $addon_quantity;
        }

        if( $addon_id == $wc->addon->mobileapp ) {
            $settings['mobileapp'] += $addon_quantity;
        }
        
        if( $addon_id == $wc->addon->languages ) {
            $settings['languages'] += $addon_quantity;
        }
        if( $addon_id == $wc->addon->users ) {
            $role = strtolower( $addon_attributes['attribute_role'] );
            $settings['users'][$role] += $addon_quantity;
        }
        
        $log->debug( [__METHOD__, __LINE__], $settings );

        /**
         * Step 3: Send to API
         */
        $options = get_option('wbssaas_options');
        $location = ($options['environment'] == 'staging') ? 'staging' : $tenant->tenant_location;
        $api = new \WBSSaaS\PhoenixAPI( $location, $log );
        $response = $api->updatePackage( $tenant, $settings );
        $log->debug( [__METHOD__, __LINE__], 'Response from API after updating the package =>' );
        $log->debug( [__METHOD__, __LINE__], $response );
        
        if( !isset( $response->data ) ) {
            $log->error( [__METHOD__, __LINE__], 'Error while update expiration date =>' );
            $log->error( [__METHOD__, __LINE__], $response->errors );
        } else {
            $log->info( [__METHOD__, __LINE__], 'Package successfully update on the API.' );
        }

        /**
         * Step 4: Save in MySQL WBSSAAS_DB_TENANTS
         */
        $result = $wpdb->update( 
            WBSSAAS_DB_TENANTS,
            array( 
                'tenant_settings' => serialize( $settings ),
                'modified'        => current_time( 'mysql' )
            ),
            array(
                'id' => $tenant->id,
            )
        );

        if( $result == false ) {
            $log->error( [__METHOD__, __LINE__], 'Error while updating new settings for the tenant => ' );
            $log->error( [__METHOD__, __LINE__], $tenant );
        } else {

            $log->info( [__METHOD__, __LINE__], 'New settings successfully update in Tenant DB' );
        }
    } else {

        $log->warning( [__METHOD__, __LINE__], 'This is not a payment for an Addon.' );

    }
}

/**
 * NEW THEME: Hook triggered when a payment is completed for a Phoenix Theme
 * NOTE: It's not a WC_Subscription. it's WC_Order
 * 
 * @since 2.0.0
 * 
 * @param   integer $order_id   The WC_Order id
 * @return  void
 */

add_action( 'woocommerce_order_status_completed', 'wbssaas_wc_theme_order_completed', 11, 1 );

function wbssaas_wc_theme_order_completed( $order_id )
{
    global $gravity;

    $log = new \WBSSaaS\Logger();
    $log->info( [__METHOD__, __LINE__], 'Start hook Theme Payment Completed' );

    $order = wc_get_order( $order_id );
    // $log->debug( [__METHOD__, __LINE__], $order );

    $is_theme = false;

    foreach( $order->get_items() as $order_items ) {

        $terms = get_the_terms( $order_items['product_id'], 'product_cat' );
        // $log->debug( [__METHOD__, __LINE__], $terms ); // Array of WP_Term Objects

        foreach( $terms as $term ) {

            // $log->debug( [__METHOD__, __LINE__], $term );
            if( $term->term_id == 93 ) {
                $is_theme = true;
            }
        }

        // Get SKU
        $product = wc_get_product( $order_items->get_product_id() );
        $sku = $product->get_sku();
    }

    if( $is_theme ) {

        $log->debug( [__METHOD__, __LINE__], 'Hook Theme: This is a WC_Order with category Theme (id:93)' );

        /**
         * Step 1: Get the GF Entry created 
         */
        $search_criteria = array();
        $search_criteria['field_filters'][] = array( 'key' => 'woocommerce_order_number', 'value' => $order_id );
        $entries = GFAPI::get_entries( $gravity->form->customer->gid, $search_criteria );
        $entry = $entries[0];
        $log->debug( [__METHOD__, __LINE__], 'Get GF Entry created during the checkout =>' );
        $log->debug( [__METHOD__, __LINE__], $entry );

        /**
         * Step 2: Get the record according to the current WC_Subscription from table WBSSAAS_DB_TENANTS
         * return $tenant as object
         */
        global $wpdb;
        $table = WBSSAAS_DB_TENANTS;
        $tenant = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE tenant_uuid = %s", $entry[$gravity->form->customer->dropdown] ) );
        $log->debug( [__METHOD__, __LINE__], 'Get the row from WBS Tenants table =>' );
        $log->debug( [__METHOD__, __LINE__], $tenant );
        $settings = unserialize( $tenant->tenant_settings ); // Array
        $log->debug( [__METHOD__, __LINE__], $settings );

        /**
         * Step 3: Update the settings with the new SKU
         */
        $log->debug( [__METHOD__, __LINE__], 'Current Theme SKU => ' . $sku );
        if( in_array( $sku, $settings['themes'] ) ) {

            $log->warning( [__METHOD__, __LINE__], 'The current SKU is already in the settings => ' );
            $log->warning( [__METHOD__, __LINE__],  $settings['themes'] );

        } else {

            array_push( $settings['themes'], $sku );
            $log->info( [__METHOD__, __LINE__], 'Theme successfully added in the settings => ' );
            $log->info( [__METHOD__, __LINE__], $settings['themes'] );
        }

        /**
         * Step 4: Send to API
         */
        $options = get_option('wbssaas_options');
        $location = ($options['environment'] == 'staging') ? 'staging' : $tenant->tenant_location;
        $api = new \WBSSaaS\PhoenixAPI( $location, $log );
        $response = $api->updatePackage( $tenant, $settings );
        $log->debug( [__METHOD__, __LINE__], 'Response from API after updating the package =>' );
        $log->debug( [__METHOD__, __LINE__], $response );
        
        if( !isset( $response->data ) ) {
            $log->error( [__METHOD__, __LINE__], 'Error while update expiration date =>' );
            $log->error( [__METHOD__, __LINE__], $response->errors );
        } else {
            $log->info( [__METHOD__, __LINE__], 'Theme successfully added in the API.' );
        }

        /**
         * Step 5: Save in MySQL WBSSAAS_DB_TENANTS
         */
        $result = $wpdb->update( 
            WBSSAAS_DB_TENANTS,
            array( 
                'tenant_settings' => serialize( $settings ),
                'modified'        => current_time( 'mysql' )
            ),
            array(
                'id' => $tenant->id,
            )
        );

        if( $result == false ) {
            $log->error( [__METHOD__, __LINE__], 'Error while updating new settings for the tenant => ' );
            $log->error( [__METHOD__, __LINE__], $tenant );
        } else {
            $log->info( [__METHOD__, __LINE__], 'Settings successfully update in Tenant DB with the new theme' );
        }
    } else {

        $log->warning( [__METHOD__, __LINE__], 'This is not a payment for a theme.' );

    }
}

/**
 * RENEWAL SUBSCRIPTION: Triggered when a renewal payment is made for a Phoenix WC_Subscription Plan
 * 
 * @link https://woocommerce.com/document/subscriptions/develop/action-reference/#section-1
 * @link https://docs.gravityforms.com/searching-and-getting-entries-with-the-gfapi/#get-entry
 * 
 * @since 2.0.0
 * 
 * @param object $subscription  A WC_Subscription object representing the subscription which has just received a renewal payment.
 * @param object $last_order    A WC_Order object representing the order created to record the renewal.
 */

add_action( 'woocommerce_subscription_renewal_payment_complete', 'wbsaas_wcs_plan_renewal_complete', 11, 2 );

function wbsaas_wcs_plan_renewal_complete( $subscription, $order ) {

    global $wc;

    $log = new \WBSSaaS\Logger();
    $log->info( [__METHOD__, __LINE__], 'Start hook WC Subscription Renewal Complete' );

    foreach( $subscription->get_items() as $item_id => $product_subscription ) {

        // Get the type of subscription ID
        $parent_subscription_id = $product_subscription->get_product_id();

    }
    $log->debug( [__METHOD__, __LINE__], 'Get the renewed WC_Subscription ID Phoenix Plan => ' . $parent_subscription_id );

    /**
     * We check if the Product ID belongs to the FREE, BASIC or PREMIUM WC_Subscription
     * AND
     * how many renewals was made. If different of 0 it's a WC_Susbcription renewal
     * 
     * @link https://woocommerce.com/document/subscriptions/develop/functions/#section-16
     */
    $renewals = $subscription->get_related_orders( 'ids', 'renewal' );
    $log->debug( [__METHOD__, __LINE__], 'Current WC_Subscription: Get related renewal(s) =>' );
    $log->debug( [__METHOD__, __LINE__], $renewals );

    $all_subscriptions = array();
    foreach( $wc->subscription as $sub ) {
        foreach( $sub as $key => $value ) {
            if( $key == 'id' ) {
                $all_subscriptions[] = $value;
            }
        }
    }

    if ( in_array( $parent_subscription_id, $all_subscriptions) && count( $renewals ) >= 1 ) {

        $log->debug( [__METHOD__, __LINE__], 'This is a renewal' );

        $subscription_id = $subscription->get_id();
        $next_payment    = $subscription->get_date('next_payment', 'site');

        /**
         * Step 1: Get the record according to the current subscription WC from table WBSSAAS_DB_TENANTS
         */
        global $wpdb;
        $table = WBSSAAS_DB_TENANTS;
        $tenant = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE subscription_wc_id = %d", $subscription_id ) );
        $log->debug( [__METHOD__, __LINE__], 'Step #1: Get the row from WBS Tenants table =>' );
        $log->debug( [__METHOD__, __LINE__], $tenant );
 
        /**
         * Step 2: We update the API and then if success we update WBSSAAS_DB_TENANTS
         */
        $options = get_option('wbssaas_options');
        $location = ($options['environment'] == 'staging') ? 'staging' : $tenant->tenant_location;
        $api = new \WBSSaaS\PhoenixAPI( $location, $log );
        $response = $api->updateExpirationDate( array(
            'company_uuid'    => $tenant->tenant_uuid,
            'expiration_date' => $next_payment
        ));
        $log->debug( [__METHOD__, __LINE__], 'Response from API after creating new Tenant =>' );
        $log->debug( [__METHOD__, __LINE__], $response );
        
        if( !isset( $response->data ) ) {
            $log->error( [__METHOD__, __LINE__], 'Error while update expiration date =>' );
            $log->error( [__METHOD__, __LINE__], $response->errors );
        }

        /**
         * Step 3: We update the record on table WBSSAAS_DB_TENANTS
         */

        $result = $wpdb->update( 
            WBSSAAS_DB_TENANTS,
            array( 
                'subscription_expired' => $next_payment,
                'modified'             => current_time( 'mysql' )
            ),
            array(
                'id' => $tenant->id,
            )
        );

        if( $result == false ) {
            $log->error( [__METHOD__, __LINE__], 'Error while updating new expiration date for the tenant => ' );
            $log->error( [__METHOD__, __LINE__], $tenant );
            $log->error( [__METHOD__, __LINE__], 'New expiration date should be => ' . $next_payment );
        } else {

            $log->info( [__METHOD__, __LINE__], 'New expiration date succesfuuly update in Tenant DB' );
        }

        $log->info( [__METHOD__, __LINE__], 'End of hook WC Subscription renewal' );
    }
}

/**
 * UPGRADE SUBSCRIPTION: Triggered when a renewal payment is made for a Phoenix WC_Subscription Plan
 * 
 * @link https://woocommerce.com/document/subscriptions/develop/action-reference/#subscription-switching-actions
 * 
 * @since 2.3.0
 * 
 * @param object $last_order    A WC_Order object representing the order created to record the renewal.
 */
 
add_action('woocommerce_subscriptions_switch_completed', 'wbsaas_wcs_plan_upgrade_completed', 11, 1 );
 
function wbsaas_wcs_plan_upgrade_completed( $order ) {
 
    global $wpdb;
 
    $log = new \WBSSaaS\Logger();
    $log->info( [__METHOD__, __LINE__], 'Start hook WC Subscription Switch (Upgrade) Complete' );
 
    $log->debug( [__METHOD__, __LINE__], 'Order created for the switch => ' );
    $log->debug( [__METHOD__, __LINE__], $order );
 
    /**
     * Step 1: Get all subscriptions linked to this switch order.
     * After a plan upgrade, WC Subscriptions creates a new WC_Subscription
     * with order_type 'switch'. We need to find that new subscription.
     */
    $subscriptions = wcs_get_subscriptions_for_order( $order->get_id(), [ 'order_type' => 'switch' ] );
 
    if ( empty( $subscriptions ) ) {
        $log->warning( [__METHOD__, __LINE__], 'No subscriptions found for this switch order. Aborting.' );
        return;
    }
 
    $table = WBSSAAS_DB_TENANTS;
 
    foreach ( $subscriptions as $new_sub ) {
 
        $user_id    = $new_sub->get_user_id();
        $new_sub_id = $new_sub->get_id();
        $log->debug( [__METHOD__, __LINE__], 'Processing new subscription ID => ' . $new_sub_id . ' for user => ' . $user_id );
 
        /**
         * Step 2: Find the new tenant row that was just created for this upgraded subscription.
         * The new row is written by wbssaas_wcs_plan_payment_complete with subscription_wc_id = $new_sub_id.
         */
        $new_tenant = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table WHERE subscription_wc_id = %d AND customer_id = %d LIMIT 1",
            $new_sub_id,
            $user_id
        ) );
 
        if ( ! $new_tenant ) {
            $log->warning( [__METHOD__, __LINE__], 'New tenant row not found for subscription => ' . $new_sub_id . '. Aborting for this sub.' );
            continue;
        }
 
        $log->debug( [__METHOD__, __LINE__], 'New tenant row found => ' );
        $log->debug( [__METHOD__, __LINE__], $new_tenant );
 
        /**
         * Step 3: Find all OLD rows for the same organisation (same tenant_name, same user, different row).
         * These rows hold previously purchased addon quantities.
         */
        $old_tenants = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table WHERE customer_id = %d AND tenant_name = %s AND id != %d",
            $user_id,
            $new_tenant->tenant_name,
            $new_tenant->id
        ) );
 
        $log->debug( [__METHOD__, __LINE__], 'Old tenant rows found => ' . count( $old_tenants ) );
 
        if ( empty( $old_tenants ) ) {
            $log->info( [__METHOD__, __LINE__], 'No old rows to merge addons from. Nothing to do.' );
            continue;
        }
 
        /**
         * Step 4: Start from the new row's settings (Premium default package) and
         * merge addon quantities from old rows by taking the MAX value per channel.
         *
         * Why MAX and not SUM:
         * Each old row already stores cumulative totals (base + addons bought at that time).
         * Taking MAX across rows ensures we never lose an addon that was written to any row.
         */
        $new_settings = unserialize( $new_tenant->tenant_settings );
        if ( ! is_array( $new_settings ) ) {
            $log->error( [__METHOD__, __LINE__], 'Could not unserialize new tenant settings. Aborting merge.' );
            continue;
        }
 
        $log->debug( [__METHOD__, __LINE__], 'New tenant settings before merge => ' );
        $log->debug( [__METHOD__, __LINE__], $new_settings );
 
        foreach ( $old_tenants as $old_tenant ) {
            $old_settings = @unserialize( $old_tenant->tenant_settings );
            if ( ! is_array( $old_settings ) ) continue;
 
            // Numeric channels: keep the highest value across all rows
            foreach ( [ 'phone', 'email', 'im', 'postmail', 'chat', 'mobileapp', 'languages' ] as $channel ) {
                $old_val = (int) ( $old_settings[ $channel ] ?? 0 );
                $new_val = (int) ( $new_settings[ $channel ] ?? 0 );
                if ( $old_val > $new_val ) {
                    $new_settings[ $channel ] = $old_val;
                    $log->debug( [__METHOD__, __LINE__], 'Merged channel ' . $channel . ': ' . $new_val . ' -> ' . $old_val );
                }
            }
 
            // User roles: keep the highest per role
            foreach ( [ 'manager', 'operator', 'agent' ] as $role ) {
                $old_val = (int) ( $old_settings['users'][ $role ] ?? 0 );
                $new_val = (int) ( $new_settings['users'][ $role ] ?? 0 );
                if ( $old_val > $new_val ) {
                    $new_settings['users'][ $role ] = $old_val;
                    $log->debug( [__METHOD__, __LINE__], 'Merged user role ' . $role . ': ' . $new_val . ' -> ' . $old_val );
                }
            }
 
            // Themes: merge all unique SKUs from all rows
            if ( ! empty( $old_settings['themes'] ) && is_array( $old_settings['themes'] ) ) {
                $existing = $new_settings['themes'] ?? [];
                $merged   = array_unique( array_merge( $existing, $old_settings['themes'] ) );
                if ( count( $merged ) > count( $existing ) ) {
                    $new_settings['themes'] = array_values( $merged );
                    $log->debug( [__METHOD__, __LINE__], 'Merged themes => ' );
                    $log->debug( [__METHOD__, __LINE__], $new_settings['themes'] );
                }
            }
        }
 
        $log->debug( [__METHOD__, __LINE__], 'New tenant settings after merge => ' );
        $log->debug( [__METHOD__, __LINE__], $new_settings );
 
        /**
         * Step 5: Push the merged settings to the Phoenix API so the live platform reflects the addons.
         */
        $options  = get_option( 'wbssaas_options' );
        $location = ( $options['environment'] == 'staging' ) ? 'staging' : $new_tenant->tenant_location;
        $api      = new \WBSSaaS\PhoenixAPI( $location, $log );
        $response = $api->updatePackage( $new_tenant, $new_settings );
        $log->debug( [__METHOD__, __LINE__], 'Response from API after updating package with merged addons =>' );
        $log->debug( [__METHOD__, __LINE__], $response );
 
        if ( ! isset( $response->data ) ) {
            $log->error( [__METHOD__, __LINE__], 'API error while pushing merged settings. DB will still be updated.' );
        } else {
            $log->info( [__METHOD__, __LINE__], 'Merged addon settings successfully pushed to Phoenix API.' );
        }
 
        /**
         * Step 6: Save the merged settings back to the new tenant row in DB.
         */
        $result = $wpdb->update(
            WBSSAAS_DB_TENANTS,
            [
                'tenant_settings' => serialize( $new_settings ),
                'modified'        => current_time( 'mysql' ),
            ],
            [ 'id' => $new_tenant->id ]
        );
 
        if ( $result === false ) {
            $log->error( [__METHOD__, __LINE__], 'DB error while saving merged settings for tenant id => ' . $new_tenant->id );
        } else {
            $log->info( [__METHOD__, __LINE__], 'Merged addon settings saved to DB for tenant id => ' . $new_tenant->id );
        }
    }
 
    $log->info( [__METHOD__, __LINE__], 'End of hook WC Subscription Switch (Upgrade) Complete' );
}
 
/**
 * Remove the Order Notes Field and “Order Notes” title from the Checkout Page
 * 
 * @since 1.0.0
 * 
 * @link https://www.businessbloomer.com/woocommerce-remove-order-notes-checkout-page/
 * 
 */
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );