<?php

/**
 * Provide the admin area to view Versioning
 * 
 * @see wbssaas_admin_settings()
 * 
 * @since 2.2.0
 */

?>

<!-- TABLE GIT -->
<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row"><label>WP Plugin Version:</label></th>
            <td>
                <?= WBSSAAS_PLUGIN_VERSION ?>
            </td>
        </tr>
        <tr>
            <th colspan="2"><h3>Git</h3></th>
        </tr>
        <tr>
            <th scope="row"><label>Current Branch:</label></th>
            <td>
                <input type="text" value="<?= \WBSSaaS\Git::getBranch() ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Date:</label></th>
            <td>
                <input type="text" value="<?= \WBSSaaS\Git::getDate() ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Current Commit:</label></th>
            <td>
                <input type="text" value="<?= \WBSSaaS\Git::getCommit() ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Hash:</label></th>
            <td>
                <input type="text" value="<?= \WBSSaaS\Git::getHash() ?>" class="regular-text code" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Gitlab:</label></th>
            <td>
                <p><a href="https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/commit/<?= \WBSSaaS\Git::getHash() ?>" target="_blank"><?= \WBSSaaS\Git::getBranch() ?>#<?= \WBSSaaS\Git::getCommit() ?></a></p>
            </td>
        </tr>
    </tbody>
</table>