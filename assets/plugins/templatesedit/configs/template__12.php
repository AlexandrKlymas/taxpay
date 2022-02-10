<?php
/**
 * default config
 */

$tabs = require 'tabs.php';
global $_lang;

return [
    'General' => [
        'default' => false,
        'title' => $_lang['settings_general'],
        'fields' => [
            'pagetitle' => [],
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

    'seo' => $tabs['seo'],
    'settings' => $tabs['settings'],
    'form_config' => [
        'title' => 'Настройка формы',
        'fields' => [
            'id_region' => [],
            'type_service' => [],
            'online_order_police_protection_fc' => [
                'position'=>'c'
            ],
        ]
    ],
];