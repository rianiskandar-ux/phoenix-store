<?php

/**
 * Provide the admin area to display any output for devel
 * 
 * @see wbssaas_admin_settings()
 * 
 * @since 2.2.0
 */

if ( isset( $_REQUEST['wbssaas-domain-is-checked'] )  && wp_verify_nonce( $_REQUEST['wbssaas-domain-is-checked'], 'wbssaas-check-domain') ) {
    $tenants = new WBSSaaS\Tenant;

    if( $tenants->verifyDomainAvailability( $_POST['wbssaas-check-domain'] ) ) {

        $notice = array(
            'level'   => 'success',
            'message' => '<h3>Good news!</h3>' .
                        '<p>The domain name '. $_POST['wbssaas-check-domain'] . ' is available.</p>'
        );

    } else {
        
        $notice = array(
            'level'   => 'warning',
            'message' => '<h3>Bad news!</h3>' .
                        '<p>The domain name '. $_POST['wbssaas-check-domain'] . ' is not available.</p>'
        );
    }
}

?>

<h3>Check FQDN avaibility:</h3>

<form action="<?= menu_page_url('wbssaas-settings-slug', false) ?>&tab=devel" method="post">
    <input name="wbssaas-check-domain" type="url" id="wbssaas-check-domain" class="regular-text" required>
    <p class="description">the fully qualified domain name.</p>
    <?php
        wp_nonce_field( 'wbssaas-check-domain', 'wbssaas-domain-is-checked' );
        submit_button('Check');
    ?>
</form>

<div class="log-viewer" style="margin-top: 2em;">
    <pre>
<?php
var_dump( $notice );
?>
    </pre>
</div>