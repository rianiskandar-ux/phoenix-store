<?php

/**
 * Create MySQL Table on _install() and delete table on _uninstall()
 * 
 * @link https://codex.wordpress.org/Creating_Tables_with_Plugins
 * @link https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
 * 
 * @since 2.0.0
 * 
 * @see register_activation_hook() callback wbssaas_install()
 * @see register_uninstall_hook() callback wbssaas_uninstall()
 * 
 */

function wbssaas_install_db() :void {

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_tenants = WBSSAAS_DB_TENANTS;

    $sql_tenants = "CREATE TABLE $table_tenants (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        customer_id mediumint(9) NOT NULL,
        tenant_uuid varchar(36),
        tenant_name text NOT NULL,
        tenant_url varchar(255) NOT NULL,
        tenant_location varchar(255) NOT NULL,
        tenant_settings longtext,
        subscription_wc_id mediumint(9),
        subscription_expired datetime DEFAULT '0000-00-00 00:00:00',
        created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql_tenants );
}

function wbssaas_delete_db() :void {

    global $wpdb;
    $table_tenants = WBSSAAS_DB_TENANTS;

    $sql_company = "DROP TABLE IF EXISTS $table_tenants";
    $wpdb->query($sql_company);

}