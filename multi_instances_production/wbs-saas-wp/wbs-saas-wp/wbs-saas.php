<?php

/**
* WBS SaaS plugin for WordPress
*
* @package           WBSSaaS
* @author            Nicolas Georget
* @copyright         2026 Integrity Asia
*
* @wordpress-plugin
* Plugin Name:       WBS SaaS for WordPress
* Plugin URI:        https://www.phoenix-whistleblowing.com
* Description:       This plugin is a bridge between WordPress and WBS SaaS Phoenix
* Version:           2.3.0
* Requires at least: 6.0
* Requires PHP:      8.0
* Text Domain:       wbs-saas-plugin
* Author:            Nicolas Georget
* Author URI:        https://ngeorget.github.io
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define All Constants 
 * 
 * @see wbssaas_loaded() for DB_VERSIon
 * 
 * @link https://developer.wordpress.org/reference/functions/plugin_dir_path/
 * @link https://developer.wordpress.org/reference/functions/plugin_basename/
 * @link https://developer.wordpress.org/reference/functions/plugins_url/
 * 
 * @since 2.0.0
 */

global $wpdb;
define( 'WBSSAAS_PLUGIN_NAME',    'wbs-saas-wp' );
define( 'WBSSAAS_PLUGIN_VERSION', '2.3.0' );
define( 'WBSSAAS_PLUGIN_DIR',      plugin_dir_path( __FILE__ ) );
define( 'WBSSAAS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WBSSAAS_PLUGIN_URL',      plugins_url( WBSSAAS_PLUGIN_NAME ) );
define( 'WBSSAAS_DB_VERSION',      '2.1' ); // @see wbssaas_loaded()
define( 'WBSSAAS_DB_TENANTS',      $wpdb->prefix . 'wbssaas_tenants' );
define( 'WBSSAAS_WPML_DOMAIN',     'wbs-saas-plugin' );

/**
 * Config Files
 * 
 * @since 2.2.0
 */
require __DIR__ . '/vendor/autoload.php';
$app     = new Laminas\Config\Config( include WBSSAAS_PLUGIN_DIR . 'config/app.php' );
$gravity = new Laminas\Config\Config( include WBSSAAS_PLUGIN_DIR . 'config/gravity.php' );
$wc      = new Laminas\Config\Config( include WBSSAAS_PLUGIN_DIR . 'config/wc.php' );

/**
 * Requires
 */
require_once( WBSSAAS_PLUGIN_DIR . 'includes/db.php');
require_once( WBSSAAS_PLUGIN_DIR . 'includes/class.logger.php');
require_once( WBSSAAS_PLUGIN_DIR . 'includes/class.api.php');
require_once( WBSSAAS_PLUGIN_DIR . 'includes/class.tenant.php');
require_once( WBSSAAS_PLUGIN_DIR . 'includes/class.git.php');
require_once( WBSSAAS_PLUGIN_DIR . 'includes/hook.gravity.php');
require_once( WBSSAAS_PLUGIN_DIR . 'includes/hook.wc.php');
require_once( WBSSAAS_PLUGIN_DIR . 'includes/shortcodes.php');
require_once( WBSSAAS_PLUGIN_DIR . 'languages/my-account.php');



/**
 * Initialize hook when the plugin is activated or removed
 * Since version 2.2.x, the hook 'plugins_loaded' is added to upgrade the
 * DB with a new column for the Tenant Location
 * 
 * @link https://developer.wordpress.org/reference/functions/register_activation_hook/
 * @link https://codex.wordpress.org/Creating_Tables_with_Plugins
 * 
 * @since 2.0.0
 */

register_activation_hook( __FILE__, 'wbssaas_init');
add_action( 'plugins_loaded', 'wbssaas_loaded' );
register_uninstall_hook(__FILE__, 'wbssaas_remove');

function wbssaas_init() :void {

    $log = new \WBSSaaS\Logger();
    $log->info( [__METHOD__, __LINE__], 'The hook register_activation_hook() is executed.' );

    wbssaas_install_db();

    $options = get_option( 'wbssaas_options' );
    // $log->debug( [__METHOD__, __LINE__], $options );

    if( !$options ) {
        $options['mode_debug']   = true;
        $options['environment'] = 'staging'; // staging|production
        // a:2:{s:10:"mode_debug";b:1;s:11:"environment";s:7:"staging";}

        update_option( 'wbssaas_options', $options);
    }
}

function wbssaas_loaded(): void {

    if( WBSSAAS_DB_VERSION != '2.1' ) {

        $log = new \WBSSaaS\Logger();
        $log->info( [__METHOD__, __LINE__], 'The hook wbssaas_loaded() is executed. Normally to upgrade the DB Schema.' );
    
        wbssaas_install_db();
    
    }
}

function wbssaas_remove() :void {

    $log = new \WBSSaaS\Logger();
    $log->warning( [__METHOD__, __LINE__], 'The hook register_uninstall_hook() is executed.' );

    wbssaas_delete_db();

    delete_option("wbssaas_options");
}


/**
 * Admin / Backend
 * The trick to get the first menu as "container" for the other submneu is
 * the menu_slug of the first add_subemnu_page() is the same as menu_slug of add_menu_page()
 * 
 * @link https://codex.wordpress.org/Administration_Menus
 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
 * @link https://developer.wordpress.org/reference/functions/add_submenu_page/
 * 
 * @since 2.0.0
 */

add_action('admin_menu', 'wbssaas_admin_menu');

function wbssaas_admin_menu () {

    add_menu_page( 'WBS SaaS for WordPress', 'WBS SaaS WP', 'manage_options', 'wbssaas-slug', null, 'dashicons-rest-api' );
    add_submenu_page( 'wbssaas-slug', 'WBS SaaS Tenants', 'Tenants', 'manage_options', 'wbssaas-slug', 'wbssaas_admin_tenants');
    add_submenu_page( 'wbssaas-slug', 'WBS SaaS Settings', 'Settings', 'manage_options', 'wbssaas-settings-slug', 'wbssaas_admin_settings');
}

/**
 * Admin Callbacks functions
 */

function wbssaas_admin_tenants() {

    require_once( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_tenants.php' );

}

function wbssaas_admin_settings() {

    require_once( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_settings.php' );

}

/**
 * Register all of the hooks related to the admin area functionality of the plugin.
 * Register all of the hooks related to the public-facing functionality of the plugin.
 * Despite the name, it is used for enqueuing both scripts and styles.
 * 
 * @link https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * 
 * @since 2.0.0
 */

add_action( 'admin_enqueue_scripts', 'wbssaas_register_admin_assets' );

function wbssaas_register_admin_assets() {

    wp_enqueue_style(  'wbssaas-styles',  WBSSAAS_PLUGIN_URL . '/assets/css/wbs-saas-admin.css' );
    wp_enqueue_script( 'wbssaas-scripts', WBSSAAS_PLUGIN_URL . '/assets/js/wbs-saas-admin.js' );

}

add_action( 'wp_enqueue_scripts', 'wbssaas_register_public_assets' );

function wbssaas_register_public_assets() {

    wp_enqueue_style(  'wbssaas-styles',  WBSSAAS_PLUGIN_URL . '/assets/css/wbs-saas.css' );
    wp_enqueue_script( 'wbssaas-scripts', WBSSAAS_PLUGIN_URL . '/assets/js/wbs-saas.js' );

}

/**
 * Register REST API endpoints
 * 
 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-rest-api-endpoints/
 * 
 * https://phoenix.test/wp-json/wbssaas/v1/uuid/315795c4-cfb7-465f-a3b6-fd3af41dd7fe
 * 
 * 
 * @since 2.3.0
 */

add_action( 'rest_api_init', 'wbssaas_register_rest_routes' );

function wbssaas_register_rest_routes() {

    $namespace = 'wbssaas/v1';

    // Regex UUID: [a-zA-Z0-9-]+ or [0-9a-fA-F-]{36})
    register_rest_route( $namespace, '/uuid/(?P<uuid>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'wbssaas_get_tenant_from_uuid',
        'permission_callback' => '__return_true',
    ) );
}

function wbssaas_get_tenant_from_uuid( $request ) {

    global $wc;

    $uuid = $request['uuid'];

    $tenant = new \WBSSaaS\Tenant();
    $tenant_data = $tenant->fetchTenantByUUID( $uuid );

    if ( ! $tenant_data ) {
        return new WP_Error( 'tenant_not_found', 'Tenant not found', array( 'status' => 404 ) );
    }

    $log = new \WBSSaaS\Logger();
    $log->info( [__METHOD__, __LINE__], 'Start Function API' );

    $log->debug( [__METHOD__, __LINE__], 'Tenant data retrieved from the database =>');
    $log->debug( [__METHOD__, __LINE__], $tenant_data );
    $log->info( [__METHOD__, __LINE__], 'Susbcription ID from the API => ' . $tenant_data->subscription_wc_id);

    $subscription = wcs_get_subscription( $tenant_data->subscription_wc_id );

    $items = $subscription->get_items();
    $current_item = reset( $items ); // Gets the first product in the sub
    $phoenix_subscription = $current_item->get_product_id();
    $log->debug( [__METHOD__, __LINE__], 'Phoenix Subscription ID: ' . $phoenix_subscription );

    return rest_ensure_response( array(
        'tenant_uuid' => $tenant_data->tenant_uuid,
        'tenant_free' => ($phoenix_subscription == $wc->subscription->free->id) ? true : false,
    ) );
}