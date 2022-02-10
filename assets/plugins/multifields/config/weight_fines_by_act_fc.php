<?php

$config = [];

$config['iban'] = ['type'=>'text','title'=>'IBAN','class'=>'col-6',];
$config['edrpou'] = ['type'=>'text','title'=>'ОКПО(ЄДРПОУ)','class'=>'col-6',];
$config['description'] = ['type'=>'text','title'=>'Отримувач','class'=>'col-6',];
$config['bankName'] = ['type'=>'text','title'=>'Банк','class'=>'col-6',];

$config['description_title'] = ['type'=>'text','title'=>'Название поля "Фотофіксація"','class'=>'col-6',];
$config['description_value'] = ['type'=>'text','title'=>'Значення поля "Фотофіксація"','class'=>'col-6',];

$fieldsWithPlaceholder = [
    'full_name'=>'Прізвище, ім`я та по-батькові',
    'fine_series'=>'Серія постанови',
    'fine_number'=>'Номер постанови',
    'sum'=>'Сума штрафу',
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