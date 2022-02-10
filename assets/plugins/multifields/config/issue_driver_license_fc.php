<?php

$config = [];

$fieldsWithPlaceholder = [
    'full_name'=>'Фио',
    'address'=>'Адреса',
    'tax_number'=>'Ідентифікаційний номер',
    'regional_service_center'=>'Регіон',
    'territorial_service_center'=>'ТСЦ вашої обасті',
    'service'=>'Послуга',

];
$fieldsWithoutPlaceholder = [];
foreach ($fieldsWithPlaceholder as $fieldName => $caption) {
    $config[$fieldName.'_title'] = [
        'type' => 'text',
        'title' => 'Название поля "'.$caption.'"',
        'class' => 'col-6'
    ];

    if(!in_array($fieldName,$fieldsWithoutPlaceholder)){
    $config[$fieldName.'_placeholder'] = [
        'type' => 'text',
        'title' => 'Плейсхолдер поля "'.$caption.'"',
        'class' => 'col-6'
    ];
    }
}



return [
    'settings' => [],
    'templates' => [

        'form' => [
            'label' => 'Настройка формы',
            'type' => 'row',

            'actions' => ['add', 'edit', 'del'],

            'value' => false,

            'items' =>$config ,
            'limit' => 1
        ],


    ]
];