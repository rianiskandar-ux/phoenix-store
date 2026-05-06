<?php

/**
 * Provide the admin area to view and edit Infomaniak Settings
 * 
 * @see wbssaas_admin_settings()
 * 
 * @since 2.2.0
 */

global $app;

$ik = new \WBSSaaS\InfomaniakAPI;

?>

<div class="manage-menus">
    <p>All the configuration options are stored in <code><?= WBSSAAS_PLUGIN_DIR . 'config' ?></code> and therefore not editable here.</p>
    <p><strong>Infomaniak API Reference:</strong> <a href="https://developer.infomaniak.com/docs/api" target="_blank">https://developer.infomaniak.com/docs/api</a></p>
</div>

<?php
/*
<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row"><label>Token:</label></th>
            <td>
                <textarea  class="regular-text code" rows="3" readonly><?= $app->infomaniak->token ?></textarea>
            </td>
        </tr>
        <tr>
            <th colspan="2"><h3>speak-up.link</h3></th>
        </tr>
        <tr>
            <th scope="row"><label>ID:</label></th>
            <td>
                <input type="text" value="<?= $app->infomaniak->inbox->{'speak-up.link'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Used Mailboxes:</label></th>
            <td>
                <input type="number" value="<?= $ik->countMailboxes( 'speak-up.link' ) ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <?php if( is_array( $ik->getMailboxIDN( 'speak-up.link' ) ) ) :?>
            <tr>
            <th scope="row"><label>Email Addresses:</label></th>
            <td>
                <textarea  class="regular-text code" rows="5" readonly><?= implode( '&#13;&#10;', $ik->getMailboxIDN( 'speak-up.link' ) ) ?></textarea>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <th colspan="2"><h3>whistle-blowing.org</h3></th>
        </tr>
        <tr>
            <th scope="row"><label>ID:</label></th>
            <td>
                <input type="text" value="<?= $app->infomaniak->inbox->{'whistle-blowing.org'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Used Mailboxes:</label></th>
            <td>
                <input type="number" value="<?= $ik->countMailboxes( 'whistle-blowing.org' ) ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <?php if( is_array( $ik->getMailboxIDN( 'whistle-blowing.org' ) ) ) :?>
            <tr>
            <th scope="row"><label>Email Addresses:</label></th>
            <td>
                <textarea  class="regular-text code" rows="5" readonly><?= implode( '&#13;&#10;', $ik->getMailboxIDN( 'whistle-blowing.org' ) ) ?></textarea>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <th colspan="2"><h3>whistleblowing.direct</h3></th>
        </tr>
        <tr>
            <th scope="row"><label>ID:</label></th>
            <td>
                <input type="text" value="<?= $app->infomaniak->inbox->{'whistleblowing.direct'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Used Mailboxes:</label></th>
            <td>
                <input type="number" value="<?= $ik->countMailboxes( 'whistleblowing.direct' ) ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <?php if( is_array( $ik->getMailboxIDN( 'whistleblowing.direct' ) ) ) :?>
            <tr>
            <th scope="row"><label>Email Addresses:</label></th>
            <td>
                <textarea  class="regular-text code" rows="5" readonly><?= implode( '&#13;&#10;', $ik->getMailboxIDN( 'whistleblowing.direct' ) ) ?></textarea>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <th colspan="2"><h3>whistleblowinghotline.net</h3></th>
        </tr>
        <tr>
            <th scope="row"><label>ID:</label></th>
            <td>
                <input type="text" value="<?= $app->infomaniak->inbox->{'whistleblowinghotline.net'} ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Used Mailboxes:</label></th>
            <td>
                <input type="number" value="<?= $ik->countMailboxes( 'whistleblowinghotline.net' ) ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <?php if( is_array( $ik->getMailboxIDN( 'whistleblowinghotline.net' ) ) ) :?>
            <tr>
            <th scope="row"><label>Email Addresses:</label></th>
            <td>
                <textarea  class="regular-text code" rows="5" readonly><?= implode( '&#13;&#10;', $ik->getMailboxIDN( 'whistleblowinghotline.net' ) ) ?></textarea>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
*/
?>
