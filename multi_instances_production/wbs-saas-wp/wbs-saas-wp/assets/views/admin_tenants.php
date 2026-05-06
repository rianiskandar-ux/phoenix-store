<?php

/**
 * Provide a admin area view to see all the SaaS Tenants
 * 
 * @see wbssaas_admin_tenants()
 * 
 * @since 2.0.0
 */

?>

<div class="wrap">

	<h1>WBS SaaS / Phoenix for WordPress</h1>
	
    <?php

    if ( isset( $_GET['id'] ) ) {

        include_once( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_edit_tenants.php' );

    } else {

        echo '<h2>Tenants</h2>';
        $tenants = new \WBSSaaS\TenantWPTable();
        $tenants->prepare_items();
        $tenants->display();

    }

    ?>

</div><!-- div class wrap -->