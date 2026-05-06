<?php

namespace WBSSaaS;

use DateInterval;
use DateTime;
use WC_Subscription;

/**
 * Class Tenant
 *
 * @since 2.0.0
 *
 */

class Tenant
{
    private ?\WBSSaaS\Logger $log;

    public ?\WC_Subscription $subscription;

    public $notification = array();

    public $tenants = array();

    private object $config;

    private string $current_lang;

    public function __construct( object $log = null )
    {
        global $app;

        $this->config = $app;
        $this->current_lang = ( apply_filters( 'wpml_current_language', NULL ) == apply_filters('wpml_default_language', null  ) ) ? '' : '/' . apply_filters( 'wpml_current_language', null );

        if( $log ) {
            $this->log = $log;
        } else {
            $this->log = new \WBSSaaS\Logger( $this );
        }
    }

    public function create( array $data ): int|false
    {
        global $wpdb;

        $wpdb->insert(
            WBSSAAS_DB_TENANTS,
            $data
        );

        return $wpdb->insert_id;

    }

    public function fetchAllTenants(): array|null
    {
        global $wpdb;

        $query = "
            SELECT *
            FROM " . WBSSAAS_DB_TENANTS;
        
        return $wpdb->get_results( $query, OBJECT );
    }

    public function fetchAllTenantsByCustomerID( int $customer_id ): array|null
    {
        global $wpdb;

        $query = "
            SELECT *
            FROM " . WBSSAAS_DB_TENANTS . "
            WHERE customer_id = " . $customer_id;
        
        return $wpdb->get_results( $query, OBJECT );
    }

    public function fetchTenantByID( int $id ): object|null
    {
        global $wpdb;

        $query = "
            SELECT *
            FROM " . WBSSAAS_DB_TENANTS . "
            WHERE id = " . $id;
        
        return $wpdb->get_row( $query, OBJECT );
    }

    public function fetchTenantByUUID( string $uuid ): object|null
    {
        global $wpdb;

        $query = "
            SELECT *
            FROM " . WBSSAAS_DB_TENANTS . "
            WHERE tenant_uuid = '" . $uuid . "'";
        
        return $wpdb->get_row( $query, OBJECT );
    }

    public function fetchTenantBySubscriptionID( int $subscription_id ): object|null
    {
        global $wpdb;

        $query = "
            SELECT *
            FROM " . WBSSAAS_DB_TENANTS . "
            WHERE subscription_wc_id = " . $subscription_id;
        
        return $wpdb->get_row( $query, OBJECT );
    }
    
    public function newNotification( string $level, string $message ): string
    {
        $shortcode = '';

        $shortcode .= match( $level ) {

            'info'    => '[vc_message color="info"]',
            'warning' => '[vc_message color="warning" message_box_color="warning" icon_fontawesome="fas fa-exclamation-triangle"]',
            'success' => '[vc_message color="success" message_box_color="success" icon_fontawesome="fas fa-check"]',
            'error'   => '[vc_message color="danger" message_box_color="danger" icon_fontawesome="fas fa-times"]',
        };

        return $this->notification[] = $shortcode . $message . '[/vc_message]';
    }

    public function prepareTenants( int $customer_id ): void
    {
        $tenants = $this->fetchAllTenantsByCustomerID( $customer_id );

        foreach( $tenants as $key => $tenant ) {

            $this->tenants[$key] = array(
                'customer_id'  => $tenant->customer_id,
                'uuid'         => $tenant->tenant_uuid,
                'name'         => $tenant->tenant_name,
                'url'          => $tenant->tenant_url,
                'location'     => $tenant->tenant_location,
                'settings'     => unserialize( $tenant->tenant_settings ),
                'subscription' => new WC_Subscription( $tenant->subscription_wc_id ),
                'expired'      => $tenant->subscription_expired,
                'created'      => $tenant->created,
                'modified'     => $tenant->modified,
            );

            // Check if the subscription is cancelled: skip this iteration and move to the next item
            if ( $this->checkWCisCancelled( $this->tenants[$key]['subscription'] ) ) {
                continue;
            }

            // Check WC Status is active
            if( ! $this->checkWCisActive( $this->tenants[$key]['subscription'] ) ) {

                $this->log->warning( [__METHOD__, __LINE__], 'Tenant WC Status is not active => WC ID ' . $this->tenants[$key]['subscription']->get_status() );

                $message = sprintf( __( 'The subscription\'s status for <strong>%s</strong> is %s. Please review your subscription and orders.', 'wbs-saas-plugin' ), $tenant->tenant_name, $this->tenants[$key]['subscription']->get_status() );
                $this->newNotification( 'warning', $message );
            }

            // Check if Next Payment is less than 1 week
            if( ! $this->checkNextPayment( $this->tenants[$key]['subscription']) ) {

                $next = date_create( $this->tenants[$key]['subscription']->get_date( 'next_payment', 'site' ) );
                $this->log->warning( [__METHOD__, __LINE__], 'Tenant Next Payment is less than 7 days => ' . date_format( $next,"Y/m/d H:i") );

                $message = sprintf( __( 'The next payment for <strong>%s</strong> will be processed on %s.', 'wbs-saas-plugin' ),  $tenant->tenant_name, date_format( $next, 'l j F Y H:i' ) );
                $this->newNotification( 'warning', $message );
            }

            // Check if expiration is in a month
            if( ! $this->checkExpiration( $this->tenants[$key]['subscription']) ) {

                $expire = date_create($this->tenants[$key]['subscription']->get_date( 'start', 'site' ) );
                $expire = $expire->add( new DateInterval( 'P1Y') );

                $this->log->warning( [__METHOD__, __LINE__], 'The subscription will be expired in less than 30 days => ' . date_format( $expire,"Y/m/d H:i") );

                $message = sprintf( __( 'The subscription for <strong>%s</strong> will be expired on %s.', 'wbs-saas-plugin' ),  $tenant->tenant_name, date_format( $expire, 'l j F Y H:i' ) );
                $this->newNotification( 'warning', $message );
            }
            
            // Check IP address for SaaS URL
            // if( ! $this->checkSaasURL( $tenant->tenant_url, $tenant->tenant_location ) ) {

            //     $this->log->warning( [__METHOD__, __LINE__], 'Tenant URL does not match with the chosen Server location IP => ' . $tenant->tenant_url );
            //     $this->log->warning( [__METHOD__, __LINE__], 'Location recorded => ' . $tenant->tenant_location );
                
            //     $message = sprintf( __( 'We noticed the address <a href="%s">%s</a> does not match with the IP address %s yet for <strong>%s</strong>.', 'wbs-saas-plugin' ), $tenant->tenant_url, $tenant->tenant_url, $this->config->location->{$tenant->tenant_location}->ip, $tenant->tenant_name );
            //     $this->newNotification( 'error', $message );
            // }

            // Check if migration has performed (Laravel)
            if( ! $this->checkMigration( $tenant->tenant_uuid, $tenant->tenant_location ) ) {

                $wp_user = get_user_by( 'id', $tenant->customer_id );
                $wp_email = password_hash( $wp_user->user_email ,PASSWORD_DEFAULT );

                $this->log->warning( [__METHOD__, __LINE__], 'Tenant has not migrated => ' . $tenant->tenant_name . ' (' . $tenant->tenant_uuid . ')' );
                $message = sprintf( __( '<p><strong>%s setup is not complete.</strong><br>', 'wbs-saas-plugin' ), $tenant->tenant_name );
                $message .= __( 'To utilize your dedicated website, you must first initiate the Basic Setup Wizard and create your account as administrator:</p>', 'wbs-saas-plugin' );
                $message .= '<center><a href="' . $tenant->tenant_url . '/clients/new?u=' . $tenant->tenant_uuid . '&m=' . $wp_email . '" class="woocommerce-button button view" target="_blank">' . __( 'Go to Basic Setup Wizard', 'wbs-saas-plugin' ) . '</a></center>';

                $this->newNotification( 'error', $message );
            }

        }

        // $this->log->debug( [__METHOD__, __LINE__], $this->tenants );
    }
    
    private function checkSaasURL( string $url, string $location ): bool
    {
        $domain = parse_url( $url, PHP_URL_HOST );
        $ip = gethostbyname( $domain );

        return ( $ip == $this->config->location->{$location}->ip ) ? true : false;
    }

    private function checkWCisActive( object $subscription ): bool
    {
        return ( $subscription->get_status() == 'active' ) ? true : false;
    }

    private function checkWCisCancelled( object $subscription ): bool
    {
        return ( $subscription->get_status() == 'cancelled' ) ? true : false;
    }

    private function checkNextPayment( object $subscription ): bool
    {
        $now = new DateTime();
        $next = date_create( $subscription->get_date( 'next_payment', 'site' ) );
        $interval = $now->diff( $next );

        return ( $interval->days > 7 ) ? true : false;
    }

    private function checkExpiration( object $subscription ): bool
    {
        $now = new DateTime();
        $expire = date_create( $subscription->get_date( 'start', 'site' ) );
        $expire = $expire->add( new DateInterval( 'P1Y') );

        $interval = $now->diff( $expire );

        return ( $interval->days > 30 ) ? true : false;

    }

    private function checkMigration( string $uuid, string $location ): bool
    {
        $api = new \WBSSaaS\PhoenixAPI( $location, $this->log );
        $is_migrated = $api->checkMigration( $uuid );
        // $this->log->debug( [__METHOD__, __LINE__], $is_migrated );

        return ( $is_migrated->data->migrate_status ) ? true : false;
    }

    /**
     * check if the host is avilable or no
     *
     * @param string $host
     * @return boolean  True if available. False if domain name is taken
     */
    public function verifyDomainAvailability( string $host ): bool
    {
        $host = parse_url( $host, PHP_URL_HOST );

        if( is_null( $host ) ) {

            $this->log->error( [__METHOD__, __LINE__], 'The host / URL is not valid: ' . $host );
            return false;
        }

        $tenants = $this->fetchAllTenants();

        if ( empty( $tenants ) ) {
            $this->log->debug( [__METHOD__, __LINE__], 'No existing tenants found — returning available' );
            return true;
        }

        foreach( $tenants as $tenant ) {
            if ( $tenant->tenant_url === 'https://' . $host ) {
                $this->log->info( [__METHOD__, __LINE__], 'Domain already used => ' . $host );
                return false;
            }
        }

        return true;

    }

    /**
     * All Function to display in the frontend
     */

    private function startRow( string $label ): string
    {
        $shortcode = '';
        $shortcode .= '<div class="wbssaas-tenants-row">';
        $shortcode .= '<div class="wbssaas-tenants-column wbssaas-tenants-left">';
        $shortcode .= '<strong>' . __( $label, 'wbs-saas-plugin' ) . ':</strong>';
        $shortcode .= '</div>'; // End of .wbssaas-tenants-column .wbssaas-tenants-left
        $shortcode .= '<div class="wbssaas-tenants-column wbssaas-tenants-right">';
        
        return $shortcode;
    }

    private function endRow(): string
    {
        $shortcode = '';
        $shortcode .= '</div>'; // End of .wbssaas-tenants-column .wbssaas-tenants-right
        $shortcode .= '</div>'; // End of .wbssaas-tenants-row

        return $shortcode;
    }

    public function display(): string
    {
        $shortcode = '';

        // Opening Accordion
        $shortcode .= '[vc_tta_accordion color="sandy-brown" gap="5" c_icon="chevron" active_section="1" no_fill="true" collapsible_all="true"]';

        foreach( $this->tenants as $tenant ) {

            // Check if the subscription is cancelled: skip this iteration and move to the next item
            if ( $this->checkWCisCancelled( $tenant['subscription'] ) ) {
                continue;
            }

            // Opening Section
            $shortcode .= '[vc_tta_section title="' . $tenant['name']. '" tab_id="' . $tenant['uuid'] . '"]';
            $shortcode .= '[vc_column_text]';

            /**
             * Content Section:
             * 
             * @see ./assets/css/wbs-saas.css
             * @link https://www.w3schools.com/howto/howto_css_two_columns.asp
             */
            
            $shortcode .= '<div class="wbssaas-tenants-container">';
            $shortcode .= $this->displayStatus( $tenant['subscription'] );
            $shortcode .= $this->displaySubscription( $tenant['subscription'] );
            $shortcode .= $this->displayCreation( $tenant['subscription']->get_date( 'start', 'site' ) );
            if ( $this->checkWCisActive( $tenant['subscription'] ) ) {
                $shortcode .= $this->displayNextPayment( $tenant['subscription'] );
            }
            $shortcode .= $this->displayExpiration( $tenant['subscription'] );
            $shortcode .= $this->displaySaasURL( $tenant['url'] );
            $shortcode .= $this->displaySaasLocation( $tenant['location'] );
            $shortcode .= $this->displayChannels( $tenant['settings'] );
            $shortcode .= $this->displayAccounts( $tenant['settings']['users'] );
            $shortcode .= $this->displayLanguages( $tenant['settings']['languages'] );
            $shortcode .= $this->displayThemes( $tenant['settings']['themes'] );

            $shortcode .= '</div>'; // End of .wbssaas-tenants-container

            // Closing Section
            $shortcode .= '[/vc_column_text]';
            $shortcode .= '[/vc_tta_section]';
        }

        // Closing Accordion
        $shortcode .= '[/vc_tta_accordion]';

        return $shortcode;
    }

    private function displayStatus( object $subscription ): string
    {
        $shortcode = '';
        $shortcode .= $this->startRow( 'Status' );
        $shortcode .= ucwords( $subscription->get_status() );
        $shortcode .= $this->endRow();

        return $shortcode;
    }

    private function displaySubscription( object $subscription ): string
    {
        $shortcode = '';
        $shortcode .= $this->startRow( 'Subscription' );

        foreach( $subscription->get_items() as $item_id => $product_subscription ) {
            $product_name = $product_subscription->get_name();
        }

        $shortcode .= $product_name;
        $shortcode .= ' <span class="wbssaas-tenants-btn-inline"><a href="'. $this->current_lang . '/my-account/view-subscription/' . $subscription->get_id() . '" target="_blank">View</a></span>';
        $shortcode .= $this->endRow();

        return $shortcode;
    }

    private function displayCreation( string $creation ): string
    {
        $date = date_create( $creation );

        $shortcode = '';
        $shortcode .= $this->startRow( 'Creation date' );
        $shortcode .= date_format( $date, 'l j F Y' );
        $shortcode .= $this->endRow();

        return $shortcode;

    }

    private function displayNextPayment( object $subscription ): string
    {
        $now = new DateTime();
        $next = date_create( $subscription->get_date( 'next_payment', 'site' ) );
        $interval = $now->diff( $next );

        $shortcode = '';
        $shortcode .= $this->startRow( 'Next payment date' );
        $shortcode .= __( 'In ', 'wbs-saas-plugin' ) . $interval->days . __( ' days (', 'wbs-saas-plugin' ) . date_format( $next, 'j F Y' ) . ') ';
        $shortcode .= '<span class="wbssaas-tenants-btn-inline"><a href="'. $this->current_lang . '/my-account/?subscription_renewal_early=' . $subscription->get_id() . '&subscription_renewal=true" target="_blank">Renew now</a></span>';
        $shortcode .= $this->endRow();

        return $shortcode;
    }

    private function displayExpiration( object $subscription ): string
    {
        $expire = date_create( $subscription->get_date( 'start', 'site' ) );
        $expire = $expire->add( new DateInterval( 'P1Y') );

        $shortcode = '';
        $shortcode .= $this->startRow( 'Expiration date' );
        $shortcode .= date_format( $expire, 'l j F Y' );
        $shortcode .= $this->endRow();

        return $shortcode;
    }

    private function displaySaasURL( string $url ): string
    {
        $shortcode = '';
        $shortcode .= $this->startRow( 'Website' );
        $shortcode .= '<a href="' . $url . '" target="__blank">' . $url. '</a>';
        $shortcode .= $this->endRow();

        return $shortcode;
    }

    private function displaySaasLocation( string $location ): string
    {
        $shortcode = '';
        $shortcode .= $this->startRow( 'Server Location' );
        $shortcode .= $this->config->location->{$location}->name;
        $shortcode .= $this->endRow();

        return $shortcode;
    }

    private function displayChannels( array $settings ): string
    {
        $shortcode = '';
        $shortcode .= $this->startRow( 'Channels' );

        // Webform
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-left">';
        $shortcode .= __( 'Web Form', 'wbs-saas-plugin' );
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-left
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-right">';
        $shortcode .= ': Choice of ' . implode( ', ', $settings['webforms'] ) . ' questionnaire';
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-right

        // Telephone
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-left">';
        $shortcode .= __( 'Telephone', 'wbs-saas-plugin' );
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-left
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-right">';
        if( $settings['phone'] >= 999 ) {
            $phones = __( 'Unlimited phone numbers', 'wbs-saas-plugin' );
        } elseif( $settings['phone'] == 1 ) {
            $phones = __( 'Only 1 phone number', 'wbs-saas-plugin' );
        } elseif( $settings['phone'] == 0 ) {
            $phones = __( 'Not included in your subscription', 'wbs-saas-plugin' );
        } else {
            $phones = __( 'Up to ', 'wbs-saas-plugin' ) . $settings['phone'] . __( ' phone numbers', 'wbs-saas-plugin' );
        }
        $shortcode .= ': ' . $phones;
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-right

        // Email
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-left">';
        $shortcode .= __( 'Email', 'wbs-saas-plugin' );
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-left
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-right">';
        if( $settings['email'] >= 999 ) {
            $emails = __( 'Unlimited secured inboxes', 'wbs-saas-plugin' );
        } elseif( $settings['email'] == 1 ) {
            $emails = __( 'Only 1 secure inbox', 'wbs-saas-plugin' );
        } elseif( $settings['email'] == 0 ) {
            $emails = __( 'Not included in your subscription', 'wbs-saas-plugin' );
        } else {
            $emails = __( 'Up to ', 'wbs-saas-plugin' ) . $settings['email'] . __( ' secured inboxes', 'wbs-saas-plugin' );
        }
        $shortcode .= ': ' . $emails;
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-right

        // IM
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-left">';
        $shortcode .= __( 'Instant Messaging', 'wbs-saas-plugin' );
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-left
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-right">';
        if( $settings['im'] >= 999 ) {
            $ims = __( 'Unlimited IMs', 'wbs-saas-plugin' );
        } elseif( $settings['im'] == 1 ) {
            $ims = __( 'Only 1 IM', 'wbs-saas-plugin' );
        } elseif( $settings['im'] == 0 ) {
            $ims = __( 'Not included in your subscription', 'wbs-saas-plugin' );
        } else {
            $ims = __( 'Up to ', 'wbs-saas-plugin' ) . $settings['im'] . __( ' IMs', 'wbs-saas-plugin' );
        }
        $shortcode .= ': ' . $ims;
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-right

        // Postmail
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-left">';
        $shortcode .= __( 'Post Mail', 'wbs-saas-plugin' );
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-left
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-right">';
        if( $settings['postmail'] >= 999 ) {
            $postmails = __( 'Unlimited post addresses', 'wbs-saas-plugin' );
        } elseif( $settings['postmail'] == 1 ) {
            $postmails = __( 'Only 1 post address', 'wbs-saas-plugin' );
        } elseif( $settings['postmail'] == 0 ) {
            $postmails = __( 'Not included in your subscription', 'wbs-saas-plugin' );
        } else {
            $postmails = __( 'Up to ', 'wbs-saas-plugin' ) . $settings['postmail'] . __( ' post addresses', 'wbs-saas-plugin' );
        }
        $shortcode .= ': ' . $postmails;
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-right

        // Chat Online
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-left">';
        $shortcode .= __( 'Chat Online', 'wbs-saas-plugin' );
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-left
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-right">';
        if( $settings['chat'] >= 999 ) {
            $rooms = __( 'Unlimited rooms', 'wbs-saas-plugin' );
        } elseif( $settings['chat'] == 0 ) {
            $rooms = __( 'Not included in your subscription', 'wbs-saas-plugin' );
        } else {
            $rooms = __( 'Up to ', 'wbs-saas-plugin' ) . $settings['chat'] . __( ' rooms', 'wbs-saas-plugin' );
        }
        $shortcode .= ': ' . $rooms;
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-right

        // Mobile App
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-left">';
        $shortcode .= __( 'Mobile App', 'wbs-saas-plugin' );
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-left
        $shortcode .= '<div class="wbssaas-tenants-sub-column wbssaas-tenants-right">';
        $mobileapp = ( $settings['mobileapp'] == 1) ? __( 'Active', 'wbs-saas-plugin' ) : __( 'Not included in your subscription', 'wbs-saas-plugin' );
        $shortcode .= ': ' . $mobileapp;
        $shortcode .= '</div>'; // End of .wbssaas-tenants-sub-column .wbssaas-tenants-right

        // End of Channels
        $shortcode .= '</div>'; // End of .wbssaas-tenants-column .wbssaas-tenants-right
        $shortcode .= '<div class="wbssaas-tenants-clear"></div>';

        // Button Buy extra channels
        $shortcode .= '[vc_empty_space height="2em"]';
        $shortcode .= '[vc_btn title="' . __( 'Buy extra channel', 'wbs-saas-plugin' ) . '" color="sandy-brown" size="lg" align="center" i_icon_fontawesome="fas fa-plus" add_icon="true" link="url:'. $this->current_lang . '%2Fproduct-category%2Fadd-on%2F"]';
        $shortcode .= '[vc_empty_space height="0"]';

        $shortcode .= '</div>'; // End of .wbssaas-tenants-row

        return $shortcode;
    }

    private function displayAccounts( array $accounts ): string
    {
        $shortcode = '';
        $shortcode .= $this->startRow( 'User Accounts' );

        // Managers
        if( $accounts['manager'] >= 999 ) {
            $managers = __( 'Unlimited Managers', 'wbs-saas-plugin' );
        } elseif( $accounts['manager'] == 1 ) {
            $managers = __( 'Only 1 account as Manager', 'wbs-saas-plugin' );
        } elseif( $accounts['manager'] == 0 ) {
            $managers = __( 'No account as Manager', 'wbs-saas-plugin' );
        } else {
            $managers = __( 'Up to ', 'wbs-saas-plugin' ) . $accounts['manager'] . __( ' accounts as Manager', 'wbs-saas-plugin' );
        }
        $shortcode .= $managers . '<br>';

        // Operators
        if( $accounts['operator'] >= 999 ) {
            $operators = __( 'Unlimited Operators', 'wbs-saas-plugin' );
        } elseif( $accounts['operator'] == 1 ) {
            $operators = __( 'Only 1 account as Operator', 'wbs-saas-plugin' );
        } elseif( $accounts['operator'] == 0 ) {
            $operators = __( 'No account as Operator', 'wbs-saas-plugin' );
        } else {
            $operators = __( 'Up to ', 'wbs-saas-plugin' ) . $accounts['operator'] . __( ' accounts as Operator', 'wbs-saas-plugin' );
        }
        $shortcode .= $operators . '<br>';

        // Agents
        if( $accounts['agent'] >= 999 ) {
            $agents = __( 'Unlimited Agents', 'wbs-saas-plugin' );
        } elseif( $accounts['agent'] == 1 ) {
            $agents = __( 'Only 1 account as Agent', 'wbs-saas-plugin' );
        } elseif( $accounts['agent'] == 0 ) {
            $agents = __( 'No account as Agent', 'wbs-saas-plugin' );
        } else {
            $agents = __( 'Up to ', 'wbs-saas-plugin' ) . $accounts['agent'] . __( ' accounts as Agent', 'wbs-saas-plugin' );
        }
        $shortcode .= $agents;

        // End of User Accounts
        $shortcode .= '</div>'; // End of .wbssaas-tenants-column .wbssaas-tenants-right
        $shortcode .= '<div class="wbssaas-tenants-clear"></div>';

        // Button Buy extra channels
        $shortcode .= '[vc_empty_space height="2em"]';
        $shortcode .= '[vc_btn title="' . __( 'Buy extra user account', 'wbs-saas-plugin' ) . '" color="sandy-brown" size="lg" align="center" i_icon_fontawesome="fas fa-plus" add_icon="true" link="url:'. $this->current_lang . '%2Fproduct%2Fuser-account%2F"]';
        $shortcode .= '[vc_empty_space height="0"]';

        $shortcode .= '</div>'; // End of .wbssaas-tenants-row

        return $shortcode;
    }

    private function displayLanguages( int $languages ): string
    {
        $shortcode = '';
        $shortcode .= $this->startRow( 'Languages' );
        $languages = ( $languages == 1 ) ? __( 'Only 1 language', 'wbs-saas-plugin' ) : __( 'Up to ', 'wbs-saas-plugin' ) . $languages . __( ' languages', 'wbs-saas-plugin' );
        $shortcode .= $languages;

        // End of Languages
        $shortcode .= '</div>'; // End of .wbssaas-tenants-column .wbssaas-tenants-right
        $shortcode .= '<div class="wbssaas-tenants-clear"></div>';

        // Button buy extra languages
        $shortcode .= '[vc_empty_space height="2em"]';
        $shortcode .= '[vc_btn title="' . __( 'Buy extra language', 'wbs-saas-plugin' ) . '" color="sandy-brown" size="lg" align="center" i_icon_fontawesome="fas fa-plus" add_icon="true" link="url:'. $this->current_lang . '%2Fproduct%2Flanguage%2F"]';
        $shortcode .= '[vc_empty_space height="0"]';

        $shortcode .= '</div>'; // End of .wbssaas-tenants-row

        return $shortcode;
    }

    private function displayThemes( array $themes ): string
    {
        $shortcode = '';
        $shortcode .= $this->startRow( 'Themes' );
        foreach( $themes as $sku ) {
            $product_id = wc_get_product_id_by_sku( $sku );
            $product = wc_get_product( $product_id );
            $shortcode .= '<a href="'. $this->current_lang . '/product/' . $sku . '/">' . $product->get_name() . '</a>, ';
        }

        // End of Themes
        $shortcode .= '</div>'; // End of .wbssaas-tenants-column .wbssaas-tenants-right
        $shortcode .= '<div class="wbssaas-tenants-clear"></div>';

        // Button buy extra themes
        $shortcode .= '[vc_empty_space height="2em"]';
        $shortcode .= '[vc_btn title="' . __( 'Buy extra theme', 'wbs-saas-plugin' ) . '" color="sandy-brown" size="lg" align="center" i_icon_fontawesome="fas fa-plus" add_icon="true" link="url:'. $this->current_lang . '%2Fproduct-category%2Ftheme%2F"]';
        $shortcode .= '[vc_empty_space height="0"]';

        $shortcode .= '</div>'; // End of .wbssaas-tenants-row


        return $shortcode;
    }
}

/**
 * Class TenantWPTable
 * 
 * @link Source: https://github.com/Veraxus/wp-list-table-example
 * 
 * @since 2.0.0
 * 
 */

if ( ! class_exists( 'WP_List_Table' ) ) {

    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

}

class TenantWPTable extends \WP_List_Table
{
    public function __construct () {
        // Set parent defaults.
        parent::__construct( array(
            'singular' => 'WBS SaaS Tenant',  // Singular name of the listed records.
            'plural'   => 'WBS SaaS Tenants', // Plural name of the listed records.
            'ajax'     => true,               // Set to true to be able to refresh the form after submit
        ) );
    }

    /**
     * Get a list of columns. The format is:
     * 'internal-name' => 'Title'
     * 
     * @see wbssaas_install_db()
     */
    public function get_columns() {
        $columns = array(
            'id'                   => _x( 'ID', 'Column label', 'wp-wbssaas-list-table' ),
            'customer_id'          => _x( 'Customer', 'Column label', 'wp-wbssaas-list-table' ),
            'subscription_wc_id'   => _x( 'Subscription', 'Column label', 'wp-wbssaas-list-table' ),
            'tenant_name'          => _x( 'Name', 'Column label', 'wp-wbssaas-list-table' ),
            'tenant_url'           => _x( 'URL', 'Column label', 'wp-wbssaas-list-table' ),
            'tenant_location'      => _x( 'Location', 'Column label', 'wp-wbssaas-list-table' ),
            'tenant_uuid'          => _x( 'UUID', 'Column label', 'wp-wbssaas-list-table'),
            'subscription_expired' => _x( 'Expiration Date', 'Column label', 'wp-wbssaas-list-table' ),
            'created'              => _x( 'Created', 'Column label', 'wp-wbssaas-list-table' ),
            'modified'             => _x( 'Modifed', 'Column label', 'wp-wbssaas-list-table' ),
        );

        return $columns;
    }

    /**
     * Get a list of sortable columns. The format is:
     * 'internal-name' => 'orderby'
     * or
     * 'internal-name' => array( 'orderby', true )
     * The second format will make the initial sorting order be descending
     */
    protected function get_sortable_columns() {
        $sortable_columns = array(
            'id'                   => array( 'id', true),
            'subscription_wc_id'   => array( 'subscription_wc_id', false),
            'tenant_name'          => array( 'tenant_name', false ),
            'tenant_location'      => array( 'tenant_location', false ),
            'created'              => array( 'created', false ),
            'modified'             => array( 'modified', false ),
            'subscription_expired' => array( 'subscription_expired', false )
        );

        return $sortable_columns;
    }

    /**
     * Get default column value.
     *
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title()
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as
     * possible.
     *
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     *
     * For more detailed insight into how columns are handled, take a look at
     * WP_List_Table::single_row_columns()
     *
     * @param object $item          A singular item (one full row's worth of data).
     * @param string $column_name   The name/slug of the column to be processed.
     * @return string               Text or HTML to be placed inside the column <td>.
     */
    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'id': 
                return $item->id;
            case 'customer_id':
                return $item->customer_id; //  See function column_customer_id( $item )
            case 'subscription_wc_id':
                return $item->subscription_wc_id;
            case 'tenant_name':
                return $item->tenant_name;
            case 'tenant_url':
                return $item->tenant_url;
            case 'tenant_location':
                return $item->tenant_location;
            case 'tenant_uuid':
                return $item->tenant_uuid;
            case 'created':
                return $item->created;
            case 'modified':
                return $item->modified;
            case 'subscription_expired':
                return $item->subscription_expired;
            default:
                return print_r( $item, true ); // Show the whole array for troubleshooting purposes.
        }
    }

    /**
     * Get and modify column `id`
     */
    protected function column_id ( $item ) {
        return '#' . $item->id;
    }

    /**
     * Get and modify column `customer_id`
     */
    protected function column_customer_id ( $item ) {
        $customer = get_user_by('id', $item->customer_id);
        return $customer->user_login;
    }

    /**
     * Get and modify column `subscription_wc_id`
     * We display subscription ID and the link to WooCommerce -> Subscription
     */
    protected function column_subscription_wc_id ( $item ) {
        return '<a href="/wp-admin/post.php?post=' . $item->subscription_wc_id . '&action=edit" target="_blank">#' . $item->subscription_wc_id . '</a>' ;
    }

    /**
     * Get and modify column `tenant_name`
     * We display the name and hyperlink to edit the client metadata
     */
    protected function column_tenant_name ( $item ) {
        return '<a href="' . menu_page_url('wbssaas-slug', false) . '&id=' . $item->id . '"  target="_blank">' . stripslashes( $item->tenant_name ) . '</a>' ;
    }

    /**
     * Get and modify column `tenant_url`
     */
    protected function column_tenant_url ( $item ) {
        return '<a href="' . $item->tenant_url . '" target="_blank">' . $item->tenant_url . '</a>' ;
    }

    /**
     * Get and modify column `tenant_location`
     */
    protected function column_tenant_location ( $item ) {
        return ucfirst( $item->tenant_location );
    }

    /**
     * Get and modify column `tenant_uuid`
     */
    protected function column_tenant_uuid ( $item ) {
        return '<code>' . substr( $item->tenant_uuid, 0, 8 ) . '...' . substr( $item->tenant_uuid, -12 ) . '</code>';
    }

    /**
     * Get and modify column `created`
     */
    protected function column_created( $item ) {
        return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $item->created ) );
    }

    /**
     * Get and modify column `modified`
     */
    protected function column_modified( $item ) {
        return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $item->modified ) );
    }

    /**
     * Get and modify column `subscription_expired`
     */
    protected function column_subscription_expired( $item ) {
        return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $item->subscription_expired ) );
    }

    /**
     * Add the CSS Class expired if the subscription is already expired
     * 
     * @link https://developer.wordpress.org/reference/classes/wp_list_table/single_row/
     */
    public function single_row( $item ) {
        if( strtotime( $item->subscription_expired ) < time() ) {
            echo '<tr class="expired">';
        } else {
            echo '<tr>';
        }
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    function prepare_items() {

        global $wpdb;

        $per_page = 20;

        /*
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();

        /*
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * three other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array( $columns, $hidden, $sortable );

        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        // $this->process_bulk_action();

        /*
         * GET THE DATA!
         * 
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example
         * package slightly different than one you might build on your own. In
         * this example, we'll be using array manipulation to sort and paginate
         * our dummy data.
         * 
         * In a real-world situation, this is probably where you would want to 
         * make your actual database query. Likewise, you will probably want to
         * use any posted sort or pagination data to build a custom query instead, 
         * as you'll then be able to use the returned query data immediately.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         */
        $table_name = WBSSAAS_DB_TENANTS;
        $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'id';
        $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'DESC';
        $records = $wpdb->get_results("SELECT * FROM $table_name ORDER BY $orderby $order");

        /*
         * REQUIRED for pagination.
         */
        $current_page = $this->get_pagenum();

        /*
         * REQUIRED for pagination.
         */
        $total_items = count( $records );

        /*
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to do that.
         */
        $records = array_slice( $records, ( ( $current_page - 1 ) * $per_page ), $per_page );

        /*
         * REQUIRED. Now we can add our *sorted* data to the items property, where
         * it can be used by the rest of the class.
         */
        $this->items = $records;

        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                     // WE have to calculate the total number of items.
            'per_page'    => $per_page,                        // WE have to determine how many items to show on a page.
            'total_pages' => ceil( $total_items / $per_page ), // WE have to calculate the total number of pages.
        ) );
    }

}

