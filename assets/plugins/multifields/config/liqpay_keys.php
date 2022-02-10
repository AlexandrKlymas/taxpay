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
        'keys' => [
            'label' => 'Ключи',
            'type' => 'row',
            'actions' => ['edit', 'del'],
            'value' => false,
            'class' => 'col-3',
            'items' => [
                'public_key' => [
                    'type' => 'text',
                    'title' => 'Публичный ключ',
                    'class' => 'col-12',
                ],
                'private_key' => [
                    'type' => 'text',
                    'title' => 'Приватный ключ',
                    'class' => 'col-12',
                ],
            ],
        ],
    ],
];