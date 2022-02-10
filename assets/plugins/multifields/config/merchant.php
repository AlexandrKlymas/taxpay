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
        'company' => [
            'label' => 'Компания',
            'type' => 'row',
            'actions' => ['edit', 'del'],
            'value' => false,
            'class' => 'col-3',
            'items' => [
                'title' => [
                    'type' => 'text',
                    'title' => 'Страховая Компания',
                    'class' => 'col-12',
                ],
                'id' => [
                    'type' => 'text',
                    'title' => 'ОКПО компании',
                    'class' => 'col-12',
                ],
                'public' => [
                    'type' => 'text',
                    'title' => 'Публичный ключ',
                    'class' => 'col-12',
                ],
                'secret' => [
                    'type' => 'text',
                    'title' => 'Приватный ключ',
                    'class' => 'col-12',
                ],
            ],
        ],
    ],
];