
// ================================================================
// SNIPPET: Merge Account Details + Billing Address
// Menggabungkan halaman "Account Details" dan "Addresses" (billing only)
// dalam 1 endpoint: /my-account/account-detail/
// Shipping address disembunyikan — tidak relevan untuk SaaS
// ================================================================

// ── 1. Tambah endpoint baru ──────────────────────────────────────
add_action('init', 'phoenix_register_account_detail_endpoint');
function phoenix_register_account_detail_endpoint() {
    add_rewrite_endpoint('account-detail', EP_ROOT | EP_PAGES);
}

// ── 2. Ganti menu item "edit-account" dan "edit-address" ────────
add_filter('woocommerce_account_menu_items', 'phoenix_merge_account_menu_items', 20);
function phoenix_merge_account_menu_items($items) {
    $new = [];
    foreach ($items as $key => $label) {
        // Skip menu lama yang digabung
        if (in_array($key, ['edit-account', 'edit-address'])) continue;
        $new[$key] = $label;
        // Sisipkan menu baru setelah "orders"
        if ($key === 'orders') {
            $new['account-detail'] = 'Account Details';
        }
    }
    return $new;
}

// ── 3. Render halaman gabungan ───────────────────────────────────
add_action('woocommerce_account_account-detail_endpoint', 'phoenix_render_account_detail_page');
function phoenix_render_account_detail_page() {
    $user_id = get_current_user_id();
    $user    = get_userdata($user_id);

    // Handle form submission — Account Details
    if (isset($_POST['phoenix_save_account']) && wp_verify_nonce($_POST['_nonce_account'], 'phoenix_account_detail')) {
        $errors = new WP_Error();

        $first_name   = sanitize_text_field($_POST['account_first_name'] ?? '');
        $last_name    = sanitize_text_field($_POST['account_last_name']  ?? '');
        $display_name = sanitize_text_field($_POST['account_display_name'] ?? '');
        $email        = sanitize_email($_POST['account_email'] ?? '');
        $pass_current = $_POST['password_current'] ?? '';
        $pass_new     = $_POST['password_1']       ?? '';
        $pass_confirm = $_POST['password_2']       ?? '';

        if (empty($first_name))   $errors->add('first_name', 'First name is required.');
        if (empty($last_name))    $errors->add('last_name',  'Last name is required.');
        if (empty($display_name)) $errors->add('display_name', 'Display name is required.');
        if (empty($email) || !is_email($email)) $errors->add('email', 'Please enter a valid email address.');

        // Cek email sudah dipakai user lain
        if ($email && $email !== $user->user_email) {
            $existing = get_user_by('email', $email);
            if ($existing && $existing->ID !== $user_id) {
                $errors->add('email', 'This email address is already registered.');
            }
        }

        // Password change
        if ($pass_new) {
            if (!$pass_current || !wp_check_password($pass_current, $user->user_pass, $user_id)) {
                $errors->add('password_current', 'Your current password is incorrect.');
            }
            if ($pass_new !== $pass_confirm) {
                $errors->add('password_confirm', 'Passwords do not match.');
            }
        }

        if (!$errors->has_errors()) {
            $user_data = [
                'ID'           => $user_id,
                'first_name'   => $first_name,
                'last_name'    => $last_name,
                'display_name' => $display_name,
                'user_email'   => $email,
            ];
            if ($pass_new) $user_data['user_pass'] = $pass_new;
            wp_update_user($user_data);
            wc_add_notice('Account details updated successfully.', 'success');
            // Reload supaya notice tampil dan form terupdate
            wp_safe_redirect(wc_get_account_endpoint_url('account-detail'));
            exit;
        } else {
            foreach ($errors->get_error_messages() as $msg) {
                wc_add_notice($msg, 'error');
            }
        }
    }

    // Handle form submission — Billing Address
    if (isset($_POST['phoenix_save_billing']) && wp_verify_nonce($_POST['_nonce_billing'], 'phoenix_billing_detail')) {
        $address_fields = WC()->countries->get_address_fields(
            sanitize_text_field($_POST['billing_country'] ?? ''),
            'billing_'
        );
        $errors = new WP_Error();

        foreach ($address_fields as $key => $field) {
            if (!empty($field['required']) && empty($_POST[$key])) {
                $errors->add($key, $field['label'] . ' is required.');
            }
        }

        if (!$errors->has_errors()) {
            foreach ($address_fields as $key => $field) {
                $val = sanitize_text_field($_POST[$key] ?? '');
                update_user_meta($user_id, $key, $val);
            }

            // Sync ke subscription aktif kalau user centang
            if (!empty($_POST['update_subscription_billing'])) {
                $subs = function_exists('wcs_get_users_subscriptions')
                    ? wcs_get_users_subscriptions($user_id) : [];
                foreach ($subs as $sub) {
                    if (!$sub->has_status(['active', 'on-hold'])) continue;
                    foreach ($address_fields as $key => $field) {
                        $meta_key = str_replace('billing_', '', $key);
                        $sub->update_meta_data('_billing_' . $meta_key, sanitize_text_field($_POST[$key] ?? ''));
                    }
                    $sub->save();
                }
            }

            wc_add_notice('Billing address updated successfully.', 'success');
            wp_safe_redirect(wc_get_account_endpoint_url('account-detail'));
            exit;
        } else {
            foreach ($errors->get_error_messages() as $msg) {
                wc_add_notice($msg, 'error');
            }
        }
    }

    // Reload user setelah save
    $user = get_userdata($user_id);

    // Billing address fields
    $billing_fields = WC()->countries->get_address_fields(
        get_user_meta($user_id, 'billing_country', true) ?: WC()->countries->get_base_country(),
        'billing_'
    );

    // WC notices
    wc_print_notices();
    ?>

    <style>
    #pup-acct { font-family: inherit; max-width: 760px; }
    #pup-acct * { box-sizing: border-box; }
    #pup-acct .pua-section {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        margin-bottom: 24px;
        overflow: hidden;
    }
    #pup-acct .pua-section-header {
        padding: 14px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    #pup-acct .pua-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }
    #pup-acct .pua-section-body { padding: 22px 20px; }
    #pup-acct .pua-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    #pup-acct .pua-row.full { grid-template-columns: 1fr; }
    @media (max-width: 560px) { #pup-acct .pua-row { grid-template-columns: 1fr; } }
    #pup-acct .pua-field { display: flex; flex-direction: column; gap: 5px; }
    #pup-acct .pua-label {
        font-size: 12px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    #pup-acct .pua-label .req { color: #e53935; margin-left: 2px; }
    #pup-acct .pua-input {
        padding: 9px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 13px;
        color: #2c3e50;
        width: 100%;
        transition: border-color .2s;
        font-family: inherit;
    }
    #pup-acct .pua-input:focus { outline: none; border-color: #3498db; }
    #pup-acct .pua-input-hint { font-size: 11px; color: #999; margin-top: 2px; }
    #pup-acct .pua-divider { height: 1px; background: #f0f0f0; margin: 18px 0; }
    #pup-acct .pua-subsection-title {
        font-size: 12px;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 14px;
    }
    #pup-acct .pua-checkbox-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        margin-top: 4px;
        margin-bottom: 16px;
    }
    #pup-acct .pua-checkbox-row input { margin-top: 2px; flex-shrink: 0; }
    #pup-acct .pua-checkbox-label { font-size: 12px; color: #555; line-height: 1.5; }
    #pup-acct .pua-btn {
        display: inline-block;
        padding: 10px 22px;
        background: #2c3e50;
        color: #fff;
        border: none;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity .2s;
        font-family: inherit;
    }
    #pup-acct .pua-btn:hover { opacity: .85; }
    #pup-acct select.pua-input {
        appearance: none;
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 30px;
    }
    </style>

    <div id="pup-acct">
        <h2 style="margin-bottom:20px;font-size:18px;color:#2c3e50;">Account Details</h2>

        <!-- ── Section 1: Personal Info + Password ── -->
        <div class="pua-section">
            <div class="pua-section-header">
                <span style="font-size:18px;">👤</span>
                <span class="pua-section-title">Personal Information</span>
            </div>
            <div class="pua-section-body">
                <form method="post" action="">
                    <?php wp_nonce_field('phoenix_account_detail', '_nonce_account'); ?>

                    <div class="pua-row">
                        <div class="pua-field">
                            <label class="pua-label">First name <span class="req">*</span></label>
                            <input type="text" name="account_first_name" class="pua-input"
                                value="<?php echo esc_attr($user->first_name); ?>" required>
                        </div>
                        <div class="pua-field">
                            <label class="pua-label">Last name <span class="req">*</span></label>
                            <input type="text" name="account_last_name" class="pua-input"
                                value="<?php echo esc_attr($user->last_name); ?>" required>
                        </div>
                    </div>

                    <div class="pua-row full">
                        <div class="pua-field">
                            <label class="pua-label">Display name <span class="req">*</span></label>
                            <input type="text" name="account_display_name" class="pua-input"
                                value="<?php echo esc_attr($user->display_name); ?>" required>
                            <span class="pua-input-hint">This will be shown in the account section and in reviews.</span>
                        </div>
                    </div>

                    <div class="pua-row full">
                        <div class="pua-field">
                            <label class="pua-label">Email address <span class="req">*</span></label>
                            <input type="email" name="account_email" class="pua-input"
                                value="<?php echo esc_attr($user->user_email); ?>" required>
                        </div>
                    </div>

                    <div class="pua-divider"></div>
                    <div class="pua-subsection-title">🔒 Change Password</div>

                    <div class="pua-row full">
                        <div class="pua-field">
                            <label class="pua-label">Current password</label>
                            <input type="password" name="password_current" class="pua-input"
                                placeholder="Leave blank to keep unchanged">
                        </div>
                    </div>
                    <div class="pua-row">
                        <div class="pua-field">
                            <label class="pua-label">New password</label>
                            <input type="password" name="password_1" class="pua-input"
                                placeholder="Leave blank to keep unchanged">
                        </div>
                        <div class="pua-field">
                            <label class="pua-label">Confirm new password</label>
                            <input type="password" name="password_2" class="pua-input"
                                placeholder="Confirm new password">
                        </div>
                    </div>

                    <button type="submit" name="phoenix_save_account" class="pua-btn">Save Personal Info</button>
                </form>
            </div>
        </div>

        <!-- ── Section 2: Billing Address ── -->
        <div class="pua-section">
            <div class="pua-section-header">
                <span style="font-size:18px;">🏦</span>
                <span class="pua-section-title">Billing Address</span>
            </div>
            <div class="pua-section-body">
                <form method="post" action="">
                    <?php wp_nonce_field('phoenix_billing_detail', '_nonce_billing'); ?>

                    <?php
                    // Render billing fields dari WooCommerce
                    $skip_fields = ['billing_country']; // country kita render manual di bawah
                    $country_val = get_user_meta($user_id, 'billing_country', true) ?: WC()->countries->get_base_country();
                    ?>

                    <div class="pua-row">
                        <div class="pua-field">
                            <label class="pua-label">First name <span class="req">*</span></label>
                            <input type="text" name="billing_first_name" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_first_name', true)); ?>">
                        </div>
                        <div class="pua-field">
                            <label class="pua-label">Last name <span class="req">*</span></label>
                            <input type="text" name="billing_last_name" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_last_name', true)); ?>">
                        </div>
                    </div>

                    <div class="pua-row full">
                        <div class="pua-field">
                            <label class="pua-label">Company (optional)</label>
                            <input type="text" name="billing_company" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_company', true)); ?>">
                        </div>
                    </div>

                    <div class="pua-row full">
                        <div class="pua-field">
                            <label class="pua-label">Country <span class="req">*</span></label>
                            <select name="billing_country" class="pua-input" id="billing_country">
                                <?php foreach (WC()->countries->get_countries() as $code => $name): ?>
                                <option value="<?php echo esc_attr($code); ?>"
                                    <?php selected($country_val, $code); ?>>
                                    <?php echo esc_html($name); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="pua-row full">
                        <div class="pua-field">
                            <label class="pua-label">Address line 1 <span class="req">*</span></label>
                            <input type="text" name="billing_address_1" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_address_1', true)); ?>">
                        </div>
                    </div>

                    <div class="pua-row full">
                        <div class="pua-field">
                            <label class="pua-label">Address line 2</label>
                            <input type="text" name="billing_address_2" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_address_2', true)); ?>">
                        </div>
                    </div>

                    <div class="pua-row">
                        <div class="pua-field">
                            <label class="pua-label">City <span class="req">*</span></label>
                            <input type="text" name="billing_city" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_city', true)); ?>">
                        </div>
                        <div class="pua-field">
                            <label class="pua-label">State / Province</label>
                            <input type="text" name="billing_state" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_state', true)); ?>">
                        </div>
                    </div>

                    <div class="pua-row">
                        <div class="pua-field">
                            <label class="pua-label">Postcode / ZIP <span class="req">*</span></label>
                            <input type="text" name="billing_postcode" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_postcode', true)); ?>">
                        </div>
                        <div class="pua-field">
                            <label class="pua-label">Phone</label>
                            <input type="tel" name="billing_phone" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_phone', true)); ?>">
                        </div>
                    </div>

                    <div class="pua-row full">
                        <div class="pua-field">
                            <label class="pua-label">Email address <span class="req">*</span></label>
                            <input type="email" name="billing_email" class="pua-input"
                                value="<?php echo esc_attr(get_user_meta($user_id, 'billing_email', true) ?: $user->user_email); ?>">
                        </div>
                    </div>

                    <div class="pua-checkbox-row">
                        <input type="checkbox" name="update_subscription_billing" id="upd_sub_billing" value="1">
                        <label for="upd_sub_billing" class="pua-checkbox-label">
                            Update the billing address used for all future renewals of my active subscriptions (optional)
                        </label>
                    </div>

                    <button type="submit" name="phoenix_save_billing" class="pua-btn">Save Billing Address</button>
                </form>
            </div>
        </div>

    </div>
    <?php
}

// ── 4. Redirect endpoint lama ke endpoint baru ───────────────────
add_action('template_redirect', 'phoenix_redirect_old_account_endpoints');
function phoenix_redirect_old_account_endpoints() {
    if (!is_user_logged_in()) return;
    global $wp;
    $current = $wp->request ?? '';

    // Redirect /my-account/edit-account/ dan /my-account/edit-address/ ke endpoint baru
    if (preg_match('#my-account/(edit-account|edit-address)#', $current)) {
        wp_safe_redirect(wc_get_account_endpoint_url('account-detail'), 301);
        exit;
    }
}