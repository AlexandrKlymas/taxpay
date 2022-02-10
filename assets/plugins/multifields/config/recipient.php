<?php

return [
    'settings' => [
        'toolbar' => [
            'export' => true,
            'import' => true,
            'fullscreen' => true
        ],
    ],
    'templates' => [
        'recipient' => [
            'label' => 'Получатель',
            'type' => 'row',
            'actions' => ['edit', 'del'],
            'value' => false,
            'class' => 'col-3',
            'items' => [
                'edrpou' => [
                    'type' => 'text',
                    'title' => 'ЄДРПОУ',
                    'class' => 'col-12',
                ],
                'iban' => [
                    'type' => 'text',
                    'title' => 'IBAN',
                    'class' => 'col-12',
                ],
                'bank_name' => [
                    'type' => 'text',
                    'title' => 'Банк',
                    'class' => 'col-12',
                ],
                'description' => [
                    'type' => 'text',
                    'title' => 'Отримувач',
                    'class' => 'col-12',
                ],
            ],
        ],
    ],
];