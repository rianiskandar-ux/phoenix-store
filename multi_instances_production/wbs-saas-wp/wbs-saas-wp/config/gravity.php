<?php

/**
 * Config file for the Gravity Hooks
 * Usage:
 *   - $gravity = new Laminas\Config\Config( include WBSSAAS_PLUGIN_DIR . 'config/gravity.php' );
 *   - $gravity->form->basic->gid
 *   - $gravity->form->basic->get( 'gid', 'default_value' );
 *
 * @since 2.2.0
 *
 * @see https://github.com/laminas/laminas-config
 *
 * @see https://docs.laminas.dev/laminas-config/
 */

return [
    'form' => [
        'free' => [
            'gid'               => '70',
            'uuid'              => '1',
            'name'              => '3',
            'location'          => '9',
            'subdomain_phoenix' => '4',
            'domain_phoenix'    => '5',
            'fqdn_phoenix'      => '6',
            'subdomain_tenant'  => null,
            'domain_tenant'     => null,
            'fqdn_tenant'       => null,
            'fqdn_custom'       => null,
        ],
        'basic' => [
            'gid'               => '58',
            'uuid'              => '1',
            'name'              => '3',
            'location'          => '9',
            'subdomain_phoenix' => '4',
            'domain_phoenix'    => '5',
            'fqdn_phoenix'      => '6',
            'subdomain_tenant'  => null,
            'domain_tenant'     => null,
            'fqdn_tenant'       => null,
            'fqdn_custom'       => null,
        ],
        // 'standard' => [
        //     'gid'               => '59',
        //     'uuid'              => '1',
        //     'name'              => '3',
        //     'location'          => '17',
        //     'subdomain_phoenix' => '5',
        //     'domain_phoenix'    => '6',
        //     'fqdn_phoenix'      => '7',
        //     'subdomain_tenant'  => '8',
        //     'domain_tenant'     => '9',
        //     'fqdn_tenant'       => '10',
        //     'fqdn_custom'       => '11',
        // ],
        // 'standard' => [
        //     'gid'               => '72',
        //     'uuid'              => '1',
        //     'name'              => '3',
        //     'location'          => '9',
        //     'subdomain_phoenix' => '4',
        //     'domain_phoenix'    => '5',
        //     'fqdn_phoenix'      => '6',
        // ],
        // 'enhanced' => [
        //     'gid'               => '60',
        //     'uuid'              => '1',
        //     'name'              => '3',
        //     'location'          => '17',
        //     'subdomain_phoenix' => '5',
        //     'domain_phoenix'    => '6',
        //     'fqdn_phoenix'      => '7',
        //     'subdomain_tenant'  => '8',
        //     'domain_tenant'     => '9',
        //     'fqdn_tenant'       => '10',
        //     'fqdn_custom'       => '11',
        // ],
        'premium' => [
            'gid'               => '61',
            'uuid'              => '1',
            'name'              => '3',
            'location'          => '17',
            'subdomain_phoenix' => '5',
            'domain_phoenix'    => '6',
            'fqdn_phoenix'      => '7',
            'subdomain_tenant'  => '8',
            'domain_tenant'     => '9',
            'fqdn_tenant'       => '10',
            'fqdn_custom'       => '11',
        ],
        // 'composite' => [
        //     'gid'               => '67',
        //     'uuid'              => '1',
        //     'name'              => '3',
        //     'location'          => '38',
        //     'subdomain_phoenix' => '5',
        //     'domain_phoenix'    => '6',
        //     'fqdn_phoenix'      => '7', // Useless
        //     'subdomain_tenant'  => '8',
        //     'domain_tenant'     => '33',
        //     'fqdn_tenant'       => '10', // Useless
        //     'fqdn_custom'       => '11',
        // ],
        'customer' => [
            'gid' => '64',
            'dropdown' => '1',
        ],
    ],
    'forbidden_suddomain' => [
        'localhost',
        'www',
        'ww',
        'demo',
        'test',
        'integrity',
    ]
];
