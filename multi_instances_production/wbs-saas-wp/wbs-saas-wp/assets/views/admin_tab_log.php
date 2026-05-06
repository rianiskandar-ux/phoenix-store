<?php

/**
 * Provide the admin area to display Logs
 * 
 * @see wbssaas_admin_settings()
 * 
 * @since 2.2.0
 */

global $app;
$log_files = glob( $app->log->path . '*.log' );
natsort( $log_files );
$current_file = ( isset( $_GET['file'] ) ) ? $_GET['file'] : 'wp-errors.log';

?>

<ul class="subsubsub">
<?php foreach( $log_files as $path): ?>
    <li><a href="<?= menu_page_url('wbssaas-settings-slug', false) ?>&tab=log&file=<?= basename( $path ) ?>"><?= basename( $path ) ?></a> | </li>
<?php endforeach; ?>
</ul>

<br class="clear">  

<h3><?= strtoupper( $current_file ) ?></h3>

<div class="log-viewer">

    <pre><?= file_get_contents( $app->log->path . $current_file ) ?></pre>

</div>