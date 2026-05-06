<?php

/**
 * Provide a admin area view to see and edit all the settings
 * 
 * @see wbssaas_admin_settings()
 * 
 * @since 2.0.0
 */

// Get the active tab from the $_GET param
$default_tab = null;
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

?>

<div class="wrap">

    <h1>WBS SaaS / Phoenix for WordPress</h1>

    <?php if( isset( $notice ) ) : ?>
        <div class="notice notice-'<?= $notice['level'] ?>"><?= $notice['message'] ?></div>
    <?php endif; ?>
    
    <h2 style="font-size: x-large;">Settings</h2>

    <nav class="nav-tab-wrapper">
        <a href="?page=wbssaas-settings-slug" class="nav-tab <?php if( $tab === null): ?>nav-tab-active<?php endif; ?>">Phoenix</a>
        <a href="?page=wbssaas-settings-slug&tab=locations" class="nav-tab <?php if( $tab === 'locations'):?>nav-tab-active<?php endif; ?>">Locations</a>
        <a href="?page=wbssaas-settings-slug&tab=cloudflare" class="nav-tab <?php if( $tab === 'cloudflare'):?>nav-tab-active<?php endif; ?>">Cloudflare</a>
        <a href="?page=wbssaas-settings-slug&tab=ik" class="nav-tab <?php if( $tab === 'ik'):?>nav-tab-active<?php endif; ?>">Infomaniak</a>
        <a href="?page=wbssaas-settings-slug&tab=version" class="nav-tab <?php if( $tab === 'version'):?>nav-tab-active<?php endif; ?>">Versioning</a>
        <a href="?page=wbssaas-settings-slug&tab=log" class="nav-tab <?php if( $tab === 'log'):?>nav-tab-active<?php endif; ?>">Logs</a>
        <a href="?page=wbssaas-settings-slug&tab=devel" class="nav-tab <?php if( $tab === 'devel'):?>nav-tab-active<?php endif; ?>">Devel / Tools</a>
    </nav>

    <div class="tab-content">
    <?php switch( $tab ) :
        case 'locations':
            include( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_tab_locations.php' );
            break;
        case 'cloudflare':
            include( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_tab_cloudflare.php' );
            break;
        case 'ik':
            include( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_tab_ik.php' );
            break;
        case 'version':
            include( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_tab_version.php' );
            break;
        case 'log':
            include( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_tab_log.php' );
            break;
        case 'devel':
            include( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_tab_devel.php' );
            break;
        default:
            include( WBSSAAS_PLUGIN_DIR . 'assets/views/admin_tab_phoenix.php' );
            break;
    endswitch; ?>
    </div>
</div><!-- div class wrap -->