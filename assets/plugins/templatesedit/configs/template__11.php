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
            'content' => [
                'position'=>'c'
            ],
        ]
    ],


    'form' => [
      'title'=>'Настройка формы',
      'fields'=>[
          'field_car_number_title'=>[],
          'field_car_number_placeholder'=>[],

          'field_tax_number_title'=>[],
          'field_tax_number_placeholder'=>[],

          'field_tech_passport_title'=>[],
          'field_tech_passport_placeholder'=>[],

          'field_driving_license_title'=>[],
          'field_driving_license_placeholder'=>[],

          'field_driving_license_date_issue_title'=>[],
          'field_driving_license_date_issue_placeholder'=>[],

          'field_fine_series_title'=>[],
          'field_fine_series_placeholder'=>[],

          'field_fine_number_title'=>[],
          'field_fine_number_placeholder'=>[],
      ]
    ],
    'seo' => $tabs['seo'],
    'settings' => $tabs['settings']
];