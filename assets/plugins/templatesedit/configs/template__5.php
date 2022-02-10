<?php
/**
 * default config
 */

$tabs = require 'tabs.php';
global $_lang;


$serviceId = intval($_GET['id']);
try {
    $alias = (new \EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias())->getServiceAlias($serviceId);

}
catch (\EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException $e){
    $alias = '';
}


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
                'position' => 'c'
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
            $alias.'_fc' => [
                'position'=>'c'
            ],
        ]
    ],

];