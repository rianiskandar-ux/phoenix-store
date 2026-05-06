<?php

/**
 * Provide the admin area to view Locations (PAOW)
 * 
 * @see wbssaas_admin_settings()
 * 
 * @since 2.2.0
 */

global $app;

?>

<div class="manage-menus">
    <p>All the configuration options are stored in <code><?= WBSSAAS_PLUGIN_DIR . 'config' ?></code> and therefore not editable here.</p>
</div>

<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row"><label>Staging / Promox:</label></th>
            <td>
                <p><input type="text" value="<?= $app->location->staging->ip ?>" class="regular-text code" readonly></p>
                <p><input type="text" value="<?= $app->location->staging->fqdn ?>" class="regular-text code" readonly></p>
                <p><textarea  class="regular-text code" readonly><?= $app->location->staging->token ?></textarea></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Switzerland / Infomaniak:</label></th>
            <td>
                <p><input type="text" value="<?= $app->location->switzerland->ip ?>" class="regular-text code" readonly></p>
                <p><input type="text" value="<?= $app->location->switzerland->fqdn ?>" class="regular-text code" readonly></p>
                <p><textarea  class="regular-text code" readonly><?= $app->location->switzerland->token ?></textarea></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Singapore:</label></th>
            <td>
                <p><input type="text" value="<?= $app->location->singapore->ip ?>" class="regular-text code" readonly></p>
                <p><input type="text" value="<?= $app->location->singapore->fqdn ?>" class="regular-text code" readonly></p>
                <p><textarea  class="regular-text code" readonly><?= $app->location->singapore->token ?></textarea></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Indonesia / Hostnic:</label></th>
            <td>
                <p><input type="text" value="<?= $app->location->indonesia->ip ?>" class="regular-text code" readonly></p>
                <p><input type="text" value="<?= $app->location->indonesia->fqdn ?>" class="regular-text code" readonly></p>
                <p><textarea  class="regular-text code" readonly><?= $app->location->indonesia->token ?></textarea></p>

            </td>
        </tr>
    </tbody>
</table>

