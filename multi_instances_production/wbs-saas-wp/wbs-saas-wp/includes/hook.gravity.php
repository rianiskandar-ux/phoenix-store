<?php

/**
 * All the const and hooks for Gravity Forms
 * ATTENTION: Some GF Filters has namefilter_FORMID_FIELDID
 * 
 * @link https://docs.gravityforms.com/category/developers/hooks/actions/
 * 
 * @since 1.0.0
 */

global $gravity;

/**
 * Check if the GF Field subdomain Phoenix and subdomain Tenant has:
 *   1. follow the rules:
 *       - It can only contain lowercase letters, the 26 letters of the English alphabet, numbers (0-9)
 *         and hyphen/minus sign (-) and underscore (_)
 *       - It must start and end with a lettre or number, not a hyphen.
 *       - There can not bien two consecutive hyphens.
 *   2. forbidden subdomain (demo, www, localhost, etc...) 
 * 
 * Suffix for a specific form and field: function_FORMID_FIELDID
 * 
 * @link https://docs.gravityforms.com/gform_field_validation/
 * 
 * @since 1.0.0
 * 
 * @param   array           $result The validation result to be filtered.
 * @param   string|array    $value  The field value to be validated. Multi-input fields like Address will pass an array of values.
 * @param   object          $form   Current form object.
 * @param   object          $field  Current field object.
 * @return  array           $return
 */

add_filter( 'gform_field_validation_' . $gravity->form->free->gid   . '_' . $gravity->form->free->subdomain_phoenix,   'wbssaas_gform_validate_subdomain', 10, 4 );
add_filter( 'gform_field_validation_' . $gravity->form->basic->gid     . '_' . $gravity->form->basic->subdomain_phoenix,     'wbssaas_gform_validate_subdomain', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->standard->gid  . '_' . $gravity->form->standard->subdomain_phoenix,  'wbssaas_gform_validate_subdomain', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->standard->gid  . '_' . $gravity->form->standard->subdomain_tenant,   'wbssaas_gform_validate_subdomain', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->enhanced->gid  . '_' . $gravity->form->enhanced->subdomain_phoenix,  'wbssaas_gform_validate_subdomain', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->enhanced->gid  . '_' . $gravity->form->enhanced->subdomain_tenant,   'wbssaas_gform_validate_subdomain', 10, 4 );
add_filter( 'gform_field_validation_' . $gravity->form->premium->gid   . '_' . $gravity->form->premium->subdomain_phoenix,   'wbssaas_gform_validate_subdomain', 10, 4 );
add_filter( 'gform_field_validation_' . $gravity->form->premium->gid   . '_' . $gravity->form->premium->subdomain_tenant,    'wbssaas_gform_validate_subdomain', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->composite->gid . '_' . $gravity->form->composite->subdomain_phoenix, 'wbssaas_gform_validate_subdomain', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->composite->gid . '_' . $gravity->form->composite->subdomain_tenant,  'wbssaas_gform_validate_subdomain', 10, 4 );

function wbssaas_gform_validate_subdomain( $result, $value, $form, $field )
{
    /**
     * @see https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/issues/40
     */
    if( empty( $value ) ) {
        return $result;
    }

    global $gravity;

    /**
     * Check #1:
     * (?!-)         -> the subdomain doesn't start with a hyphen.
     * [a-zA-Z0-9-_] -> matches alphanumeric characters (letters and digits) and hyphens + underscore
     * {1,63}        -> he subdomain must be between 1 and 63 characters in length
     * (?<!-)        -> the subdomain doesn't end with a hyphen
     */
    if( ! preg_match('/^(?!-)[a-zA-Z0-9-_]{1,63}(?<!-)$/i', $value ) ) {
        $log = new \WBSSaaS\Logger();
        $log->warning( [__METHOD__, __LINE__], 'The Phoenix subdomain is not valid =>' );
        $log->warning( [__METHOD__, __LINE__], $value );
        $result['is_valid'] = false;
        $result['message'] = __( 'The subdomain contains forbidden characters.', 'wbs-saas-plugin' );
    }

    /**
     * Check #2: No fordidden subdomain
     */
    foreach( $gravity->forbidden_suddomain as $str ) {
        if( strpos( $value, $str ) !== false ) {
            $match[] = true;
        }
    }

    if( isset( $match ) ) {
        $log = new \WBSSaaS\Logger();
        $log->warning( [__METHOD__, __LINE__], 'The subdomain contains a forbidden subdomain =>' );
        $log->warning( [__METHOD__, __LINE__], $value );
        $result['is_valid'] = false;
        $result['message'] = __( 'You are not allowed to use this subdomain. Please choose another one.', 'wbs-saas-plugin' );
    }

    return $result;
}

/**
 * Check if the domain name of the Tenant is valid
 * Only for GF Standard, Enhanced, Premium or Composite
 * 
 * Suffix for a specific form and field: function_FORMID_FIELDID
 * 
 * @link https://docs.gravityforms.com/gform_field_validation/
 * 
 * @since 1.0.0
 * 
 * @param   array           $result The validation result to be filtered.
 * @param   string|array    $value  The field value to be validated. Multi-input fields like Address will pass an array of values.
 * @param   object          $form   Current form object.
 * @param   object          $field  Current field object.
 * @return  array           $return
 */
// add_filter( 'gform_field_validation_' . $gravity->form->standard->gid  . '_' . $gravity->form->standard->domain_tenant,  'wbssaas_gform_validate_domain', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->enhanced->gid  . '_' . $gravity->form->enhanced->domain_tenant,  'wbssaas_gform_validate_domain', 10, 4 );
add_filter( 'gform_field_validation_' . $gravity->form->premium->gid   . '_' . $gravity->form->premium->domain_tenant,   'wbssaas_gform_validate_domain', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->composite->gid . '_' . $gravity->form->composite->domain_tenant, 'wbssaas_gform_validate_domain', 10, 4 );

function wbssaas_gform_validate_domain( $result, $value, $form, $field )
{
    $log = new \WBSSaaS\Logger();
    $log->debug( [__METHOD__, __LINE__], 'Hook wbssaas_gform_validate_domain()' );
    $log->debug( [__METHOD__, __LINE__], $value );

    /**
     * @see https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/issues/40
     */
    if( empty( $value ) ) {
        return $result;
    }

    /**
     * Based on RFC 1035, a domain name can contain only the following characters:
     * - Lowercase letters (a-z)
     * - Digits (0-9)
     * - Hyphens (-)
     * - Periods (.)
     * - Cannot start or end with a hyphen
     * - Cannot contain consecutive hyphens
     * - Must be between 1 and 253 characters in length
     */
    if ( ! filter_var( $value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME ) ) {
        $log = new \WBSSaaS\Logger();
        $log->warning( [__METHOD__, __LINE__], 'The domain name is not valid RFC =>' );
        $log->warning( [__METHOD__, __LINE__], $value );
        $result['is_valid'] = false;
        $result['message'] = __( 'The domain name is not valid.', 'wbs-saas-plugin' );
    }

    // Validate the domain name format
    if (!preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,}$/i', $value)) {
        $log = new \WBSSaaS\Logger();
        $log->warning( [__METHOD__, __LINE__], 'The domain name format is not valid =>' );
        $log->warning( [__METHOD__, __LINE__], $value );
        $result['is_valid'] = false;
        $result['message'] = __( 'The domain name format is not valid.', 'wbs-saas-plugin' );
    }

    return $result;
}

/**
 * Check if the Tenand URL (FQDN)
 *   1. is valid
 *   2. has no sudirectory or a path
 * 
 * @link https://docs.gravityforms.com/gform_field_validation/
 * 
 * @since 1.0.0
 * 
 * @param   array           $result The validation result to be filtered.
 * @param   string|array    $value  The field value to be validated. Multi-input fields like Address will pass an array of values.
 * @param   object          $form   Current form object.
 * @param   object          $field  Current field object.
 * @return  array           $return
 */
// add_filter( 'gform_field_validation_' . $gravity->form->standard->gid  . '_' . $gravity->form->standard->fqdn_custom,  'wbssaas_gform_validate_fqdn', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->enhanced->gid  . '_' . $gravity->form->enhanced->fqdn_custom,  'wbssaas_gform_validate_fqdn', 10, 4 );
add_filter( 'gform_field_validation_' . $gravity->form->premium->gid   . '_' . $gravity->form->premium->fqdn_custom,   'wbssaas_gform_validate_fqdn', 10, 4 );
// add_filter( 'gform_field_validation_' . $gravity->form->composite->gid . '_' . $gravity->form->composite->fqdn_custom, 'wbssaas_gform_validate_fqdn', 10, 4 );

function wbssaas_gform_validate_fqdn( $result, $value, $form, $field )
{
    /**
     * @see https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/issues/40
     */
    if( empty( $value ) ) {
        return $result;
    }

    /**
     * Check #1: Valid URL
     */
    if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
        $log = new \WBSSaaS\Logger();
        $log->warning( [__METHOD__, __LINE__], 'The FQDN is not valid.' );
        $result['is_valid'] = false;
        $result['message'] = __( 'The address is not correct. Enter a valid address.', 'wbs-saas-plugin' );
    }

    /**
     * Check #2: No subdirectory or path
     */
    $value = rtrim( $value, '/' );
    $subdir = parse_url( $value, PHP_URL_PATH);
    if( $subdir ) {
        $log = new \WBSSaaS\Logger();
        $log->warning( [__METHOD__, __LINE__], 'The FQDN has a subdirectory or a path' );
        $result['is_valid'] = false;
        $result['message'] = __( 'You cannot use a subdirectory or a path in your website address.', 'wbs-saas-plugin' );
    }

    return $result;
}

/**
 * Check if the domain is already used in WBS SaaS
 * 
 * @link https://docs.gravityforms.com/gform_validation/
 * 
 * @since 1.0.0
 *
 * @param  array $validation_result
 * @return array $validation_result     Required or it generates a PHP error
 */

add_filter( 'gform_validation_' . $gravity->form->free->gid,   'wbssaas_gform_check_domain', 10, 1 );
add_filter( 'gform_validation_' . $gravity->form->basic->gid,     'wbssaas_gform_check_domain', 10, 1 );
// add_filter( 'gform_validation_' . $gravity->form->standard->gid,  'wbssaas_gform_check_domain', 10, 1 );
// add_filter( 'gform_validation_' . $gravity->form->enhanced->gid,  'wbssaas_gform_check_domain', 10, 1 );
add_filter( 'gform_validation_' . $gravity->form->premium->gid,   'wbssaas_gform_check_domain', 10, 1 );
// add_filter( 'gform_validation_' . $gravity->form->composite->gid, 'wbssaas_gform_check_domain', 10, 1 );

function wbssaas_gform_check_domain( $validation_result )
{
    global $gravity;

    $log = new \WBSSaaS\Logger();
    $log->info( [__METHOD__, __LINE__], 'Start hook Check domain');

    $form  = $validation_result['form'];
    $entry = GFFormsModel::get_current_lead();

    $log->debug( [__METHOD__, __LINE__], 'Current GF Entry filled by the customer => ');
    $log->debug( [__METHOD__, __LINE__], $entry );

    // Get the current plan
    foreach( $gravity->form as $plan => $cursor )
    {
        if( $cursor->gid == $entry['form_id'] )
        {
            $current_plan = $plan;
        }
    }
    $log->debug( [__METHOD__, __LINE__], 'Current plan => ' . $current_plan );

    /**
     * @see https://gitlab.integrity-asia.com/canary/wbs-saas-wp/-/issues/40
     */
    // if( empty( $entry[GF_TENANT[$current_plan]['subdomain_phoenix']] ) ||
    //     empty( $entry[GF_TENANT[$current_plan]['subdomain_tenant']]  ) ||
    //     empty( $entry[GF_TENANT[$current_plan]['domain_tenant']]     ) ||
    //     empty( $entry[GF_TENANT[$current_plan]['fqdn_custom']]       ) ) {
        
    //     return $validation_result;
    // }

    if( !empty( $entry[$gravity->form->$current_plan->subdomain_phoenix] ) ) {
        $url = 'https://' . rgar( $entry, $gravity->form->$current_plan->subdomain_phoenix ) . '.' . rgar( $entry, $gravity->form->$current_plan->domain_phoenix );
    }

    $sub = rgar( $entry, $gravity->form->$current_plan->subdomain_tenant );
    $dom = rgar( $entry, $gravity->form->$current_plan->domain_tenant );

    

    if ( !empty( $sub ) && !empty( $dom ) && filter_var( "$sub.$dom", FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME ) ) {
        $url = "https://$sub.$dom";
    }


    if( !empty( $entry[$gravity->form->$current_plan->fqdn_custom] ) ) {
        $url = rgar( $entry, $gravity->form->$current_plan->fqdn_custom );
    }

    if( empty( $url ) ) {

        $log->error( [__METHOD__, __LINE__], 'SaaS URL empty!!' );
        // $validation_result['form'] = $form;
        // return $validation_result;

        $validation_result['is_valid'] = false;
        add_filter( 'gform_validation_message', function ( $message, $form ) {
            return __( 'Website address is empty. Please fill it.', 'wbs-saas-plugin' );
        }, 10, 2 );

    } else {
        
        $log->debug( [__METHOD__, __LINE__], 'Tenant URL => ' . $url );
    }

    $tenants = new \WBSSaaS\Tenant( $log );

    if ( empty( $url ) || ! is_string( $url ) ) {
        $log->error([__METHOD__, __LINE__], 'Invalid or empty URL passed to domain availability check.');
        $validation_result['is_valid'] = false;
        add_filter( 'gform_validation_message', function ( $message, $form ) {
            return __( 'Website address is missing or invalid. Please double-check your input.', 'wbs-saas-plugin' );
        }, 10, 2 );
        return $validation_result;
    }

    
    if( $tenants->verifyDomainAvailability( $url ) == false ) {
        $log->warning( [__METHOD__, __LINE__], 'The address is already taken by another client => ' . $url );
        $validation_result['is_valid'] = false;
        add_filter( 'gform_validation_message', function ( $message, $form ) {
            return __( 'The domain is already taken. Please choose another one.', 'wbs-saas-plugin' );
        }, 10, 2 );
    }

    // DO NOT COMMENT THE 2 FOLLOWING. GF needs it to retrieve the form
    $validation_result['form'] = $form;
 
    return $validation_result;
}

/**
 * Dynamically Populating Drop Down list of SaaS Clients for WC_Subscription Addons
 * 
 * @link https://docs.gravityforms.com/dynamically-populating-drop-down-or-radio-buttons-fields/
 * 
 * @since 2.0.0
 * 
 * @param  object   $form   The original form
 * @return object   $form   The modified form
 * 
 */

add_filter( 'gform_pre_render_' . $gravity->form->customer->gid,            'wbssaas_gform_populate_dropdown_client' );
add_filter( 'gform_pre_validation_' . $gravity->form->customer->gid,        'wbssaas_gform_populate_dropdown_client' );
add_filter( 'gform_pre_submission_filter_' . $gravity->form->customer->gid, 'wbssaas_gform_populate_dropdown_client' );
add_filter( 'gform_admin_pre_render_' . $gravity->form->customer->gid,      'wbssaas_gform_populate_dropdown_client' );

function wbssaas_gform_populate_dropdown_client( $form )
{
    global $gravity;

    $log = new \WBSSaaS\Logger();
    $log->info( [__METHOD__, __LINE__], 'Start hook Populate Dropdown Clients');

    foreach ( $form['fields'] as &$field ) {

        if ( $field->id == $gravity->form->customer->dropdown ) {

            /**
             * Get the GF entries where CreatorID == current UserID 
             * @link https://docs.gravityforms.com/searching-and-getting-entries-with-the-gfapi/
             */

            $search_criteria = array();
            $search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => get_current_user_id() );
            $form_ids = array(
                $gravity->form->free->gid,
                $gravity->form->basic->gid,
                // $gravity->form->standard->gid,
                // $gravity->form->enhanced->gid,
                $gravity->form->premium->gid,
                // $gravity->form->composite->gid,
            );
            $entries = GFAPI::get_entries( $form_ids, $search_criteria );
            // $log->debug( [__METHOD__, __LINE__], $entries );

            $clients = array();

            foreach( $entries as $entry ) {

                $current_subscription = match( $entry['form_id'] ) {
                    $gravity->form->free->gid    => 'free',
                    $gravity->form->basic->gid      => 'basic',
                    // $gravity->form->standard->gid,  => 'standard',
                    // $gravity->form->enhanced->gid,  => 'enhanced',
                    $gravity->form->premium->gid,   => 'premium',
                    // $gravity->form->composite->gid, => 'composite',
                };

                $clients[] = array(
                    'text'  => $entry[$gravity->form->$current_subscription->name],
                    'value' => $entry[$gravity->form->$current_subscription->uuid],
                );
            }

            $field->choices = $clients;
        }

    }

    return $form;
}
