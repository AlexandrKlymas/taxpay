<?php

$config = [];

$fieldsWithPlaceholder = [
    'full_name'=>'Фио',
    'phone'=>'Телефон для звязку',
    'email'=>'Email',
    'service'=>'Оберіть послугу',
    'regional_service_center'=>'Обрати регіон',
    'district'=>'Район',
    'address'=>'Адреса',
    'floor'=>'Поверх',
    'rooms'=>'Кімнат',
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
$fieldsWithoutPlaceholder = [
    'pet'=>'Домашні тварини',
    'secure'=>'Охоронне обладнання',
];
foreach ($fieldsWithoutPlaceholder as $fieldName => $caption) {
    $config[$fieldName.'_title'] = [
        'type' => 'text',
        'title' => 'Название поля "'.$caption.'"',
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