<?php

/**
 * Provide the admin area to view and edit Cloudflare Settings
 * 
 * @see wbssaas_admin_settings()
 * 
 * @since 2.2.0
 */

global $app;

?>

<div class="manage-menus">
    <p>All the configuration options are stored in <code><?= WBSSAAS_PLUGIN_DIR . 'config' ?></code> and therefore not editable here.</p>
    <p><strong>Cloudflare API Reference:</strong> <a href="https://developers.cloudflare.com/api/" target="_blank">https://developers.cloudflare.com/api/</a></p>
</div>

<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row"><label>Token:</label></th>
            <td>
                <textarea class="regular-text code" readonly><?= $app->cloudflare->token ?></textarea>
            </td>
        </tr>
        <tr>
            <th colspan="2"><h3>Zone ID</h3></th>
        </tr>
        <tr>
            <th scope="row"><label>43210.org</label></th>
            <td>
                <input type="text" value="<?= $app->cloudflare->{'43210.org'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>phoenix-whistleblowing.com</label></th>
            <td>
                <input type="text" value="<?= $app->cloudflare->{'phoenix-whistleblowing.com'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>speak-up.link</label></th>
            <td>
                <input type="text" value="<?= $app->cloudflare->{'speak-up.link'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>whistleblowing.direct</label></th>
            <td>
                <input type="text" value="<?= $app->cloudflare->{'whistleblowing.direct'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>whistleblowinghotline.net</label></th>
            <td>
                <input type="text" value="<?= $app->cloudflare->{'whistleblowinghotline.net'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>whistle-blowing.org</label></th>
            <td>
                <input type="text" value="<?= $app->cloudflare->{'whistle-blowing.org'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
    </tbody>
</table>

