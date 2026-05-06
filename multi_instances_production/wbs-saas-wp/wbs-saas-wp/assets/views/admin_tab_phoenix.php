<?php

/**
 * Provide the admin area to view and edit Phoenix Settings
 * 
 * @see wbssaas_admin_settings()
 * 
 * @since 2.2.0
 */

$options = get_option('wbssaas_options');

if ( isset( $_REQUEST['wbssaas-options-is-setup'] ) && wp_verify_nonce( $_REQUEST['wbssaas-options-is-setup'], 'wbssaas-options') ) {

    $log = new WBSSaaS\Logger();
    $log->info( [__FILE__, __LINE__], 'POST Admin Settings form =>' );
    $log->debug( [__FILE__, __LINE__], $_POST );

    if( isset( $_POST['wbssaas_option_mode_debug']) && $_POST['wbssaas_option_mode_debug'] == true) {

        $options['mode_debug'] = true; // mode debug activate

    } else {

        $options['mode_debug'] = false; // Mode debug deactivate

    }

    $options['environment'] = $_POST['wbssaas_option_environment'];

    update_option( 'wbssaas_options', $options);

    $notice = array(
        'level'   => 'success',
        'message' => '<h3>Settings saved!</h3>' .
                     '<p>All the settings were saved successfully.</p>',
    );
}

?>

<div class="manage-menus">
    <p>This is where you configure the Phoenix Settings:</p>
    <ul>
        <li><strong>Mode debug:</strong> This option output in logs all the events tagged as [DEBUG]</li>
        <li><strong>Environment</strong>
            <ul>
                <li>Staging: All the queries are send to the Staging Promox server at the head office.</li>
                <li>Production: All the queries are send to the location defined by the customer.</li>
            </ul>
        </li>
    </ul>
</div>
<form action="<?php menu_page_url('wbssaas-settings-slug', true); ?>" method="post">

    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="mode_debug">Mode Debug:</label></th>
                <td>
                    <input name="wbssaas_option_mode_debug" type="checkbox" id="wbssaas_option_mode_debug" value="true" <?php if( $options['mode_debug'] == true ) : ?>checked<?php endif; ?>> Activate
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="environment">Environment:</label></th>
                <td>
                    <select name="wbssaas_option_environment" id="wbssaas_option_environment">
                        <option value="staging" <?php if( $options['environment'] == 'staging' ) : ?>selected<?php endif; ?>>Staging / Hostnic</option>
                        <option value="production" <?php if( $options['environment'] == 'production' ) : ?>selected<?php endif; ?>>Production / Mulitiple Locations</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>

    <?php
        wp_nonce_field( 'wbssaas-options', 'wbssaas-options-is-setup' );
        submit_button('Save');
    ?>
</form>
