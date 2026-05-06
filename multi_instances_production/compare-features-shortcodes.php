
/**
 * SNIPPET: Pricing Comparison Table — Shortcodes + WPML Translation Strings
 *
 * Registers all feature table strings for WPML translation (icl_register_string).
 * Each feature/tooltip is exposed as a shortcode for use in the page editor table.
 *
 * Plan names: Starter | Basic | Premium | Enterprise
 */

function register_compare_feature_strings_for_translation() {
    $strings = [
        // Header
        'Features', 'Starter', 'Basic', 'Premium', 'Enterprise',
        'Standard', 'Build your own', // kept for backward compatibility with existing WPML translations

        // Reporting Channels
        'Reporting Channels', 'Web form', 'Email address', 'Phone number display',
        'Instant messaging display', 'Postal address display', 'Chat Room',

        // User Accounts & Case Management
        'User Accounts & Case Management', 'Single Account', 'Multi-User Accounts',
        'Case Management Dashboard',

        // Language & Localisation
        'Language & Localisation', 'Single Language', 'Multi-Languages',

        // Themes & Customisation
        'Themes & Customisation', 'Phoenix Web Domain', 'Custom Domain',
        'Theme Selection', 'White-Label Branding', 'Advanced Customization',

        // Server & Security
        'Server & Security', 'Anonymous Reporting', 'Data Encryption & Security Compliance',
        'Standard Server Location', 'Custom Server Location',

        // Assistance
        'Assistance', 'Self-serve knowledge base', 'Ticketing Support',
        'Priority Customer Support', 'Dedicated Account Manager',

        // Payment
        'Payment', 'Choice of Currency', 'Payment and Terms of Payment',

        // Upgrading
        'Upgrading', 'Upgrade Options',

        // Add-ons
        'Add-ons', 'Available',

        // Content Table
        'Various Currencies', 'Various Billing and Terms of Payment Options', 'By Credit Card',
        'Options to upgrade to Premium or Enterprise', 'Options to add features or upgrade to Enterprise',
        'Options to upgrade to Build your own or Enterprise', // kept for backward compatibility
    ];

    $tooltips = [
        'Web form tooltip'                              => 'Collect disclosures via a customizable web form.',
        'Email address tooltip'                         => 'Choose your secure email address, with domain name provided by Phoenix.',
        'Phone number display tooltip'                  => 'Add your phone number for real-time whistleblower reporting.',
        'Instant messaging display tooltip'             => 'Display your phone number with your preferred Instant messaging such as WhatsApp, Skype, Telegram enabled for easy communication.',
        'Postal address display tooltip'                => 'Add your postal address for an option to receive mail.',
        'Chat Room tooltip'                             => 'Use Phoenix secure, confidential chat rooms for communication.',
        'Single Account tooltip'                        => 'One account, ideal for a small institution.',
        'Multi-User Accounts tooltip'                   => 'Create and manage multiple user accounts with different roles (up to three accounts for the Basic edition).',
        'Case Management Dashboard tooltip'             => 'Manage and track reports through a centralized dashboard.',
        'Single Language tooltip'                       => 'User interface available in only one language.',
        'Multi-Languages tooltip'                       => 'Select and upgrade with a choice of over 50 languages for global accessibility to whistleblowers.',
        'Phoenix Web Domain tooltip'                    => 'Use the default Phoenix domains for your platform.',
        'Custom Domain tooltip'                         => 'Use your own domain for a branded platform.',
        'Theme Selection tooltip'                       => 'Choose from various themes to match your brand.',
        'White-Label Branding tooltip'                  => 'Rebrand the platform with your own logo/design.',
        'Advanced Customization tooltip'                => 'Customize the platform to meet your needs.',
        'Anonymous Reporting tooltip'                   => 'Enable whistleblowers to submit disclosures anonymously.',
        'Data Encryption & Security Compliance tooltip' => 'Ensure all data is encrypted and meets security standards.',
        'Standard Server location tooltip'              => 'Choose from three predefined server locations.',
        'Custom Server location tooltip'                => 'Choose your preferred location.',
        'Self-Serve Knowledge Base (SLA) tooltip'       => 'Provide a self-service knowledge base for user support.',
        'Ticketing Support tooltip'                     => 'Track and manage your support tickets seamlessly.',
        'Priority Customer Support tooltip'             => 'Get priority support for faster issue resolution.',
        'Dedicated Account Manager tooltip'             => 'Benefit from a dedicated account manager for personalized support.',
        'Choice of currency tooltip'                    => 'Choice of currencies.',
        'Upgrade Options tooltip'                       => 'Upgrade to additional features or advanced plans.',
        'Available tooltip'                             => 'Expand your plan with additional channels, users, languages, and themes.',
        'Add-ons tooltip'                               => 'Expand your plan with additional channels, users, languages, and themes.',
    ];

    foreach ($strings as $string) {
        icl_register_string('compare_feature', $string, $string);
    }
    foreach ($tooltips as $key => $tooltip) {
        icl_register_string('compare_feature', $key, $tooltip);
    }
}
add_action('init', 'register_compare_feature_strings_for_translation');

// ================================================================
// Header
// ================================================================
function shortcode_features() {
    return __('Features', 'compare_feature');
}
add_shortcode('features', 'shortcode_features');

function shortcode_starter() {
    return __('Starter', 'compare_feature');
}
add_shortcode('starter', 'shortcode_starter');

// [standard] kept for backward compatibility — displays "Basic"
function shortcode_basic_plan() {
    return __('Basic', 'compare_feature');
}
add_shortcode('standard', 'shortcode_basic_plan');
add_shortcode('basic', 'shortcode_basic_plan');

// [byo] kept for backward compatibility — displays "Premium"
function shortcode_premium_plan() {
    return __('Premium', 'compare_feature');
}
add_shortcode('byo', 'shortcode_premium_plan');
add_shortcode('premium', 'shortcode_premium_plan');

function shortcode_enterprise() {
    return __('Enterprise', 'compare_feature');
}
add_shortcode('enterprise', 'shortcode_enterprise');

// ================================================================
// Reporting Channels
// ================================================================
function shortcode_channels() {
    return __('Reporting Channels', 'compare_feature');
}
add_shortcode('channels', 'shortcode_channels');

function shortcode_web_form() {
    $tooltip = __('Collect disclosures via a customizable web form.', 'compare_feature');
    return '<div>' . __('Web form', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('web_form', 'shortcode_web_form');

function shortcode_email_address() {
    $tooltip = __('Choose your secure email address, with domain name provided by Phoenix.', 'compare_feature');
    return '<div>' . __('Email address', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('email_address', 'shortcode_email_address');

function shortcode_phone_number_display() {
    $tooltip = __('Add your phone number for real-time whistleblower reporting.', 'compare_feature');
    return '<div>' . __('Phone number display', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('phone_number_display', 'shortcode_phone_number_display');

function shortcode_instant_messaging_display() {
    $tooltip = __('Display your phone number with your preferred Instant messaging such as WhatsApp, Skype, Telegram enabled for easy communication.', 'compare_feature');
    return '<div>' . __('Instant messaging display', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('instant_messaging_display', 'shortcode_instant_messaging_display');

function shortcode_postal_address_display() {
    $tooltip = __('Add your postal address for an option to receive mail.', 'compare_feature');
    return '<div>' . __('Postal address display', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('postal_address_display', 'shortcode_postal_address_display');

function shortcode_chat_room() {
    $tooltip = __('Use Phoenix secure, confidential chat rooms for communication.', 'compare_feature');
    return '<div>' . __('Chat Room', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('chat_room', 'shortcode_chat_room');

// ================================================================
// User Accounts & Case Management
// ================================================================
function shortcode_user_accounts_case_management() {
    return __('User Accounts & Case Management', 'compare_feature');
}
add_shortcode('user_accounts_case_management', 'shortcode_user_accounts_case_management');

function shortcode_single_account() {
    $tooltip = __('One account, ideal for a small institution.', 'compare_feature');
    return '<div>' . __('Single Account', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('single_account', 'shortcode_single_account');

function shortcode_multi_user_accounts() {
    $tooltip = __('Create and manage multiple user accounts with different roles (up to three accounts for the Basic edition).', 'compare_feature');
    return '<div>' . __('Multi-User Accounts', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('multi_user_accounts', 'shortcode_multi_user_accounts');

function shortcode_case_management_dashboard() {
    $tooltip = __('Manage and track reports through a centralized dashboard.', 'compare_feature');
    return '<div>' . __('Case Management Dashboard', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('case_management_dashboard', 'shortcode_case_management_dashboard');

// ================================================================
// Language & Localisation
// ================================================================
function shortcode_language_localisation() {
    return __('Language & Localisation', 'compare_feature');
}
add_shortcode('language_localisation', 'shortcode_language_localisation');

function shortcode_single_language() {
    $tooltip = __('User interface available in only one language.', 'compare_feature');
    return '<div>' . __('Single Language', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('single_language', 'shortcode_single_language');

function shortcode_multi_languages() {
    $tooltip = __('Select and upgrade with a choice of over 50 languages for global accessibility to whistleblowers.', 'compare_feature');
    return '<div>' . __('Multi-Languages', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('multi_languages', 'shortcode_multi_languages');

// ================================================================
// Themes & Customisation
// ================================================================
function shortcode_themes_customisation() {
    return __('Themes & Customisation', 'compare_feature');
}
add_shortcode('themes_customisation', 'shortcode_themes_customisation');

function shortcode_phoenix_web_domain() {
    $tooltip = __('Use the default Phoenix domains for your platform.', 'compare_feature');
    return '<div>' . __('Phoenix Web Domain', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('phoenix_web_domain', 'shortcode_phoenix_web_domain');

function shortcode_custom_domain() {
    $tooltip = __('Use your own domain for a branded platform.', 'compare_feature');
    return '<div>' . __('Custom Domain', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('custom_domain', 'shortcode_custom_domain');

function shortcode_theme_selection() {
    $tooltip = __('Choose from various themes to match your brand.', 'compare_feature');
    return '<div>' . __('Theme Selection', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('theme_selection', 'shortcode_theme_selection');

function shortcode_white_label_branding() {
    $tooltip = __('Rebrand the platform with your own logo/design.', 'compare_feature');
    return '<div>' . __('White-Label Branding', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('white_label_branding', 'shortcode_white_label_branding');

function shortcode_advanced_customization() {
    $tooltip = __('Customize the platform to meet your needs.', 'compare_feature');
    return '<div>' . __('Advanced Customization', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('advanced_customization', 'shortcode_advanced_customization');

// ================================================================
// Server & Security
// ================================================================
function shortcode_server_security() {
    return __('Server & Security', 'compare_feature');
}
add_shortcode('server_security', 'shortcode_server_security');

function shortcode_anonymous_reporting() {
    $tooltip = __('Enable whistleblowers to submit disclosures anonymously.', 'compare_feature');
    return '<div>' . __('Anonymous Reporting', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('anonymous_reporting', 'shortcode_anonymous_reporting');

function shortcode_data_encryption_security_compliance() {
    $tooltip = __('Ensure all data is encrypted and meets security standards.', 'compare_feature');
    return '<div>' . __('Data Encryption & Security Compliance', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('data_encryption_security_compliance', 'shortcode_data_encryption_security_compliance');

function shortcode_standard_server_location() {
    $tooltip = __('Choose from three predefined server locations.', 'compare_feature');
    return '<div>' . __('Standard Server Location', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('standard_server_location', 'shortcode_standard_server_location');

function shortcode_custom_server_location() {
    $tooltip = __('Choose your preferred location.', 'compare_feature');
    return '<div>' . __('Custom Server Location', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('custom_server_location', 'shortcode_custom_server_location');

// ================================================================
// Assistance
// ================================================================
function shortcode_assistance() {
    return __('Assistance', 'compare_feature');
}
add_shortcode('assistance', 'shortcode_assistance');

function shortcode_self_serve_knowledge_base() {
    $tooltip = __('Provide a self-service knowledge base for user support.', 'compare_feature');
    return '<div>' . __('Self-serve knowledge base', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('self_serve_knowledge_base', 'shortcode_self_serve_knowledge_base');

function shortcode_ticketing_support() {
    $tooltip = __('Track and manage your support tickets seamlessly.', 'compare_feature');
    return '<div>' . __('Ticketing Support', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('ticketing_support', 'shortcode_ticketing_support');

function shortcode_priority_customer_support() {
    $tooltip = __('Get priority support for faster issue resolution.', 'compare_feature');
    return '<div>' . __('Priority Customer Support', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('priority_customer_support', 'shortcode_priority_customer_support');

function shortcode_dedicated_account_manager() {
    $tooltip = __('Benefit from a dedicated account manager for personalized support.', 'compare_feature');
    return '<div>' . __('Dedicated Account Manager', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('dedicated_account_manager', 'shortcode_dedicated_account_manager');

// ================================================================
// Payment
// ================================================================
function shortcode_payment() {
    return __('Payment', 'compare_feature');
}
add_shortcode('payment', 'shortcode_payment');

function shortcode_choice_of_currency() {
    $tooltip = __('Choice of currencies.', 'compare_feature');
    return '<div>' . __('Choice of Currency', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('choice_of_currency', 'shortcode_choice_of_currency');

function shortcode_payment_and_terms_of_payment() {
    return __('Payment and Terms of Payment', 'compare_feature');
}
add_shortcode('payment_and_terms_of_payment', 'shortcode_payment_and_terms_of_payment');

// ================================================================
// Upgrading
// ================================================================
function shortcode_upgrade_section() {
    return __('Upgrade', 'compare_feature');
}
add_shortcode('upgrade_section', 'shortcode_upgrade_section');

function shortcode_upgrade_options() {
    $tooltip = __('Upgrade to additional features or advanced plans.', 'compare_feature');
    return '<div>' . __('Upgrade Options', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('upgrade_options', 'shortcode_upgrade_options');

// ================================================================
// Add-ons
// ================================================================
function shortcode_addons_section() {
    return __('Add-ons', 'compare_feature');
}
add_shortcode('addons_section', 'shortcode_addons_section');

function shortcode_addons_available() {
    $tooltip = __('Expand your plan with additional channels, users, languages, and themes.', 'compare_feature');
    return '<div>' . __('Available', 'compare_feature') .
           ' <span class="tooltip-icon" title="' . esc_attr($tooltip) . '"><i class="fa fa-info-circle"></i></span></div>';
}
add_shortcode('addons_available', 'shortcode_addons_available');

// ================================================================
// Content Table
// ================================================================
function shortcode_various_currencies() {
    return __('Various Currencies', 'compare_feature');
}
add_shortcode('various_currencies', 'shortcode_various_currencies');

function shortcode_various_billing() {
    return __('Various Billing and Terms of Payment Options', 'compare_feature');
}
add_shortcode('various_billing', 'shortcode_various_billing');

function shortcode_credit_card() {
    return __('By Credit Card', 'compare_feature');
}
add_shortcode('credit_card', 'shortcode_credit_card');

function shortcode_options_upgrade_txt() {
    return __('Options to upgrade to Premium or Enterprise', 'compare_feature');
}
add_shortcode('options_upgrade_txt', 'shortcode_options_upgrade_txt');

function shortcode_options_upgrade_txt2() {
    return __('Options to add features or upgrade to Enterprise', 'compare_feature');
}
add_shortcode('options_upgrade_txt2', 'shortcode_options_upgrade_txt2');
