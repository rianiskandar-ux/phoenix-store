<?php

/**
 * Provide a admin area to edit a tenant
 * WARNING: Every change is sent to the SaaS API
 * 
 * @since 2.2.0
 */

$log = new WBSSaaS\Logger();
$tenant = new \WBSSaaS\Tenant( $log );
$client= $tenant->fetchTenantByID( $_GET['id'] );
$settings = unserialize( $client->tenant_settings );
$wp_user = get_user_by('id', $client->customer_id);

$api = new \WBSSaaS\PhoenixAPI( $client->tenant_location, $log ) ;
$is_migrated = $api->checkMigration( $client->tenant_uuid );
$is_expired  = ( strtotime( $client->subscription_expired ) < time() ) ? true : false;

$form = array(
    'wbssaas_edit_saasid'           => $client->id,
    'wbssaas_edit_uuid'             => $client->tenant_uuid,
    'wbssaas_edit_customerid'       => $wp_user->user_login . ' / ID: ' . $client->customer_id,
    'wbssaas_edit_tenantname'       => $client->tenant_name,
    'wbssaas_edit_tenantlocation'   => ucfirst($client->tenant_location),
    'wbssaas_edit_tenanturl'        => $client->tenant_url,
    'wbssaas_edit_migrated'         => ( $is_migrated->data->migrate_status == true ) ? 'TRUE' : 'FALSE',
    'wbssaas_edit_basicsetupwizard' => 'https://' . substr( $client->tenant_uuid, -12 ) . '.phoenix-whistleblowing.com/clients/new?u=' . $client->tenant_uuid . '&m=' . password_hash( $wp_user->user_email, PASSWORD_DEFAULT ),
    'wbssaas_edit_wcid'             => $client->subscription_wc_id,
    'wbssaas_edit_expiration'       => date_i18n( 'l d F Y H:i T', strtotime( $client->subscription_expired ) ),
    'wbssaas_edit_created'          => date_i18n( 'l d F Y H:i T', strtotime( $client->created ) ),
    'wbssaas_edit_modifed'          => date_i18n( 'l d F Y H:i T', strtotime( $client->modified ) ),
    'wbssaas_edit_webforms'         => implode( ', ', $settings['webforms'] ),
    'wbssaas_edit_phone'            => $settings['phone'],
    'wbssaas_edit_email'            => $settings['email'],
    'wbssaas_edit_im'               => $settings['im'],
    'wbssaas_edit_postmail'         => $settings['postmail'],
    'wbssaas_edit_chat'             => $settings['chat'],
    'wbssaas_edit_mobileapp'        => $settings['mobileapp'],
    'wbssaas_edit_languages'        => $settings['languages'],
    'wbssaas_edit_manager'          => $settings['users']['manager'],
    'wbssaas_edit_operator'         => $settings['users']['operator'],
    'wbssaas_edit_agent'            => $settings['users']['agent'],
    'wbssaas_edit_themes'           => implode( ', ', $settings['themes'] )
);

if ( isset( $_REQUEST['wbssaas-tenant-is-edited'] ) && wp_verify_nonce( $_REQUEST['wbssaas-tenant-is-edited'], 'wbssaas-edit-tenant') ) {

    $log->info( [__FILE__, __LINE__], 'The Settings for ' . $client->tenant_name . ' were updated.' );
    // $log->debug( [__FILE__, __LINE__], $_POST );

    /**
     * At this form, we only update thru the PhoenixAPI the settings
     */
    if( $_POST['wbssaas_edit_webforms'] != $form['wbssaas_edit_webforms'] ) {
        
        $log->warning( [__FILE__, __LINE__], 'Settings "Webforms" modified: ' . $form['wbssaas_edit_webforms'] . ' → ' .  $_POST['wbssaas_edit_webforms']);
        $form['wbssaas_edit_webforms'] = $_POST['wbssaas_edit_webforms'];
        $settings['webforms'] = explode( ', ', $form['wbssaas_edit_webforms'] );
    }
    if( $_POST['wbssaas_edit_phone'] != $form['wbssaas_edit_phone'] ) {
        
        $log->warning( [__FILE__, __LINE__], 'Settings "Phone" modified: ' . $form['wbssaas_edit_phone'] . ' → ' .  $_POST['wbssaas_edit_phone']);
        $form['wbssaas_edit_phone'] = $_POST['wbssaas_edit_phone'];
        $settings['phone'] = $form['wbssaas_edit_phone'];
    }
    if( $_POST['wbssaas_edit_email'] != $form['wbssaas_edit_email'] ) {
        
        $log->warning( [__FILE__, __LINE__], 'Settings "Email" modified: ' . $form['wbssaas_edit_email'] . ' → ' .  $_POST['wbssaas_edit_email']);
        $form['wbssaas_edit_email'] = $_POST['wbssaas_edit_email'];
        $settings['email'] = $form['wbssaas_edit_email'];

    }
    if( $_POST['wbssaas_edit_im'] != $form['wbssaas_edit_im'] ) {
        
        $log->warning( [__FILE__, __LINE__], 'Settings "Instant Message" modified: ' . $form['wbssaas_edit_im'] . ' → ' .  $_POST['wbssaas_edit_im']);
        $form['wbssaas_edit_im'] = $_POST['wbssaas_edit_im'];
        $settings['im'] = $form['wbssaas_edit_im'];

    }
    if( $_POST['wbssaas_edit_postmail'] != $form['wbssaas_edit_postmail'] ) {

        $log->warning( [__FILE__, __LINE__], 'Settings "Postage Mail" modified: ' . $form['wbssaas_edit_postmail'] . ' → ' .  $_POST['wbssaas_edit_postmail']);
        $form['wbssaas_edit_postmail'] = $_POST['wbssaas_edit_postmail'];
        $settings['postmail'] = $form['wbssaas_edit_postmail'];

    }
    if( $_POST['wbssaas_edit_chat'] != $form['wbssaas_edit_chat'] ) {

        $log->warning( [__FILE__, __LINE__], 'Settings "Online Chat Room" modified: ' . $form['wbssaas_edit_chat'] . ' → ' .  $_POST['wbssaas_edit_chat']);
        $form['wbssaas_edit_chat'] = $_POST['wbssaas_edit_chat'];
        $settings['chat'] = $form['wbssaas_edit_chat'];

    }
    if( $_POST['wbssaas_edit_mobileapp'] != $form['wbssaas_edit_mobileapp'] ) {

        $log->warning( [__FILE__, __LINE__], 'Settings "Mobile App" modified: ' . $form['wbssaas_edit_mobileapp'] . ' → ' .  $_POST['wbssaas_edit_mobileapp']);
        $form['wbssaas_edit_mobileapp'] = $_POST['wbssaas_edit_mobileapp'];
        $settings['mobileapp'] = $form['wbssaas_edit_mobileapp'];

    }
    if( $_POST['wbssaas_edit_languages'] != $form['wbssaas_edit_languages'] ) {

        $log->warning( [__FILE__, __LINE__], 'Settings "Languages" modified: ' . $form['wbssaas_edit_languages'] . ' → ' .  $_POST['wbssaas_edit_languages']);
        $form['wbssaas_edit_languages'] = $_POST['wbssaas_edit_languages'];
        $settings['languages'] = $form['wbssaas_edit_languages'];


    }
    if( $_POST['wbssaas_edit_manager'] != $form['wbssaas_edit_manager'] ) {

        $log->warning( [__FILE__, __LINE__], 'Settings "Account as Manager" modified: ' . $form['wbssaas_edit_manager'] . ' → ' .  $_POST['wbssaas_edit_manager']);
        $form['wbssaas_edit_manager'] = $_POST['wbssaas_edit_manager'];
        $settings['users']['manager'] = $form['wbssaas_edit_manager'];

    }
    if( $_POST['wbssaas_edit_operator'] != $form['wbssaas_edit_operator'] ) {

        $log->warning( [__FILE__, __LINE__], 'Settings "Account as Operator" modified: ' . $form['wbssaas_edit_operator'] . ' → ' .  $_POST['wbssaas_edit_operator']);
        $form['wbssaas_edit_operator'] = $_POST['wbssaas_edit_operator'];
        $settings['users']['operator'] = $form['wbssaas_edit_operator'];

    }
    if( $_POST['wbssaas_edit_agent'] != $form['wbssaas_edit_agent'] ) {

        $log->warning( [__FILE__, __LINE__], 'Settings "Account as Agent" modified: ' . $form['wbssaas_edit_agent'] . ' → ' .  $_POST['wbssaas_edit_agent']);
        $form['wbssaas_edit_agent'] = $_POST['wbssaas_edit_agent'];
        $settings['users']['agent'] = $form['wbssaas_edit_agent'];

    }
    if( $_POST['wbssaas_edit_themes'] != $form['wbssaas_edit_themes'] ) {

        $log->warning( [__FILE__, __LINE__], 'Settings "Themes" modified: ' . $form['wbssaas_edit_themes'] . ' → ' .  $_POST['wbssaas_edit_themes']);
        $form['wbssaas_edit_themes'] = $_POST['wbssaas_edit_themes'];
        $settings['themes'] = explode( ', ', $form['wbssaas_edit_themes'] );
    }

    $log->debug( [__FILE__, __LINE__], 'New settings =>' );
    $log->debug( [__FILE__, __LINE__], $settings );

    /**
     * Save in MySQL WBSSAAS_DB_TENANTS
     */
    global $wpdb;

    $result = $wpdb->update( 
        WBSSAAS_DB_TENANTS,
        array( 
            'tenant_settings' => serialize( $settings ),
            'modified'        => current_time( 'mysql' )
        ),
        array(
            'id' => $client->id,
        )
    );
    $log->debug( [__FILE__, __LINE__], 'New settings updated in MySQL. Result =>' );
    $log->debug( [__FILE__, __LINE__], $result );

    /**
     * Save in PhoenixAPI
     */
    
    $log->debug( [__FILE__, __LINE__], $is_migrated );
    $response = $api->updatePackage( $client, $settings );
    $log->debug( [__FILE__, __LINE__], 'New settings sent to Phoenix API. Response =>' );
    $log->debug( [__FILE__, __LINE__], $response );

    $notice = array(
        'level'   => 'success',
        'message' => '<h3>Settings saved!</h3>' .
                     '<p>All the settings were saved successfully and updated to the API.</p>',
    );
}

?>

<?php if(isset($notice) ) : ?>
    <div class="notice notice-<?= $notice['level'] ?>"><?= $notice['message'] ?></div>
<?php endif; ?>

<h2 style="font-size: x-large;">#<?= $client->id ?> <?= $client->tenant_name ?></h2>
<div class="manage-menus">
    <h3>Read Carefully!</h3>
    <?php if( $is_migrated->data->migrate_status == false ) : ?>
        <p><strong>The instance has not been migrated yet. Editing is not allowed.</strong></p>
    <?php elseif( $is_expired == true ) : ?>
        <p><strong>The subscription is already expired. Editing is not allowed.</strong></p>
    <?php else : ?>
        <p><strong>All changes will be applied and sent to the Phoenix API: <?= $client->tenant_location ?></strong></p>
    <?php endif; ?>
</div>
<form action="<?php menu_page_url('wbssaas-slug', true); ?><?= '&id=' . $_GET['id'] ?>" method="post">

    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th colspan="2"><h3>Identifiers</h3></th>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_saasid">SaaS ID:</label></th>
                <td>
                    <input name="wbssaas_edit_saasid" type="number" id="wbssaas_edit_saasid" value="<?= $form['wbssaas_edit_saasid'] ?>" class="regular-text code" readonly>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_uuid">SaaS UUID:</label></th>
                <td>
                    <input name="wbssaas_edit_uuid" type="text" id="wbssaas_edit_uuid" value="<?= $form['wbssaas_edit_uuid'] ?>" class="regular-text code" readonly>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_customerid">WC Customer ID:</label></th>
                <td>
                    <input name="wbssaas_edit_customerid" type="text" id="wbssaas_edit_customerid" value="<?= $form['wbssaas_edit_customerid'] ?>" class="regular-text code" readonly>
                    <p class="description"><a href="/wp-admin/admin.php?page=wc-admin&path=%2Fcustomers" target="_blank">See list of all customers...</a></p>
                </td>
            </tr>
            <tr>
                <th colspan="2"><h3>Tenant</h3></th>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_tenantname">Company Name:</label></th>
                <td>
                    <input name="wbssaas_edit_tenantname" type="text" id="wbssaas_edit_tenantname" value="<?= $form['wbssaas_edit_tenantname'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_tenantlocation">Location:</label></th>
                <td>
                    <input name="wbssaas_edit_tenantlocation" type="text" id="wbssaas_edit_tenantlocation" value="<?= $form['wbssaas_edit_tenantlocation'] ?>" class="regular-text code" readonly>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_tenanturl">Website URL:</label></th>
                <td>
                    <input name="wbssaas_edit_tenanturl" type="text" id="wbssaas_edit_tenanturl" value="<?= $form['wbssaas_edit_tenanturl'] ?>" class="regular-text code" readonly>
                    <p class="description"><a href="<?= $form['wbssaas_edit_tenanturl'] ?>" target="_blank">Go to Website...</a></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_tenanturl">Laravel Migration:</label></th>
                <td>
                    <input name="wbssaas_edit_migrated" type="text" id="wbssaas_edit_migrated" value="<?= $form['wbssaas_edit_migrated'] ?>" class="regular-text code" readonly>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_basicsetupwizard">Basic Setup Wizard:</label></th>
                <td>
                    <textarea name="wbssaas_edit_basicsetupwizard" id="wbssaas_edit_basicsetupwizard" class="regular-text code" rows="5" readonly><?= $form['wbssaas_edit_basicsetupwizard'] ?></textarea>
                    <p class="description"><a href="<?= $form['wbssaas_edit_basicsetupwizard'] ?>" target="_blank">Go to link Basic Setup Wizard...</a></p>
                </td>
            </tr>
            <tr>
                <th colspan="2"><h3>Subscription</h3></th>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_wcid">WC Subscription ID:</label></th>
                <td>
                    <input name="wbssaas_edit_wcid" type="text" id="wbssaas_edit_wcid" value="#<?= $form['wbssaas_edit_wcid'] ?>" class="regular-text code" readonly>
                    <p class="description"><a href="/wp-admin/post.php?post=<?= $form['wbssaas_edit_wcid'] ?>&action=edit"" target="_blank">Go to Subscription Details...</a></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_expiration">Expiration Date:</label></th>
                <td>
                    <input name="wbssaas_edit_expiration" type="text" id="wbssaas_edit_expiration" value="<?= $form['wbssaas_edit_expiration'] ?>" class="regular-text code" readonly>
                </td>
            </tr>
            <tr>
                <th colspan="2"><h3>Settings</h3></th>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_created">Created:</label></th>
                <td>
                    <input name="wbssaas_edit_created" type="text" id="wbssaas_edit_created" value="<?= $form['wbssaas_edit_created'] ?>" class="regular-text code" readonly>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_modifed">Last Modified:</label></th>
                <td>
                    <input name="wbssaas_edit_modifed" type="text" id="wbssaas_edit_modifed" value="<?= $form['wbssaas_edit_modifed'] ?>" class="regular-text code" readonly>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_webforms">Webforms:</label></th>
                <td>
                    <input name="wbssaas_edit_webforms" type="text" id="wbssaas_edit_webforms" value="<?= $form['wbssaas_edit_webforms'] ?>" class="regular-text code">
                    <p class="description"><code>short, medium, long, custom</code></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_phone">Phone Numbers:</label></th>
                <td>
                    <input name="wbssaas_edit_phone" type="number" id="wbssaas_edit_phone" value="<?= $form['wbssaas_edit_phone'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_email">Email Inbox:</label></th>
                <td>
                    <input name="wbssaas_edit_email" type="number" id="wbssaas_edit_email" value="<?= $form['wbssaas_edit_email'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_im">Instant Messaging:</label></th>
                <td>
                    <input name="wbssaas_edit_im" type="number" id="wbssaas_edit_im" value="<?= $form['wbssaas_edit_im'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_postmail">Postal Address:</label></th>
                <td>
                    <input name="wbssaas_edit_postmail" type="number" id="wbssaas_edit_postmail" value="<?= $form['wbssaas_edit_postmail'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_chat">Online Chat:</label></th>
                <td>
                    <input name="wbssaas_edit_chat" type="number" id="wbssaas_edit_chat" value="<?= $form['wbssaas_edit_chat'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_mobileapp">Mobile App:</label></th>
                <td>
                    <input name="wbssaas_edit_mobileapp" type="number" id="wbssaas_edit_mobileapp" value="<?= $form['wbssaas_edit_mobileapp'] ?>" min="0" max="1" class="regular-text code">
                    <p class="description">1 = Enable, 0 = Disable</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_languages">Language & Localisation:</label></th>
                <td>
                    <input name="wbssaas_edit_languages" type="number" id="wbssaas_edit_languages" value="<?= $form['wbssaas_edit_languages'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_manager">Account as Manager:</label></th>
                <td>
                    <input name="wbssaas_edit_manager" type="number" id="wbssaas_edit_manager" value="<?= $form['wbssaas_edit_manager'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_operator">Account as Operator:</label></th>
                <td>
                    <input name="wbssaas_edit_operator" type="number" id="wbssaas_edit_operator" value="<?= $form['wbssaas_edit_operator'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_agent">Account as Agent:</label></th>
                <td>
                    <input name="wbssaas_edit_agent" type="number" id="wbssaas_edit_agent" value="<?= $form['wbssaas_edit_agent'] ?>" class="regular-text code">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wbssaas_edit_themes">Themes:</label></th>
                <td>
                    <textarea name="wbssaas_edit_themes" id="wbssaas_edit_themes" class="regular-text code" rows="3"><?=  $form['wbssaas_edit_themes'] ?></textarea>
                    <p class="description">Using SKU: <code>phoenix_1, phoenix_2, phoenix_3, etc</code></p>
                </td>
            </tr>
        </tbody>
    </table>

    <?php
        wp_nonce_field( 'wbssaas-edit-tenant', 'wbssaas-tenant-is-edited' );
        if( $is_migrated->data->migrate_status == true && $is_expired == false ) {
            submit_button('Update', 'primary', 'submit', false );
        }
    ?>
    <a href="<?= menu_page_url('wbssaas-slug', false) ?>"><button type="button" class="button button-secondary">Go Back</button></a>

</form>
