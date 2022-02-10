<?php
/**
 * default config
 */

$tabs = require 'tabs.php';
global $_lang;

return [
    'General' => [
        'default' => true,
        'title' => $_lang['settings_general'],
        'fields' => [
            'pagetitle' => [],
            'longtitle' => [],
            'alias' => [],
            'template' => [],
            'menutitle' => [],
            'hidemenu' => [],
            'parent' => [],

            'weblink' => [],
            'content' => [
                'position'=>'c'
            ],
        ]
    ],

    'payment_settings'=>[
        'title' => 'Настройки оплат',
        'fields' => [
            'fix_sum' => [
                'position'=>'c'
            ],
            'liqpay_keys' => [
                'position'=>'c'
            ],
            'recipient' => [
                'position'=>'c'
            ],
        ],
    ],

    'seo' => $tabs['seo'],
    'settings' => $tabs['settings']
];