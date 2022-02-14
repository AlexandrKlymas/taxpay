<?php

$config = [];

$fieldsWithPlaceholder = [
    'full_name'=>'Прізвище, ім`я та по-батькові',
    'ipn'=>'ІПН',
    'region'=>'Область/місто',
    'district'=>'Районий відділ',
    'series'=>'Номер постанови',
    'date'=>'Дата постанови',
    'sum'=>'Сума оплати',
    'total'=>'До сплати',

];
$fieldsWithoutPlaceholder = ['total'];
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
    'settings' => [
        'toolbar' => [
            'export' => true,
            'import' => true,
        ],
    ],
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