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
            'menutitle' => [],
            'hidemenu' => [],
            'parent' => [],
            'weblink' => [],
            'content'=>['position'=>'c'],
            'content_builder'=>['position'=>'c'],
        ]
    ],
    'merchant'=>[
        'title' => 'Мерчанты - Автоцивилка',
        'fields' => [
            'merchant'=>['position'=>'c']
        ],
    ],
    'seo' => $tabs['seo'],
    'settings' => $tabs['settings']
];