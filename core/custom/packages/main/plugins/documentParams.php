<?php

use DocumentParams\Plugin;
use EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias;
use EvolutionCMS\Main\Support\Helpers;

Event::listen('evolution.OnDocFormRender',function ($params){

    $id = $params['id'];
    $modx_lang_attribute  = 'ru';

    $output = '';
    $modx = evolutionCMS();

    if(empty($id)){
        return false;
    }




    $servicesAliases = new ServicesAlias();
    $serviceAliases = '';

    try {
        $serviceAliases = $servicesAliases->getServiceAlias($id);
    }
    catch (\EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException $e){

    }


    if(in_array($serviceAliases,[
        'police_protection',
        'fines',
        'e_cabinet_tax',
        'sudy_tax',
        'installation_services_police_protection',
        'issue_driver_license',
        'fines_by_act',
        'park_fines_by_act',
        'weight_fines_by_act',
        'vvpay',
    ])){
        $tab_1 = new Plugin ($modx, $modx_lang_attribute);
        $tab_1->addDocumentPramas($id, 'Комиссия', Helpers::getModuleId('commissions'), 'commission');
        $output = $tab_1->render();
    }

    switch($serviceAliases){

        case 'police_protection':

            $tab_2 = new Plugin ($modx, $modx_lang_attribute);
            $tab_2->addDocumentPramas($id, 'Счета ПО', Helpers::getModuleId('poregcode'),'fines');
            $output .= $tab_2->render();
            break;
        case 'installation_services_police_protection':

            $tab_2 = new Plugin ($modx, $modx_lang_attribute);
            $tab_2->addDocumentPramas($id, 'Счета МП', Helpers::getModuleId('montcode_items'),'fines');
            $output .= $tab_2->render();
            break;
        case 'issue_driver_license':

            $tab_2 = new Plugin ($modx, $modx_lang_attribute);
            $tab_2->addDocumentPramas($id, 'Счета РЦС', Helpers::getModuleId('TerritorialServiceCenter'), 'TerritorialServiceCenter', '', '');
            $output .= $tab_2->render();

            $tab_3 = new Plugin ($modx, $modx_lang_attribute);
            $tab_3->addDocumentPramas($id, 'Услуги', Helpers::getModuleId('services'), 'service', '');
            $output .= $tab_3->render();
            break;
        case 'fines_by_act':

            $tab_2 = new Plugin ($modx, $modx_lang_attribute);
            $tab_2->addDocumentPramas($id, 'Счета штрафов', Helpers::getModuleId('PencodesItems'), 'PencodesItems', '', '');
            $output .= $tab_2->render();
            break;

        case 'park_fines_by_act':
            $tab_2 = new Plugin ($modx, $modx_lang_attribute);
            $tab_2->addDocumentPramas($id, 'Счета штрафов', Helpers::getModuleId('ParkPencodeItems'), 'ParkPencodeItems', '', '');
            $output .= $tab_2->render();
            break;

        case 'vvpay':
            $tab_2 = new Plugin ($modx, $modx_lang_attribute);
            $tab_2->addDocumentPramas($id, 'Счета', Helpers::getModuleId('VVPayDetails'), 'VVPayDetails', '', '');
            $output .= $tab_2->render();
            break;

//        case 51:
//            $tab_1 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_1->addDocumentPramas($id, 'Комиссия', 17, 'commission');
//            $output = $tab_1->render();
//
//            $tab_2 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_2->addDocumentPramas($id, 'Счета НДЕКЦ', 12,'fines');
//            $output .= $tab_2->render();
//
//            $tab_3 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_3->addDocumentPramas($id, 'Счета ДП', 25,'fines2');
//            $output .= $tab_3->render();
//
//            $tab_4 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_4->addDocumentPramas($id, 'Услуги НДЕКЦ', 15,'service', '');
//            $output .= $tab_4->render();
//
//            $tab_5 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_5->addDocumentPramas($id, 'Услуги ДП', 15,'service', 99);
//            $output .= $tab_5->render();
//            break;
//        case 53:
//        case 55:
//        case 62:

//        case 57:
//            $tab_1 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_1->addDocumentPramas($id, 'Комиссия', 17, 'commission');
//            $output = $tab_1->render();
//
//            $tab_2 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_2->addDocumentPramas($id, 'Счета РЦС', 14,'fines', '', '');
//            $output .= $tab_2->render();
//
//            $tab_3 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_3->addDocumentPramas($id, 'Услуги', 15,'service', '');
//            $output .= $tab_3->render();
//
//            $tab_4 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_4->addDocumentPramas($id, 'Счета ПФ', 25,'finesDP', '');
//            $output .= $tab_4->render();
//
//            $tab_5 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_5->addDocumentPramas($id, 'Комиссия ПФ', 18,'commissionPF', '');
//            $output .= $tab_5->render();
//
//            break;
//        case 61:
//            $tab_1 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_1->addDocumentPramas($id, 'Комиссия', 17, 'commission');
//            $output = $tab_1->render();
//
//            $tab_2 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_2->addDocumentPramas($id, 'Счета', 10,'fines');
//            $output .= $tab_2->render();
//            break;
//        case 102:
//            $tab_1 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_1->addDocumentPramas($id, 'Комиссия', 17, 'commission');
//            $output = $tab_1->render();
//
//            $tab_2 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_2->addDocumentPramas($id, 'Счета', 10,'fines');
//            $output .= $tab_2->render();
//
//            $tab_3 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_3->addDocumentPramas($id, 'Услуги', 15,'service', '');
//            $output .= $tab_3->render();
//
//            $tab_4 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_4->addDocumentPramas($id, 'Счета ДП', 25,'finesDP');
//            $output .= $tab_4->render();
//
//            break;
//        case 70:
//            $tab_1 = new \DocumentParams\Plugin ($modx, $modx_lang_attribute);
//            $tab_1->addDocumentPramas($id, 'Email', '','email', '', '', $modx->config['cfg_mail_for_zajavka']);
//            $output .= $tab_1->render();
//            break;
    }


    return $output;

});