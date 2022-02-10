<?php

$config = [];

$fieldsWithPlaceholder = [
    'full_name'=>'Фио',
    'installation_service_region'=>'Оберіть регіон',
    'address'=>'Адреса',
    'company'=>'Для юр. осіб (назва, ЕДРПОУ)',
    'contract_date_and_number'=>'Дата та номер договору',
    'sum'=>'Сума сплати',
];
foreach ($fieldsWithPlaceholder as $fieldName => $caption) {
    $config[$fieldName.'_title'] = [
        'type' => 'text',
        'title' => 'Название поля "'.$caption.'"',
        'class' => 'col-6'
    ];
    $config[$fieldName.'_placeholder'] = [
        'type' => 'text',
        'title' => 'Плейсхолдер поля "'.$caption.'"',
        'class' => 'col-6'
    ];
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