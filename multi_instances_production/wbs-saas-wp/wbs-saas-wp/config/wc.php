<?php

/**
 * Config file for the WooCmmmerce Hooks
 * Usage:
 *   - $wc = new Laminas\Config\Config( include WBSSAAS_PLUGIN_DIR . 'config/wc.php' );
 *   - $wc->subscription->basic->id
 *   - $wc->subscription->basic->get( 'id', 'default_value' );
 * 
 * @since 2.2.0
 * 
 * @see https://github.com/laminas/laminas-config
 * 
 * @see https://docs.laminas.dev/laminas-config/
 */

return [
    'subscription' => [
        'free' => [
            'id' => '30596',
            'default_package' => [
                'webforms' => ['short', 'medium', 'long'],
                'phone'        => 0,
                'email'        => 0,
                'im'           => 0,
                'postmail'     => 0,
                'chat'         => 0,
                'mobileapp'    => 0,
                'languages'    => 1,
                'users'     => ['manager' => 1, 'operator' => 0, 'agent' => 0],
                'themes'    => ['phoenix_1'],
            ],
        ],
        'basic' => [
            'id' => '58',
            'default_package' => [
                'webforms' => ['short', 'medium', 'long'],
                'phone'     => 1,
                'email'     => 1,
                'im'        => 1,
                'postmail'  => 1,
                'chat'      => 0,
                'mobileapp' => 0,
                'languages' => 2,
                'users'     => ['manager' => 1, 'operator' => 0, 'agent' => 0],
                'themes'    => ['phoenix_1', 'phoenix_2', 'phoenix_3'],
            ],
        ],
        // INACTIVE PLAN
        'standard' => [
            'id' => '68',
            'default_package' => [
                'webforms' => ['short', 'medium', 'long'],
                'phone'     => 1,
                'email'     => 1,
                'im'        => 1,
                'postmail'  => 1,
                'chat'      => 0,
                'mobileapp' => 0,
                'languages' => 2,
                'users'     => ['manager' => 1, 'operator' => 0, 'agent' => 0],
                'themes'    => ['phoenix_1', 'phoenix_2', 'phoenix_3'],
            ],
        ],
        // INACTIVE PLAN
        'enhanced' => [
            'id' => '72',
            'default_package' => [
                'webforms' => ['short', 'medium', 'long'],
                'phone'        => 1,
                'email'        => 1,
                'im'           => 1,
                'postmail'     => 1,
                'chat'         => 0,
                'mobileapp'    => 0,
                'languages'    => 2,
                'users'     => ['manager' => 1, 'operator' => 1, 'agent' => 1],
                'themes'    => [
                    'phoenix_1', 'phoenix_2', 'phoenix_3', 'phoenix_4', 'phoenix_5', 'phoenix_6', 'phoenix_7', 'phoenix_8', 'phoenix_9', 'phoenix_10',
                    'phoenix_11', 'phoenix_12', 'phoenix_13', 'phoenix_14', 'phoenix_15', 'phoenix_16', 'phoenix_17', 'phoenix_18', 'phoenix_19', 'phoenix_20',
                    'phoenix_21', 'phoenix_22'
                    ],
            ],
        ],
        'premium' => [
            'id' => '76',
            'default_package' => [
                'webforms' => ['short', 'medium', 'long', 'custom'],
                'phone'        => 1,
                'email'        => 1,
                'im'           => 1,
                'postmail'     => 1,
                'chat'         => 1,
                'mobileapp'    => 0,
                'languages'    => 2,
                'users'     => ['manager' => 1, 'operator' => 1, 'agent' => 1],
                'themes'    => [
                    'phoenix_1', 'phoenix_2', 'phoenix_3', 'phoenix_4', 'phoenix_5', 'phoenix_6', 'phoenix_7', 'phoenix_8', 'phoenix_9', 'phoenix_10',
                    'phoenix_11', 'phoenix_12', 'phoenix_13', 'phoenix_14', 'phoenix_15', 'phoenix_16', 'phoenix_17', 'phoenix_18', 'phoenix_19', 'phoenix_20',
                    'phoenix_21', 'phoenix_22'
                    ],
            ],
        ],
        // INACTIVE PLAN
        'composite' => [
            'id' => '21621',
            'default_package' => [
                'webforms' => ['short', 'medium', 'long'],
                'phone'        => 0,
                'email'        => 0,
                'im'           => 0,
                'postmail'     => 0,
                'chat'         => 0,
                'mobileapp'    => 0,
                'languages'    => 1,
                'users'     => ['manager' => 0, 'operator' => 0, 'agent' => 0],
                'themes'    => ['phoenix_1'],
            ],
        ],
    ],
    'addon' => [
        'users'     => '2248',
        'languages' => '2258',
        'phone'     => '2263',
        'email'     => '2266',
        'im'        => '2269',
        'postmail'  => '2272',
        'chat'      => '2275',
        'mobileapp' => '2278',
    ],
    // INACTIVE PLAN
    'composite' => [
        'core'        => '27319',
        'theme_lib'   => '27331',
        'white_label' => '27334',
        'mobileapp'   => '27337',
        'languages'   => '27345',
        'email'       => '27348 ',
        'phone'       => '27351',
        'im'          => '27354',
        'chat'        => '27357',
        'postmail'    => '27360',
        'manager'     => '27363',
        'operator'    => '27366',
        'agent'       => '27369',
    ]
];
