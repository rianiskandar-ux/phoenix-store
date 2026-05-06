<?php
/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * Phoenix Text Manager
 * Centralized UI strings untuk: My SaaS, My Add-ons, Billing, Upgrade
 *
 * FILE LOCATION: /wp-content/plugins/wbs-saas/includes/phoenix-text-manager.php
 *
 * CARA LOAD di wbs-saas.php:
 *   require_once plugin_dir_path(__FILE__) . 'includes/phoenix-text-manager.php';
 *
 * USAGE di snippet / file PHP lain:
 *   phoenix_text('my_saas.page_title')
 *   phoenix_text('billing.status_active')
 *   phoenix_text('my_saas.fmt_up_to', 5, 'languages')
 *   phoenix_text_plural('my_addons.active_instance', 3)
 *   phoenix_text_list('upgrade.gains_basic_from_free')   ← returns array
 *   phoenix_text_html('billing.cancel_modal_until', ['date' => '31 Dec 2025'])
 *
 * TEXT DOMAIN: wbs-saas
 * WPML: Scan via WPML → String Translation → Scan → pilih plugin WBS SaaS
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 */

if (!defined('ABSPATH')) exit;


// ── 1. STRINGS ───────────────────────────────────────────────────────────────

if (!function_exists('phoenix_get_text_strings')) {
    function phoenix_get_text_strings() {
        return [

            // ══════════════════════════════════════════════════════
            // MY SAAS INSTANCES
            // ══════════════════════════════════════════════════════
            'my_saas' => [
                'menu_label'            => __('Workspaces', 'wbs-saas-plugin'),
                'page_title'            => __('Phoenix Workspaces', 'wbs-saas-plugin'),
                'page_subtitle'         => __('View and manage all your whistleblowing workspaces.', 'wbs-saas-plugin'),
                'login_required'        => __('Please log in to view your workspaces.', 'wbs-saas-plugin'),
                'system_error'          => __('⚠️ SaaS management system not found. Please contact support.', 'wbs-saas-plugin'),
                'empty_title'           => __('No SaaS Workspaces Yet', 'wbs-saas-plugin'),
                'empty_desc'            => __('Create your first Phoenix Whistleblowing Workspaces to get started.', 'wbs-saas-plugin'),
                'empty_cta'             => __('Create Workspaces →', 'wbs-saas-plugin'),
                'active_singular'       => __('active workspace', 'wbs-saas-plugin'),
                'active_plural'         => __('active workspaces', 'wbs-saas-plugin'),
                'cancelled'             => __('cancelled', 'wbs-saas-plugin'),
                'show_cancelled'        => __('Show Cancelled', 'wbs-saas-plugin'),
                'hide_cancelled'        => __('Hide Cancelled', 'wbs-saas-plugin'),
                'plan_free'             => __('Free', 'wbs-saas-plugin'),
                'plan_basic'            => __('Basic', 'wbs-saas-plugin'),
                'plan_premium'          => __('Premium', 'wbs-saas-plugin'),
                'plan_unknown'          => __('Unknown Plan', 'wbs-saas-plugin'),
                'billing_monthly'       => __('Monthly', 'wbs-saas-plugin'),
                'billing_yearly'        => __('Yearly', 'wbs-saas-plugin'),
                'status_active'         => __('Active', 'wbs-saas-plugin'),
                'status_expiring'       => __('Expiring Soon', 'wbs-saas-plugin'),
                'wizard_title'          => __('Setup not complete', 'wbs-saas-plugin'),
                'wizard_desc'           => __('You\'re almost there! Complete the quick setup to create your admin account and begin using your platform.', 'wbs-saas-plugin'),
                'wizard_desc2'          => __('You must complete setup before upgrading your plan.', 'wbs-saas-plugin'),
                'wizard_cta'            => __('🚀 Go to Setup Wizard →', 'wbs-saas-plugin'),
                'wizard_backplan'       => __('← Back to My Plans', 'wbs-saas-plugin'),
                'wizard_note'           => __('After completing setup, refresh this page to unlock the Upgrade button.', 'wbs-saas-plugin'),
                'wizard_opened'         => __('⏳ Wizard opened — refresh this page after completing setup', 'wbs-saas-plugin'),
                'plan_journey_title'    => __('📈 Plan Journey', 'wbs-saas-plugin'),
                'plan_state_current'    => __('Current', 'wbs-saas-plugin'),
                'plan_state_done'       => __('✓ Done', 'wbs-saas-plugin'),
                'plan_state_none'       => __('—', 'wbs-saas-plugin'),
                'label_website'         => __('Website', 'wbs-saas-plugin'),
                'label_server'          => __('Server Location', 'wbs-saas-plugin'),
                'label_created'         => __('Created', 'wbs-saas-plugin'),
                'label_subscription'    => __('Subscription', 'wbs-saas-plugin'),
                'label_next_payment'    => __('Next Payment', 'wbs-saas-plugin'),
                'label_renewal'         => __('Renewal', 'wbs-saas-plugin'),
                'label_default'         => __('Default', 'wbs-saas-plugin'),
                'free_no_billing'       => __('Free — no billing', 'wbs-saas-plugin'),
                'view_link'             => __('(View)', 'wbs-saas-plugin'),
                'addons_section_label'  => __('🔌 Add-ons', 'wbs-saas-plugin'),
                'addons_free_msg'       => __('Not Available on Free Plan, Upgrade Plan Required', 'wbs-saas-plugin'),
                'addons_browse_btn'     => __('Browse Add-ons', 'wbs-saas-plugin'),
                'channels_title'        => __('📡 Channels', 'wbs-saas-plugin'),
                'channel_webform'       => __('Web Form', 'wbs-saas-plugin'),
                'channel_webform_std'   => __('Standard questionnaire', 'wbs-saas-plugin'),
                'channel_webform_choice'=> __('Choice of %s', 'wbs-saas-plugin'),
                'channel_phone'         => __('Phone', 'wbs-saas-plugin'),
                'channel_email'         => __('Email', 'wbs-saas-plugin'),
                'channel_im'            => __('Instant Messaging', 'wbs-saas-plugin'),
                'channel_postmail'      => __('Post Mail', 'wbs-saas-plugin'),
                'channel_chat'          => __('Chat', 'wbs-saas-plugin'),
                'channel_mobileapp'     => __('Mobile App', 'wbs-saas-plugin'),
                'channel_mobile_active' => __('Active', 'wbs-saas-plugin'),
                'channel_not_included'  => __('Not included', 'wbs-saas-plugin'),
                'cta_buy_channel'       => __('+ Buy extra channel', 'wbs-saas-plugin'),
                'fmt_unlimited'         => __('Unlimited %s', 'wbs-saas-plugin'),
                'fmt_one'               => __('1 %s', 'wbs-saas-plugin'),
                'fmt_up_to'             => __('Up to %d %s', 'wbs-saas-plugin'),
                'users_title'           => __('👥 User Accounts', 'wbs-saas-plugin'),
                'user_manager'          => __('Manager', 'wbs-saas-plugin'),
                'user_operator'         => __('Operator', 'wbs-saas-plugin'),
                'user_agent'            => __('Agent', 'wbs-saas-plugin'),
                'cta_buy_user'          => __('+ Buy extra user', 'wbs-saas-plugin'),
                'languages_label'       => __('🌐 Languages', 'wbs-saas-plugin'),
                'languages_one'         => __('1 language', 'wbs-saas-plugin'),
                'languages_up_to'       => __('Up to %d languages', 'wbs-saas-plugin'),
                'cta_buy_extra'         => __('+ Buy extra', 'wbs-saas-plugin'),
                'themes_label'          => __('🎨 Themes', 'wbs-saas-plugin'),
                'themes_default'        => __('Default theme', 'wbs-saas-plugin'),
                'upgrade_label'         => __('Upgrade Plan', 'wbs-saas-plugin'),
                'new_plan_title'        => __('Need another plan?', 'wbs-saas-plugin'),
                'new_plan_desc'         => __('Set up a new dedicated plan for a new organization.', 'wbs-saas-plugin'),
                'new_plan_cta'          => __('+ Start New Plan', 'wbs-saas-plugin'),
            ],

            // ══════════════════════════════════════════════════════
            // MY ADD-ONS
            // ══════════════════════════════════════════════════════
            'my_addons' => [
                'menu_label'            => __('Add-ons', 'wbs-saas-plugin'),
                'page_title'            => __('Add-ons', 'wbs-saas-plugin'),
                'page_subtitle'         => __('Manage add-ons for each of your Workspaces.', 'wbs-saas-plugin'),
                'login_required'        => __('Please log in to view your add-ons.', 'wbs-saas-plugin'),
                'unavailable'           => __('Add-on information unavailable. Please contact support.', 'wbs-saas-plugin'),
                'empty_title'           => __('No SaaS Workspaces Found', 'wbs-saas-plugin'),
                'empty_desc'            => __('Create a SaaS Workspaces first to manage add-ons.', 'wbs-saas-plugin'),
                'empty_cta'             => __('Get Started →', 'wbs-saas-plugin'),
                'active_instance'       => __('active workspaces|active workspaces', 'wbs-saas-plugin'),
                'inactive'              => __('inactive', 'wbs-saas-plugin'),
                'show_inactive'         => __('Show Inactive', 'wbs-saas-plugin'),
                'hide_inactive'         => __('Hide Inactive', 'wbs-saas-plugin'),
                'badge_unknown'         => __('Unknown', 'wbs-saas-plugin'),
                'badge_payment_issue'   => __('Payment Issue', 'wbs-saas-plugin'),
                'badge_inactive'        => __('Inactive', 'wbs-saas-plugin'),
                'badge_active'          => __('Active', 'wbs-saas-plugin'),
                'badge_renewing'        => __('Renewing Soon', 'wbs-saas-plugin'),
                'billing_monthly'       => __('Monthly', 'wbs-saas-plugin'),
                'billing_yearly'        => __('Yearly', 'wbs-saas-plugin'),
                'plan_suffix'           => __('Plan', 'wbs-saas-plugin'),
                'alert_cancelled'       => __('Subscription Cancelled', 'wbs-saas-plugin'),
                'alert_expired'         => __('Subscription Expired', 'wbs-saas-plugin'),
                'alert_on_hold'         => __('Payment Issue', 'wbs-saas-plugin'),
                'alert_inactive'        => __('Subscription Inactive', 'wbs-saas-plugin'),
                'alert_cancelled_msg'   => __('This subscription has been cancelled. Contact support to reactivate.', 'wbs-saas-plugin'),
                'alert_expired_msg'     => __('This subscription has expired. Please renew to continue using this workspace.', 'wbs-saas-plugin'),
                'alert_on_hold_msg'     => __('Payment failed. Please update your payment method to reactivate.', 'wbs-saas-plugin'),
                'alert_other_msg'       => __('Please contact support for assistance.', 'wbs-saas-plugin'),
                'renewing_title'        => __('Renewing in %d day|Renewing in %d days', 'wbs-saas-plugin'),
                'renewing_msg'          => __('Your subscription renews on %s. Make sure your payment method is up to date.', 'wbs-saas-plugin'),
                'free_locked_title'     => __('⬆️ Add-ons require a paid plan', 'wbs-saas-plugin'),
                'free_locked_desc'      => __('Add-ons such as extra channels, user accounts, and languages are available on Basic and Premium plans. Upgrade to unlock all features for this workspace.', 'wbs-saas-plugin'),
                'free_locked_cta'       => __('⬆️ View Upgrade Plans →', 'wbs-saas-plugin'),
                'active_title'          => __('✅ Active Add-ons', 'wbs-saas-plugin'),
                'active_empty'          => __('No active add-ons for this workspace yet. Add features below.', 'wbs-saas-plugin'),
                'col_addon'             => __('Add-on', 'wbs-saas-plugin'),
                'col_qty'               => __('Qty', 'wbs-saas-plugin'),
                'col_billing'           => __('Billing', 'wbs-saas-plugin'),
                'col_amount'            => __('Amount', 'wbs-saas-plugin'),
                'col_renewal'           => __('Next Renewal', 'wbs-saas-plugin'),
                'period_yearly'         => __('Yearly', 'wbs-saas-plugin'),
                'period_monthly'        => __('Monthly', 'wbs-saas-plugin'),
                'available_title'       => __('🛒 Available Add-ons', 'wbs-saas-plugin'),
                'btn_add'               => __('+ Add', 'wbs-saas-plugin'),
                'btn_active'            => __('✓ Active', 'wbs-saas-plugin'),
                'read_more'             => __('Read more', 'wbs-saas-plugin'),
                'theme_title'           => __('🎨 Theme', 'wbs-saas-plugin'),
                'theme_active_label'    => __('Active', 'wbs-saas-plugin'),
                'theme_add_more'        => __('Add more themes to your plan.', 'wbs-saas-plugin'),
                'theme_browse_txt'      => __('Browse and purchase themes for your workspace.', 'wbs-saas-plugin'),
                'theme_browse_btn'      => __('🎨 Browse Theme →', 'wbs-saas-plugin'),
                'back_link'             => __('← Back to My Workspaces', 'wbs-saas-plugin'),
            ],

            // ══════════════════════════════════════════════════════
            // BILLING
            // ══════════════════════════════════════════════════════
            'billing' => [
                'menu_label'            => __('Billing', 'wbs-saas-plugin'),
                'page_title'            => __('Billing', 'wbs-saas-plugin'),
                'page_subtitle'         => __('All billing per workspace — main plan and add-ons.', 'wbs-saas-plugin'),
                'login_required'        => __('Please log in.', 'wbs-saas-plugin'),
                'unavailable'           => __('Billing information unavailable.', 'wbs-saas-plugin'),
                'empty_title'           => __('No Workspaces Yet', 'wbs-saas-plugin'),
                'empty_cta'             => __('Create workspace →', 'wbs-saas-plugin'),
                'label_active'          => __('active', 'wbs-saas-plugin'),
                'label_cancelled'       => __('cancelled', 'wbs-saas-plugin'),
                'show_cancelled'        => __('Show cancelled', 'wbs-saas-plugin'),
                'status_active'         => __('Active', 'wbs-saas-plugin'),
                'status_cancel_window'  => __('⚠ Cancel window open', 'wbs-saas-plugin'),
                'status_cancels_on'     => __('Cancels on %s', 'wbs-saas-plugin'),
                'status_suspended'      => __('Suspended', 'wbs-saas-plugin'),
                'status_cancelled'      => __('Cancelled', 'wbs-saas-plugin'),
                'status_expired'        => __('Expired', 'wbs-saas-plugin'),
                'plan_free'             => __('Free', 'wbs-saas-plugin'),
                'plan_basic'            => __('Basic', 'wbs-saas-plugin'),
                'plan_premium'          => __('Premium', 'wbs-saas-plugin'),
                'period_monthly'        => __('Monthly', 'wbs-saas-plugin'),
                'period_yearly'         => __('Yearly', 'wbs-saas-plugin'),
                'commitment_title'      => __('📋 12-Month Commitment', 'wbs-saas-plugin'),
                'commitment_progress'   => __('%d / 12 months', 'wbs-saas-plugin'),
                'commitment_renewal'    => __('Next renewal: %s', 'wbs-saas-plugin'),
                'cancel_window_title'   => __('Cancellation window open.', 'wbs-saas-plugin'),
                'cancel_window_ends'    => __('Commitment ends', 'wbs-saas-plugin'),
                'cancel_window_before'  => __('Cancel before', 'wbs-saas-plugin'),
                'cancel_window_charge'  => __('to avoid being charged for the next month.', 'wbs-saas-plugin'),
                'cancel_window_manage'  => __('Manage subscription →', 'wbs-saas-plugin'),
                'trial_title'           => __('⏳ Trial Period', 'wbs-saas-plugin'),
                'trial_ends_label'      => __('Trial ends:', 'wbs-saas-plugin'),
                'trial_upgrade_note'    => __('Upgrade to Basic or Premium to continue after your trial.', 'wbs-saas-plugin'),
                'upcoming_title'        => __('📅 Upcoming Billing', 'wbs-saas-plugin'),
                'upcoming_total'        => __('Total', 'wbs-saas-plugin'),
                'upcoming_next_billing' => __('Next billing:', 'wbs-saas-plugin'),
                'upcoming_aligned'      => __('✓ All charges on same date', 'wbs-saas-plugin'),
                'upcoming_misaligned'   => __('⚠ Some charges on different dates', 'wbs-saas-plugin'),
                'themes_title'          => __('🎨 Purchased Themes', 'wbs-saas-plugin'),
                'themes_purchased'      => __('%d theme purchased|%d themes purchased', 'wbs-saas-plugin'),
                'themes_total_spent'    => __('Total spent:', 'wbs-saas-plugin'),
                'themes_view_invoices'  => __('📄 View Invoices', 'wbs-saas-plugin'),
                'themes_modal_title'    => __('🎨 Theme Purchases — %s', 'wbs-saas-plugin'),
                'themes_browse_btn'     => __('🎨 Browse More →', 'wbs-saas-plugin'),
                'themes_col_date'       => __('Date', 'wbs-saas-plugin'),
                'themes_col_theme'      => __('Theme', 'wbs-saas-plugin'),
                'themes_col_amount'     => __('Amount', 'wbs-saas-plugin'),
                'history_title'         => __('🧾 Billing History', 'wbs-saas-plugin'),
                'history_empty'         => __('No billing history yet.', 'wbs-saas-plugin'),
                'history_col_date'      => __('Date', 'wbs-saas-plugin'),
                'history_col_desc'      => __('Description', 'wbs-saas-plugin'),
                'history_col_type'      => __('Type', 'wbs-saas-plugin'),
                'history_col_amount'    => __('Amount', 'wbs-saas-plugin'),
                'history_col_status'    => __('Status', 'wbs-saas-plugin'),
                'tag_prorated'          => __('Prorated', 'wbs-saas-plugin'),
                'tag_renewal'           => __('Renewal', 'wbs-saas-plugin'),
                'tag_addon'             => __('Add-on', 'wbs-saas-plugin'),
                'tag_plan'              => __('Plan', 'wbs-saas-plugin'),
                'invoice_btn'           => __('📄 Invoice', 'wbs-saas-plugin'),
                'btn_cancel'            => __('✕ Cancel', 'wbs-saas-plugin'),
                'btn_renew'             => __('↻ Renew Now', 'wbs-saas-plugin'),
                'btn_reactivate'        => __('↩ Reactivate', 'wbs-saas-plugin'),
                'cancel_modal_title'    => __('Cancel Subscription?', 'wbs-saas-plugin'),
                'cancel_modal_about'    => __('You are about to cancel', 'wbs-saas-plugin'),
                'cancel_modal_until'    => __('Your subscription will remain <strong>active until %s</strong>.', 'wbs-saas-plugin'),
                'cancel_modal_after'    => __('After that, your workspace and all its add-ons will be deactivated.', 'wbs-saas-plugin'),
                'cancel_modal_warn'     => __('This action cannot be undone.', 'wbs-saas-plugin'),
                'btn_keep'              => __('Keep Subscription', 'wbs-saas-plugin'),
                'btn_confirm_cancel'    => __('Yes, Cancel', 'wbs-saas-plugin'),
                'btn_cancelling'        => __('Cancelling...', 'wbs-saas-plugin'),
                'btn_reactivating'      => __('Reactivating...', 'wbs-saas-plugin'),
                'msg_cancelled'         => __('Subscription cancelled. You will have access until %s.', 'wbs-saas-plugin'),
                'msg_not_active'        => __('Subscription is not active.', 'wbs-saas-plugin'),
                'msg_not_pending'       => __('Subscription is not pending cancellation.', 'wbs-saas-plugin'),
                'msg_reactivated'       => __('Subscription reactivated successfully.', 'wbs-saas-plugin'),
                'msg_access_denied'     => __('Access denied.', 'wbs-saas-plugin'),
                'msg_not_logged_in'     => __('Not logged in.', 'wbs-saas-plugin'),
                'msg_invalid_sub'       => __('Invalid subscription.', 'wbs-saas-plugin'),
                'msg_not_found'         => __('Subscription not found.', 'wbs-saas-plugin'),
                'msg_something_wrong'   => __('✗ Something went wrong. Please try again.', 'wbs-saas-plugin'),
                'payment_modal_title'   => __('Update Payment Method', 'wbs-saas-plugin'),
                'payment_modal_subtitle'=> __('Your new card will be used for all active subscriptions.', 'wbs-saas-plugin'),
                'payment_save_btn'      => __('Save New Card', 'wbs-saas-plugin'),
                'payment_processing'    => __('Processing...', 'wbs-saas-plugin'),
                'payment_saved'         => __('Card Saved!', 'wbs-saas-plugin'),
                'payment_card_updated'  => __('✓ Card updated: %s ending in %s (exp %s)', 'wbs-saas-plugin'),
                'payment_updated_msg'   => __('Payment method updated successfully.', 'wbs-saas-plugin'),
            ],

            // ══════════════════════════════════════════════════════
            // UPGRADE PAGE
            // ══════════════════════════════════════════════════════
            'upgrade' => [
                'page_title'            => __('Upgrade Plan', 'wbs-saas-plugin'),
                'back_link'             => __('← Back to My Workspaces', 'wbs-saas-plugin'),
                'error_no_sub'          => __('⚠️ No subscription selected.', 'wbs-saas-plugin'),
                'error_access_denied'   => __('❌ Access denied.', 'wbs-saas-plugin'),
                'error_not_active'      => __('⚠️ Subscription not active.', 'wbs-saas-plugin'),
                'error_addon_only'      => __('⚠️ Add-on subscriptions cannot be upgraded here.', 'wbs-saas-plugin'),
                'error_go_plans'        => __('Go to My Plans →', 'wbs-saas-plugin'),
                'already_premium_title' => __('✅ You are already on the Premium plan', 'wbs-saas-plugin'),
                'already_premium_sub'   => __('This is the highest available plan. Your workspace details are shown below.', 'wbs-saas-plugin'),
                'instance_details'      => __('workspace details', 'wbs-saas-plugin'),
                'label_organisation'    => __('Organisation', 'wbs-saas-plugin'),
                'label_server'          => __('Server location', 'wbs-saas-plugin'),
                'label_subdomain'       => __('Subdomain', 'wbs-saas-plugin'),
                'label_domain'          => __('Domain', 'wbs-saas-plugin'),
                'ent_desc_premium'      => __('Need more capacity, a dedicated server, or custom features? Contact us.', 'wbs-saas-plugin'),
                'badge_free'            => __('🌱 Free', 'wbs-saas-plugin'),
                'badge_basic'           => __('📦 Basic', 'wbs-saas-plugin'),
                'billing_monthly'       => __('Monthly', 'wbs-saas-plugin'),
                'billing_yearly'        => __('Yearly', 'wbs-saas-plugin'),
                'cycle_label'           => __('Billing cycle:', 'wbs-saas-plugin'),
                'cycle_monthly'         => __('Monthly', 'wbs-saas-plugin'),
                'cycle_yearly'          => __('Yearly', 'wbs-saas-plugin'),
                'popular_badge'         => __('⭐ Most Popular', 'wbs-saas-plugin'),
                'price_per_year_note'   => __('/year (pay upfront)', 'wbs-saas-plugin'),
                'price_save_fmt'        => __('Save %s — Equiv. %s/month', 'wbs-saas-plugin'),
                'gains_title'           => __('What you gain with %s:', 'wbs-saas-plugin'),
                'btn_upgrade'           => __('UPGRADE NOW', 'wbs-saas-plugin'),
                'btn_processing'        => __('⏳ Processing...', 'wbs-saas-plugin'),
                'btn_contact'           => __('CONTACT US →', 'wbs-saas-plugin'),
                'notice_preparing'      => __('Preparing your upgrade — please wait...', 'wbs-saas-plugin'),
                'notice_ready'          => __('✅ Upgrade ready — redirecting to checkout...', 'wbs-saas-plugin'),
                'notice_redirecting'    => __('Redirecting to checkout...', 'wbs-saas-plugin'),
                'notice_error'          => __('Error: %s', 'wbs-saas-plugin'),
                'notice_network'        => __('Network error — please try again.', 'wbs-saas-plugin'),
                'addons_section_title'  => __('Add-ons', 'wbs-saas-plugin'),
                'addons_free_subtitle'  => __('Available after upgrading. Basic plan unlocks most add-ons, Premium plan unlocks all including Online Chat.', 'wbs-saas-plugin'),
                'addon_premium_only'    => __('Premium plan only', 'wbs-saas-plugin'),
                'addon_available_after' => __('Available after upgrading', 'wbs-saas-plugin'),
                'addons_basic_owned'        => __('Your %d active add-on%s will continue working after upgrading.', 'wbs-saas-plugin'),
                'addons_basic_owned_note'   => __('No interruption, add-ons stay linked to your workspace. You can also purchase additional add-ons from My Add-ons after upgrading.', 'wbs-saas-plugin'),
                'addons_basic_none'         => __('Add-ons are available for your plan.', 'wbs-saas-plugin'),
                'addons_basic_none_note'    => __('After upgrading, purchase add-ons from My Add-ons. Premium unlocks all add-ons including Online Chat.', 'wbs-saas-plugin'),
                'ent_label'             => __('🏢 Enterprise', 'wbs-saas-plugin'),
                'ent_desc_from_free'    => __('Not ready for Basic/Premium yet? Skip directly to Enterprise for white-glove service and custom pricing.', 'wbs-saas-plugin'),
                'ent_desc_from_basic'   => __('Need more than Premium? Dedicated account manager, custom domain & server location, unlimited users & languages.', 'wbs-saas-plugin'),

                // Gains arrays — tiap item tetap translatable via __()
                'gains_basic_from_free' => [
                    __('Multi-channel communication — Add Email, Phone, Instant Messaging, and a physical address → make your business more accessible and professional', 'wbs-saas-plugin'),
                    __('Support for 2 languages', 'wbs-saas-plugin'),
                    __('More design flexibility (3 themes)', 'wbs-saas-plugin'),
                    __('Customer support with ticketing system', 'wbs-saas-plugin'),
                    __('Access to add-ons', 'wbs-saas-plugin'),
                ],
                'gains_premium_from_basic' => [
                    __('Live interaction (Online Chat Room)', 'wbs-saas-plugin'),
                    __('Customizable web forms', 'wbs-saas-plugin'),
                    __('Multi-role team access (Manager, Operator, Agent)', 'wbs-saas-plugin'),
                    __('Full access to theme library', 'wbs-saas-plugin'),
                    __('Custom domain name', 'wbs-saas-plugin'),
                    __('Greater scalability', 'wbs-saas-plugin'),
                ],
                'gains_premium_from_free' => [
                    __('Multi-channel communication — Add Email, Phone, Instant Messaging, and a physical address → make your business more accessible and professional', 'wbs-saas-plugin'),
                    __('Live interaction (Online Chat Room)', 'wbs-saas-plugin'),
                    __('Customizable web forms', 'wbs-saas-plugin'),
                    __('Multi-role team access (Manager, Operator, Agent)', 'wbs-saas-plugin'),
                    __('Support for 2 languages', 'wbs-saas-plugin'),
                    __('Full access to theme library', 'wbs-saas-plugin'),
                    __('Custom domain name', 'wbs-saas-plugin'),
                    __('Greater scalability', 'wbs-saas-plugin'),
                    __('Access to add-ons', 'wbs-saas-plugin'),
                ],
            ],

            // ══════════════════════════════════════════════════════
            // COMMON / SHARED
            // ══════════════════════════════════════════════════════
            'common' => [
                'monthly'       => __('Monthly', 'wbs-saas-plugin'),
                'yearly'        => __('Yearly', 'wbs-saas-plugin'),
                'per_month'     => __('/month', 'wbs-saas-plugin'),
                'per_year'      => __('/year', 'wbs-saas-plugin'),
                'active'        => __('Active', 'wbs-saas-plugin'),
                'inactive'      => __('Inactive', 'wbs-saas-plugin'),
                'cancelled'     => __('Cancelled', 'wbs-saas-plugin'),
                'unknown'       => __('Unknown', 'wbs-saas-plugin'),
                'not_included'  => __('Not included', 'wbs-saas-plugin'),
                'processing'    => __('Processing...', 'wbs-saas-plugin'),
                'error'         => __('Error', 'wbs-saas-plugin'),
                'network_error' => __('Network error — please try again.', 'wbs-saas-plugin'),
            ],
        ];
    }
}


// ── 2. HELPER FUNCTIONS ───────────────────────────────────────────────────────

if (!function_exists('phoenix_text')) {
    /**
     * Get a UI string by dot-notation key.
     * Supports sprintf args: phoenix_text('my_saas.fmt_up_to', 5, 'languages')
     */
    function phoenix_text($key, ...$args) {
        static $strings = null;
        if ($strings === null) $strings = phoenix_get_text_strings();

        $parts = explode('.', $key);
        $value = $strings;
        foreach ($parts as $p) {
            if (!isset($value[$p])) {
                error_log('[Phoenix Text] Missing key: ' . $key);
                return $key;
            }
            $value = $value[$p];
        }

        if (!empty($args) && is_string($value)) {
            return sprintf($value, ...$args);
        }
        return $value;
    }
}

if (!function_exists('phoenix_text_plural')) {
    /**
     * Singular/plural via pipe separator.
     * e.g. 'active workspace|active workspaces' → '3 active workspaces'
     */
    function phoenix_text_plural($key, $count) {
        $text = phoenix_text($key);
        if (is_string($text) && strpos($text, '|') !== false) {
            $forms = explode('|', $text);
            $text  = ($count === 1) ? $forms[0] : $forms[1];
        }
        return is_string($text) && strpos($text, '%d') !== false
            ? sprintf($text, $count)
            : $count . ' ' . $text;
    }
}

if (!function_exists('phoenix_text_list')) {
    /**
     * Get an array value (e.g. gains lists).
     * Returns [] if key is not an array.
     */
    function phoenix_text_list($key) {
        $val = phoenix_text($key);
        return is_array($val) ? $val : [];
    }
}

if (!function_exists('phoenix_text_html')) {
    /**
     * Get string and replace %placeholder% tokens.
     * e.g. phoenix_text_html('billing.cancel_modal_until', ['date' => '31 Dec 2025'])
     */
    function phoenix_text_html($key, $replacements = []) {
        $text = phoenix_text($key);
        foreach ($replacements as $token => $val) {
            $text = str_replace('%' . $token . '%', $val, $text);
        }
        return $text;
    }
}