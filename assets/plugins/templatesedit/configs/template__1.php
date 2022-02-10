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
        ]
    ],


    'seo' => $tabs['seo'],
    'settings' => $tabs['settings'],
];