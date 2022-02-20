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
            'alias' => [],
            'template' => [],
            'parent' => [],
            'content' => [
                'position'=>'c'
            ],
        ]
    ],

    'payment_settings'=>[
        'title' => 'Настройки оплат',
        'fields' => [
            'registry_offices' => [
                'position'=>'c'
            ],
        ],
    ],

    'seo' => $tabs['seo'],
    'settings' => $tabs['settings']
];