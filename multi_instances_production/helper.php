/**
 * SNIPPET: Helper Functions - Phoenix Plan Management
 * 
 * Consolidate duplicate code across all snippets
 * These functions are used by:
 * - Auto-cancel lower plan
 * - Track highest plan
 * - Prefill forms
 * - My SaaS page
 * - My Addons page
 */

// ============================================================================
// PHOENIX_TEXT FALLBACK
// Loaded first (helper priority 5) so all snippets get all keys.
// Real phoenix_text() from wbs-saas-wp plugin takes precedence when available.
// ============================================================================
if (!function_exists('phoenix_text')) {
    function phoenix_text($key, ...$args) {
        static $fb = null;
        if ($fb === null) $fb = [
            // ── My Workspaces ──────────────────────────────────────────────
            'my_saas.menu_label'            => 'My Workspaces',
            'my_saas.page_title'            => 'My Workspaces',
            'my_saas.page_subtitle'         => 'Manage your whistleblowing instances.',
            'my_saas.login_required'        => 'Please log in to view your workspaces.',
            'my_saas.system_error'          => 'System error. Please contact support.',
            'my_saas.empty_title'           => 'No workspaces yet',
            'my_saas.empty_cta'             => 'View Plans',
            'my_saas.active_plural'         => 'active workspaces',
            'my_saas.active_singular'       => 'active workspace',
            'my_saas.cancelled'             => 'cancelled',
            'my_saas.show_cancelled'        => 'Show cancelled',
            'my_saas.hide_cancelled'        => 'Hide cancelled',
            'my_saas.billing_yearly'        => 'Yearly',
            'my_saas.billing_monthly'       => 'Monthly',
            'my_saas.fmt_unlimited'         => 'Unlimited %s',
            'my_saas.fmt_one'               => '1 %s',
            'my_saas.fmt_up_to'             => 'Up to %d %s',
            'my_saas.channel_not_included'  => 'Not included',
            'my_saas.status_active'         => 'Active',
            'my_saas.status_expiring'       => 'Expiring soon',
            'my_saas.btn_setup_first'       => 'Complete Setup First',
            'my_saas.upgrade_label'         => 'Upgrade',
            'my_saas.plan_free'             => 'Free',
            'my_saas.plan_basic'            => 'Basic',
            'my_saas.plan_premium'          => 'Premium',
            'my_saas.plan_enterprise'       => 'Enterprise',
            'my_saas.wizard_title'          => 'Setup Wizard',
            'my_saas.wizard_desc'           => 'Complete your workspace setup to get started.',
            'my_saas.wizard_desc2'          => 'Setup is required before upgrading.',
            'my_saas.wizard_desc2_addons'   => 'Setup is required before purchasing add-ons.',
            'my_saas.wizard_cta'            => 'Open Setup Wizard',
            'my_saas.wizard_opened'         => 'Opened',
            'my_saas.wizard_note'           => 'The wizard will guide you through initial configuration.',
            'my_saas.wizard_note_addons'    => 'Complete setup before purchasing add-ons.',
            'my_saas.wizard_gate_title'     => 'Setup Required',
            'my_saas.wizard_gate_msg'       => 'Please complete the setup wizard before upgrading.',
            'my_saas.label_website'         => 'Website',
            'my_saas.label_server'          => 'Server',
            'my_saas.label_default'         => 'Default',
            'my_saas.label_created'         => 'Created',
            'my_saas.label_subscription'    => 'Subscription',
            'my_saas.label_next_payment'    => 'Next payment',
            'my_saas.label_renewal'         => 'Renewal date',
            'my_saas.view_link'             => 'View',
            'my_saas.free_no_billing'       => 'Free — no billing',
            'my_saas.addons_section_label'  => 'Add-ons',
            'my_saas.addons_free_msg'       => 'Upgrade to access add-ons.',
            'my_saas.addons_browse_btn'     => 'Browse Add-ons',
            'my_saas.channels_title'        => 'Reporting Channels',
            'my_saas.channel_webform'       => 'Web Form',
            'my_saas.channel_webform_choice'=> 'Custom: %s',
            'my_saas.channel_webform_std'   => 'Standard',
            'my_saas.channel_phone'         => 'Phone',
            'my_saas.channel_email'         => 'Email',
            'my_saas.channel_im'            => 'Instant Messaging',
            'my_saas.channel_postmail'      => 'Post Mail',
            'my_saas.channel_chat'          => 'Live Chat',
            'my_saas.channel_mobileapp'     => 'Mobile App',
            'my_saas.channel_mobile_active' => 'Active',
            'my_saas.cta_buy_channel'       => 'Add more channels',
            'my_saas.users_title'           => 'Users',
            'my_saas.user_manager'          => 'Manager',
            'my_saas.user_operator'         => 'Operator',
            'my_saas.user_agent'            => 'Agent',
            'my_saas.cta_buy_user'          => 'Add more users',
            'my_saas.languages_label'       => 'Languages',
            'my_saas.languages_one'         => '1 language',
            'my_saas.languages_up_to'       => 'Up to %d languages',
            'my_saas.cta_buy_extra'         => 'Add more languages',
            'my_saas.themes_label'          => 'Themes',
            'my_saas.themes_default'        => 'Default',
            'my_saas.cta_buy_theme'         => 'Browse themes',
            'my_saas.new_plan_title'        => 'Looking for a new plan?',
            'my_saas.new_plan_desc'         => 'Upgrade or add a new workspace.',
            'my_saas.new_plan_cta'          => 'View Plans',
            // ── My Add-ons ─────────────────────────────────────────────────
            'my_addons.menu_label'          => 'My Add-ons',
            'my_addons.page_title'          => 'My Add-ons',
            'my_addons.page_subtitle'       => 'Manage your active add-ons.',
            'my_addons.login_required'      => 'Please log in to view your add-ons.',
            'my_addons.unavailable'         => 'Add-ons information is currently unavailable.',
            'my_addons.empty_title'         => 'No add-ons yet',
            'my_addons.empty_desc'          => 'Enhance your workspace with powerful add-ons.',
            'my_addons.empty_cta'           => 'Browse Add-ons',
            'my_addons.badge_unknown'       => 'Unknown',
            'my_addons.badge_payment_issue' => 'Payment Issue',
            'my_addons.badge_inactive'      => 'Inactive',
            'my_addons.badge_active'        => 'Active',
            'my_addons.badge_renewing'      => 'Renewing',
            'my_addons.badge_upgrading_yearly' => 'Upgrading to Yearly',
            'my_addons.hide_inactive'       => 'Hide Inactive',
            'my_addons.show_inactive'       => 'Show Inactive',
            'my_addons.billing_yearly'      => 'Yearly',
            'my_addons.billing_monthly'     => 'Monthly',
            'my_addons.plan_suffix'         => 'plan',
            'my_addons.alert_cancelled'     => 'Cancelled',
            'my_addons.alert_expired'       => 'Expired',
            'my_addons.alert_on_hold'       => 'On Hold',
            'my_addons.alert_inactive'      => 'Inactive',
            'my_addons.alert_cancelled_msg' => 'This add-on has been cancelled.',
            'my_addons.alert_expired_msg'   => 'This add-on has expired.',
            'my_addons.alert_on_hold_msg'   => 'This add-on is on hold.',
            'my_addons.alert_other_msg'     => 'This add-on is inactive.',
            'my_addons.renewing_msg'        => 'Renews on %s',
            'my_addons.renewal_on'          => 'Renewal on %s',
            'my_addons.free_locked_title'   => 'Upgrade Required',
            'my_addons.free_locked_desc'    => 'Upgrade your plan to access add-ons.',
            'my_addons.active_empty'        => 'No active add-ons.',
            'my_addons.col_addon'           => 'Add-on',
            'my_addons.col_qty'             => 'Qty',
            'my_addons.col_billing'         => 'Billing',
            'my_addons.col_amount'          => 'Amount',
            'my_addons.col_renewal'         => 'Next Renewal',
            'my_addons.period_yearly'       => 'Yearly',
            'my_addons.period_monthly'      => 'Monthly',
            'my_addons.read_more'           => 'Read more',
            'my_addons.btn_active'          => 'Active',
            'my_addons.btn_add'             => 'Add',
            'my_addons.theme_active_label'  => 'Active',
            'my_addons.theme_add_more'      => 'Add more themes',
            'my_addons.theme_browse_txt'    => 'Browse Themes',
            'my_addons.back_link'           => '← Back to My Workspaces',
            // ── My Billing ─────────────────────────────────────────────────
            'billing.menu_label'            => 'My Billing',
            'billing.page_title'            => 'Billing',
            'billing.page_subtitle'         => 'Manage your subscriptions and billing.',
            'billing.login_required'        => 'Please log in to view your billing.',
            'billing.unavailable'           => 'Billing information is currently unavailable.',
            'billing.empty_title'           => 'No active subscriptions',
            'billing.empty_cta'             => 'View Plans',
            'billing.status_active'         => 'Active',
            'billing.status_cancel_window'  => 'Cancel Window Open',
            'billing.status_cancels_on'     => 'Cancels on %s',
            'billing.status_suspended'      => 'Suspended',
            'billing.status_cancelled'      => 'Cancelled',
            'billing.status_expired'        => 'Expired',
            'billing.period_yearly'         => 'Yearly',
            'billing.period_monthly'        => 'Monthly',
            'billing.plan_free'             => 'Free',
            'billing.plan_basic'            => 'Basic',
            'billing.plan_premium'          => 'Premium',
            'billing.plan_enterprise'       => 'Enterprise',
            'billing.btn_reactivate'        => 'Reactivate',
            'billing.btn_cancel'            => 'Cancel',
            'billing.btn_renew'             => 'Renew',
            'billing.btn_keep'              => 'Keep Subscription',
            'billing.btn_confirm_cancel'    => 'Yes, Cancel',
            'billing.btn_cancelling'        => 'Cancelling...',
            'billing.btn_reactivating'      => 'Reactivating...',
            'billing.title_reactivate'      => 'Reactivate subscription',
            'billing.title_cancel'          => 'Cancel subscription',
            'billing.title_renew'           => 'Renew now',
            'billing.commitment_title'      => 'Commitment',
            'billing.commitment_progress'   => '%d months completed',
            'billing.commitment_renewal'    => 'Auto-renews on %s',
            'billing.trial_section_title'   => 'Free Trial',
            'billing.trial_ends_label'      => 'Trial ends:',
            'billing.trial_upgrade_note'    => 'Upgrade to keep your data.',
            'billing.upcoming_title'        => 'Upcoming Payment',
            'billing.upcoming_addon_subtotal'=> 'Add-ons subtotal',
            'billing.upcoming_total'        => 'Total',
            'billing.upcoming_next_billing' => 'Next billing:',
            'billing.upcoming_aligned'      => 'Billing aligned',
            'billing.upcoming_misaligned'   => 'Billing not yet aligned',
            'billing.themes_title'          => 'Themes',
            'billing.themes_total_spent'    => 'Total spent:',
            'billing.themes_view_invoices'  => 'View Invoices',
            'billing.themes_modal_title'    => 'Theme Invoices — %s',
            'billing.themes_col_date'       => 'Date',
            'billing.themes_col_theme'      => 'Theme',
            'billing.themes_col_amount'     => 'Amount',
            'billing.themes_browse_btn'     => 'Browse Themes',
            'billing.history_title'         => 'Billing History',
            'billing.history_empty'         => 'No billing history.',
            'billing.history_col_date'      => 'Date',
            'billing.history_col_desc'      => 'Description',
            'billing.history_col_type'      => 'Type',
            'billing.history_col_amount'    => 'Amount',
            'billing.history_col_status'    => 'Status',
            'billing.history_show_all'      => 'Show all',
            'billing.history_show_less'     => 'Show less',
            'billing.invoice_btn'           => 'Invoice',
            'billing.tag_prorated'          => 'Prorated',
            'billing.tag_renewal'           => 'Renewal',
            'billing.tag_addon'             => 'Add-on',
            'billing.tag_plan'              => 'Plan',
            'billing.cancel_modal_title'    => 'Cancel Subscription',
            'billing.js_cancel_about'       => 'You are about to cancel your subscription.',
            'billing.js_cancel_until'       => 'You will have access until',
            'billing.js_cancel_after'       => 'After this date, your account will be downgraded.',
            'billing.js_cancel_warn'        => 'This action cannot be undone.',
            'billing.js_something_wrong'    => 'Something went wrong. Please try again.',
            'billing.reactivated_success'   => 'Subscription reactivated successfully.',
            'billing.reactivated_failed'    => 'Failed to reactivate. Please try again.',
            'billing.msg_not_logged_in'     => 'Not logged in.',
            'billing.msg_stripe_no_customer'=> 'No Stripe customer found.',
            'billing.msg_gateway_not_configured' => 'Payment gateway not configured.',
            'billing.msg_gateway_connect_failed' => 'Could not connect to payment gateway.',
            'billing.msg_stripe_error'      => 'Stripe error.',
            'billing.msg_invalid_pm'        => 'Invalid payment method.',
            'billing.msg_config_error'      => 'Configuration error.',
            'billing.msg_pm_update_failed'  => 'Payment method update failed.',
            'billing.msg_pm_init_failed'    => 'Failed to initialise payment form.',
            'billing.msg_card_updated'      => 'Card updated successfully.',
            'billing.msg_invalid_sub'       => 'Invalid subscription.',
            'billing.msg_not_found'         => 'Subscription not found.',
            'billing.msg_access_denied'     => 'Access denied.',
            'billing.msg_not_active'        => 'Subscription is not active.',
            'billing.msg_not_pending'       => 'No pending cancellation.',
            'billing.msg_cancelled'         => 'Subscription will cancel on %s.',
            'billing.msg_reactivated'       => 'Subscription reactivated.',
            'billing.note_card_reactivated' => 'Card updated and subscription reactivated.',
            'billing.payment_updated_msg'   => 'Payment method updated.',
            'billing.payment_save_btn'      => 'Save Card',
            'billing.payment_saved'         => 'Saved',
            'billing.payment_modal_title'   => 'Update Payment Method',
            'billing.payment_modal_subtitle'=> 'Enter your new card details.',
            'billing.payment_processing'    => 'Processing...',
            // ── Common ─────────────────────────────────────────────────────
            'common.unknown'                => 'Unknown',
            'gf_guard.subdomain_taken'      => 'The subdomain "%s" is already registered. Please choose a different subdomain.',
        ];
        $text = $fb[$key] ?? $key;
        return $args ? vsprintf($text, $args) : $text;
    }
}

// ============================================================================
// PLAN HIERARCHY & DETECTION
// ============================================================================

/**
 * Get plan hierarchy mapping
 * Single source of truth for plan levels
 * 
 * @return array Product ID => Level (1=Free, 2=Basic, 3=Premium)
 */
function phoenix_get_plan_hierarchy() {
    return [
        30688 => 1, // Free Plan
        11    => 1, // Starter Plan (variation)
        30689 => 1, // Free variation
        58    => 2, // Basic Plan
        61    => 2, // Basic Monthly variation
        62    => 2, // Basic Yearly variation
        22    => 2, // Standard Plan (variation)
        76    => 3, // Premium Plan
        78    => 3, // Premium Monthly variation
        79    => 3, // Premium Yearly variation
        33    => 3, // Custom Plan (variation)
    ];
}

/**
 * Get plan level from product ID or name
 * 
 * @param int    $product_id Product ID (optional)
 * @param string $name       Product name (optional)
 * @return int   Plan level (0=unknown, 1=Free, 2=Basic, 3=Premium)
 */
function phoenix_get_plan_level($product_id = 0, $name = '') {
    $hierarchy = phoenix_get_plan_hierarchy();
    
    // Try product ID first
    if ($product_id && isset($hierarchy[$product_id])) {
        return $hierarchy[$product_id];
    }
    
    // Try parent product ID for variations
    if ($product_id) {
        $product = wc_get_product($product_id);
        if ($product && method_exists($product, 'get_parent_id')) {
            $parent_id = $product->get_parent_id();
            if ($parent_id && isset($hierarchy[$parent_id])) {
                return $hierarchy[$parent_id];
            }
        }
    }
    
    // Fallback to name detection
    if ($name) {
        $n = strtolower($name);
        if (strpos($n, 'premium') !== false || strpos($n, 'custom') !== false || strpos($n, 'byo') !== false) {
            return 3;
        }
        if (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false) {
            return 2;
        }
        if (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) {
            return 1;
        }
    }
    
    return 0; // Unknown
}

/**
 * Get plan level from subscription item
 * 
 * @param object $item          WooCommerce order item
 * @param array  $plan_hierarchy Optional custom hierarchy
 * @return int   Plan level
 */
function phoenix_get_plan_level_from_item($item, $plan_hierarchy = null) {
    if (!$plan_hierarchy) {
        $plan_hierarchy = phoenix_get_plan_hierarchy();
    }
    
    $pid = $item->get_product_id();
    $level = isset($plan_hierarchy[$pid]) ? $plan_hierarchy[$pid] : 0;
    
    if ($level === 0) {
        $level = phoenix_get_plan_level($pid, $item->get_name());
    }
    
    return $level;
}

// ============================================================================
// SUBSCRIPTION HELPERS
// ============================================================================

/**
 * Check if subscription is addon
 * 
 * @param WC_Subscription $subscription
 * @return bool
 */
function phoenix_is_addon_subscription($subscription) {
    if (!is_a($subscription, 'WC_Subscription')) {
        return false;
    }
    
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            return true;
        }
    }
    
    return false;
}

/**
 * Check if subscription is main plan (not addon)
 * 
 * @param WC_Subscription $subscription
 * @return bool
 */
function phoenix_is_main_plan_subscription($subscription) {
    if (!is_a($subscription, 'WC_Subscription')) {
        return false;
    }
    
    // Skip if addon
    if (phoenix_is_addon_subscription($subscription)) {
        return false;
    }
    
    // Check if has valid plan product
    $hierarchy = phoenix_get_plan_hierarchy();
    foreach ($subscription->get_items() as $item) {
        $pid = $item->get_product_id();
        if (isset($hierarchy[$pid])) {
            return true;
        }
        
        // Check by name
        $level = phoenix_get_plan_level(0, $item->get_name());
        if ($level > 0) {
            return true;
        }
    }
    
    return false;
}

/**
 * Get plan name from subscription
 * 
 * @param WC_Subscription $subscription
 * @return string Plan name or empty
 */
function phoenix_get_plan_name($subscription) {
    if (!is_a($subscription, 'WC_Subscription')) {
        return '';
    }
    
    foreach ($subscription->get_items() as $item) {
        // Skip addons
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            continue;
        }
        
        return $item->get_name();
    }
    
    return '';
}

/**
 * Get plan level from subscription
 * 
 * @param WC_Subscription $subscription
 * @return int Plan level (0=unknown)
 */
function phoenix_get_subscription_plan_level($subscription) {
    if (!is_a($subscription, 'WC_Subscription')) {
        return 0;
    }
    
    $hierarchy = phoenix_get_plan_hierarchy();
    
    foreach ($subscription->get_items() as $item) {
        // Skip addons
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            continue;
        }
        
        return phoenix_get_plan_level_from_item($item, $hierarchy);
    }
    
    return 0;
}

// ============================================================================
// TENANT / INSTANCE HELPERS
// ============================================================================

/**
 * Get tenant by subscription ID
 * 
 * @param int $subscription_id WooCommerce Subscription ID
 * @return object|null Tenant object or null
 */
function phoenix_get_tenant_by_subscription($subscription_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    
    return $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE subscription_wc_id = %d LIMIT 1",
        $subscription_id
    ));
}

/**
 * Get all tenants for user
 * 
 * @param int $user_id WordPress user ID
 * @return array Array of tenant objects
 */
function phoenix_get_user_tenants($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE customer_id = %d ORDER BY created DESC",
        $user_id
    ));
}

/**
 * Check if two subscriptions belong to same tenant/instance
 * 
 * @param int $sub_id_1
 * @param int $sub_id_2
 * @return bool
 */
function phoenix_same_instance($sub_id_1, $sub_id_2) {
    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    
    $tenant_1 = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE subscription_wc_id = %d",
        $sub_id_1
    ));
    
    $tenant_2 = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE subscription_wc_id = %d",
        $sub_id_2
    ));
    
    return $tenant_1 && $tenant_2 && $tenant_1 === $tenant_2;
}

// ============================================================================
// BILLING / PERIOD HELPERS
// ============================================================================

/**
 * Check if subscription is yearly
 * 
 * @param WC_Subscription $subscription
 * @return bool
 */
function phoenix_is_yearly_subscription($subscription) {
    if (!is_a($subscription, 'WC_Subscription')) {
        return false;
    }
    
    $period = $subscription->get_billing_period();
    $interval = $subscription->get_billing_interval();
    
    return ($period === 'year') || ($period === 'month' && (int)$interval >= 12);
}

/**
 * Get billing period label (Monthly/Yearly)
 * 
 * @param WC_Subscription $subscription
 * @return string
 */
function phoenix_get_billing_period_label($subscription) {
    return phoenix_is_yearly_subscription($subscription) ? 'Yearly' : 'Monthly';
}

/**
 * Calculate commitment progress (for 12-month commitment)
 * 
 * @param WC_Subscription $subscription
 * @return array ['months' => int, 'total' => 12, 'percentage' => float, 'complete' => bool]
 */
function phoenix_get_commitment_progress($subscription, $start_override = null) {
    if (!is_a($subscription, 'WC_Subscription')) {
        return ['months' => 0, 'total' => 12, 'percentage' => 0, 'complete' => false];
    }

    // Priority 1: explicit override (dari My SaaS yang cari oldest sub)
    // Priority 2: meta _commitment_start_date (disimpan saat upgrade)
    // Priority 3: start date subscription itu sendiri
    if ($start_override) {
        $start = is_numeric($start_override) ? (int)$start_override : strtotime($start_override);
    } elseif ($meta = $subscription->get_meta('_commitment_start_date')) {
        $start = (int)$meta;
    } else {
        $start_date = $subscription->get_date('start');
        if (!$start_date) {
            return ['months' => 0, 'total' => 12, 'percentage' => 0, 'complete' => false];
        }
        $start = strtotime($start_date);
    }

    $now = time();
    // Hitung total bulan sejak awal komitmen
    $start_year  = (int)date('Y', $start);
    $start_month = (int)date('n', $start);
    $now_year    = (int)date('Y', $now);
    $now_month   = (int)date('n', $now);
    $diff_months = max(0, ($now_year - $start_year) * 12 + ($now_month - $start_month));

    // Komitmen cyclic: setiap 12 bulan auto-renew dan mulai cycle baru
    // Bulan ke-12 (kelipatan 12) ditampilkan sebagai 12/12 — window sudah tutup,
    // cancel CTA hilang, auto-renew akan jalan.
    if ($diff_months > 0 && $diff_months % 12 === 0) {
        $cycle_index     = (int) floor($diff_months / 12) - 1;
        $months_in_cycle = 12; // tampil 12/12, window sudah tutup
    } else {
        $cycle_index     = (int) floor($diff_months / 12);
        $months_in_cycle = $diff_months % 12;
    }
    $cycle_start_ts = strtotime("+{$cycle_index} years", $start);
    $percentage     = ($months_in_cycle / 12) * 100;

    return [
        'months'     => $months_in_cycle,
        'total'      => 12,
        'percentage' => $percentage,
        'complete'   => false, // commitment tidak pernah selesai — selalu renew
        'start_ts'   => $cycle_start_ts,
        'cycle'      => $cycle_index,
    ];
}

// ============================================================================
// FORMATTING HELPERS
// ============================================================================

/**
 * Format price with currency
 * 
 * @param float  $amount
 * @param string $currency Optional, defaults to shop currency
 * @return string Formatted price
 */
function phoenix_format_price($amount, $currency = null) {
    if (!$currency) {
        $currency = get_woocommerce_currency();
    }
    
    return wc_price($amount, ['currency' => $currency]);
}

/**
 * Format date in user-friendly format
 * 
 * @param string $date Date string
 * @param string $format Optional format (default: F d, Y)
 * @return string Formatted date
 */
function phoenix_format_date($date, $format = 'F d, Y') {
    if (!$date || $date === '0000-00-00 00:00:00') {
        return '-';
    }
    
    return date($format, strtotime($date));
}

// ============================================================================
// VALIDATION HELPERS
// ============================================================================

/**
 * Check if table exists
 * 
 * @param string $table_name Table name without prefix
 * @return bool
 */
function phoenix_table_exists($table_name) {
    global $wpdb;
    $table = $wpdb->prefix . $table_name;
    return $wpdb->get_var("SHOW TABLES LIKE '$table'") === $table;
}

/**
 * Validate subscription belongs to user
 * 
 * @param int $subscription_id
 * @param int $user_id
 * @return bool
 */
function phoenix_user_owns_subscription($subscription_id, $user_id) {
    if (!function_exists('wcs_get_subscription')) {
        return false;
    }
    
    $subscription = wcs_get_subscription($subscription_id);
    if (!$subscription) {
        return false;
    }
    
    return $subscription->get_user_id() === $user_id;
}


// ============================================================================
// VALIDATION NEW INSTANCE
// ============================================================================

// Clear cart kalau user buka halaman plan untuk new instance
add_action('template_redirect', 'clear_cart_for_new_instance');
function clear_cart_for_new_instance() {
    if (!is_product()) return;
    if (!is_user_logged_in()) return;

    global $post;
    if (!in_array($post->ID, [58, 76, 30688])) return;

    // Ada upgrade/addon parameter = jangan clear
    if (isset($_GET['upgrade_subscription']) || 
        isset($_GET['switch-subscription']) ||
        isset($_GET['tenant_uuid'])) return; // addon flow pakai tenant_uuid

    // New instance = clear cart + clear session
    if (WC()->cart && !WC()->cart->is_empty()) {
        WC()->cart->empty_cart();
    }
    if (WC()->session) {
        WC()->session->set('upgrade_from_subscription', null);
    }
}

// Bypass WCS "only one subscription" restriction untuk new instance
add_filter('woocommerce_subscriptions_product_add_to_cart_validation', 
    'bypass_single_sub_restriction_new_instance', 1, 2);
function bypass_single_sub_restriction_new_instance($passed, $product_id) {
    // Kalau ini upgrade/addon flow — jangan bypass, biarkan WCS handle normal
    if (isset($_GET['upgrade_subscription']) || 
        isset($_GET['switch-subscription']) ||
        isset($_GET['tenant_uuid'])) { // addon flow pakai tenant_uuid
        return $passed;
    }
    
    // New instance — clear cart dulu sebelum add
    if (WC()->cart && !WC()->cart->is_empty()) {
        WC()->cart->empty_cart();
    }
    
    return $passed;
}


// Fix GF double add-to-cart — clear cart sebelum GF add product
add_filter('woocommerce_add_to_cart_validation', 'fix_gf_double_add_to_cart', 1, 3);
function fix_gf_double_add_to_cart($passed, $product_id, $quantity) {
    // Hanya untuk GF submission
    if (!isset($_POST['gform_form_id'])) return $passed;
    
    // Jangan clear kalau ini addon/upgrade flow
    if (isset($_GET['tenant_uuid']) || // addon flow pakai tenant_uuid
        isset($_POST['upgrade_subscription'])) return $passed;
    
    // Clear cart dulu sebelum add — prevent double subscription conflict
    if (WC()->cart && !WC()->cart->is_empty()) {
        WC()->cart->empty_cart();
    }
    
    return $passed;
}

// Hide payment on FREE Plan
// Hide Clear/Reset variation button di semua product page (global)
add_action('wp_head', 'phoenix_hide_reset_variations_global');
function phoenix_hide_reset_variations_global() {
    if (!is_product()) return;
    ?>
    <style>
    .reset_variations {
        display: none !important;
        visibility: hidden !important;
    }
    </style>
    <?php
}

add_action('wp_head', 'hide_variation_selector_free_plan');
function hide_variation_selector_free_plan() {
    if (!is_product()) return;
    global $post;
    if ($post->ID !== 30688) return; // hanya free plan
    ?>
    <style>
    table.variations,
    .variations_form .reset_variations,
    .wc-no-matching-variations {
        display: none !important;
    }
    </style>
    <?php
}

// ================================================================
// ADDON PRODUCT PAGE: Force payment variation sesuai period plan aktif
// - Payment (Monthly/Yearly) → force-select + disabled (readonly)
// - Role (Account Manager/Agent) → tetap visible, user bisa pilih
// ================================================================
add_action('wp_footer', 'phoenix_force_addon_payment_variation');
function phoenix_force_addon_payment_variation() {
    if (!is_product()) return;
    global $post;

    // Hanya untuk produk kategori add-on
    if (!has_term('add-on', 'product_cat', $post->ID)) return;

    // Ambil tenant_uuid dari URL atau session
    $tenant_uuid = '';
    if (!empty($_GET['tenant_uuid'])) {
        $tenant_uuid = sanitize_text_field($_GET['tenant_uuid']);
    } elseif (WC()->session) {
        $tenant_uuid = WC()->session->get('addon_tenant_uuid', '');
    }

    if (!$tenant_uuid) return;

    // Cari plan aktif instance ini — ambil billing_period dari subscription
    global $wpdb;
    $tenant = $wpdb->get_row($wpdb->prepare(
        "SELECT subscription_wc_id FROM {$wpdb->prefix}wbssaas_tenants
         WHERE tenant_uuid = %s AND customer_id = %d LIMIT 1",
        $tenant_uuid,
        get_current_user_id()
    ));

    if (!$tenant || !$tenant->subscription_wc_id) return;

    $sub = wcs_get_subscription($tenant->subscription_wc_id);
    if (!$sub || !$sub->has_status('active')) return;

    $is_yearly     = ($sub->get_billing_period() === 'year');
    $force_payment = $is_yearly ? 'Yearly' : 'Monthly';
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var forcePayment = <?php echo json_encode($force_payment); ?>;

        function lockPaymentVariation() {
            // Cari semua select variation di form
            var selects = document.querySelectorAll('.variations select');
            if (!selects.length) return false;

            selects.forEach(function(sel) {
                var name  = sel.getAttribute('name') || '';
                var label = '';
                // Cari label dari row th
                var row = sel.closest('tr');
                if (row) {
                    var th = row.querySelector('th label, th');
                    if (th) label = th.textContent.toLowerCase();
                }

                var isPayment = name.toLowerCase().indexOf('payment') !== -1
                             || label.indexOf('payment') !== -1
                             || label.indexOf('billing') !== -1;

                if (isPayment) {
                    // Force select sesuai plan aktif instance
                    for (var i = 0; i < sel.options.length; i++) {
                        var v = sel.options[i].value;
                        if (v.toLowerCase() === forcePayment.toLowerCase()) {
                            sel.value = v;
                            sel.dispatchEvent(new Event('change', {bubbles: true}));
                            break;
                        }
                    }
                    // Disable — user tidak bisa ganti period
                    sel.setAttribute('disabled', 'disabled');
                    sel.style.background    = '#f0f0f0';
                    sel.style.cursor        = 'not-allowed';
                    sel.style.borderColor   = '#ccc';
                    sel.style.pointerEvents = 'none';

                    // Hidden input supaya value terkirim meski disabled
                    var hName = sel.getAttribute('name');
                    if (hName && !document.querySelector('input[name="' + hName + '"][type="hidden"]')) {
                        var hi = document.createElement('input');
                        hi.type  = 'hidden';
                        hi.name  = hName;
                        hi.value = sel.value;
                        sel.parentNode.appendChild(hi);
                    }

                    // Tambah label info ke user
                    if (!sel.parentNode.querySelector('.phoenix-period-lock')) {
                        var note = document.createElement('small');
                        note.className   = 'phoenix-period-lock';
                        note.style.color = '#888';
                        note.style.display = 'block';
                        note.style.marginTop = '4px';
                        note.textContent = 'Matches your ' + forcePayment + ' plan';
                        sel.parentNode.appendChild(note);
                    }

                    // Hide Clear button — user tidak boleh reset payment variation
                    var clearBtn = sel.parentNode.querySelector('.reset_variations');
                    if (clearBtn) {
                        clearBtn.style.display    = 'none';
                        clearBtn.style.visibility = 'hidden';
                    }
                }
                // Role dan variation lain → biarkan, user bisa pilih bebas
            });

            return true;
        }

        // Retry sampai variation form render
        var attempts = 0;
        var interval = setInterval(function() {
            attempts++;
            if (lockPaymentVariation() || attempts >= 20) clearInterval(interval);
        }, 300);
    });
    </script>
    <?php
}


// ============================================================================
// PRICE FORMATTING — Remove decimal zeros on non-checkout pages
// 
// Removes trailing .00 or ,00 from all WC prices EXCEPT cart & checkout.
// This means: single product page, My Add-ons, Billing → "$65" not "$65.00"
//             Cart & Checkout → untouched, WC default (e.g. "$65,00 / month")
//
// Uses formatted_woocommerce_price filter (runs after wc_price() formats the number).
// Signature: ($price_html, $price, $decimals, $decimal_separator, $thousand_separator, $symbol)
// ============================================================================
add_filter('formatted_woocommerce_price', 'phoenix_trim_price_zeros', 10, 6);
function phoenix_trim_price_zeros($formatted, $price, $decimals, $decimal_separator, $thousand_separator, $symbol) {
    // Leave cart and checkout untouched
    if (function_exists('is_cart') && is_cart())         return $formatted;
    if (function_exists('is_checkout') && is_checkout()) return $formatted;

    // Remove trailing decimal zeros: .00  ,00  . 00  , 00
    // Pattern: decimal separator + two zeros + optional space before closing </span> or end
    $formatted = preg_replace(
        '/(' . preg_quote($decimal_separator, '/') . ')0{2}(?=\s*<\/span>|\s*$)/',
        '',
        $formatted
    );

    return $formatted;
}


/*// TEMPORARY DEBUG — hapus setelah selesai
add_action('woocommerce_check_cart_items', function() {
    if (!current_user_can('administrator')) return;
    echo '<pre style="background:#000;color:#0f0;padding:20px;z-index:9999;position:relative;">';
    echo 'NOTICES (error):' . PHP_EOL;
    print_r(wc_get_notices('error'));
    echo PHP_EOL . 'CART:' . PHP_EOL;
    foreach (WC()->cart->get_cart() as $item) {
        $product = $item['data'];
        echo '- ' . $product->get_name() . PHP_EOL;
        echo '  purchasable: ' . ($product->is_purchasable() ? 'YES' : 'NO') . PHP_EOL;
        echo '  is_in_stock: ' . ($product->is_in_stock() ? 'YES' : 'NO') . PHP_EOL;
        echo '  type: ' . $product->get_type() . PHP_EOL;
    }
    echo '</pre>';
});

add_action('woocommerce_before_checkout_form', function() {
    if (!current_user_can('administrator')) return;
    echo '<pre style="background:#000;color:#0f0;padding:20px;position:relative;z-index:9999;">';
    echo 'ALL NOTICES AT CHECKOUT:' . PHP_EOL;
    print_r(wc_get_notices());
    echo PHP_EOL . 'CART CONTENTS:' . PHP_EOL;
    foreach (WC()->cart->get_cart() as $item) {
        echo '- ' . $item['data']->get_name() . ' | purchasable:' . ($item['data']->is_purchasable() ? 'YES' : 'NO') . PHP_EOL;
    }
    echo PHP_EOL . 'USER ACTIVE SUBS:' . PHP_EOL;
    foreach (wcs_get_users_subscriptions(get_current_user_id()) as $sub) {
        echo '- #' . $sub->get_id() . ' | status:' . $sub->get_status() . PHP_EOL;
        foreach ($sub->get_items() as $item) {
            echo '  product: ' . $item->get_name() . PHP_EOL;
        }
    }
    echo '</pre>';
}, 1);
*/