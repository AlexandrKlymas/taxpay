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
        'currencies' => [
            'label' => 'Валюта',
            'type' => 'row',
            'actions' => ['edit', 'del'],
            'value' => false,
            'class' => 'col-3',
            'items' => [
                'alias' => [
                    'type' => 'text',
                    'title' => 'Значення',
                    'class' => 'col-12',
                ],
                'caption' => [
                    'type' => 'text',
                    'title' => 'Текст',
                    'class' => 'col-12',
                ],
            ],
        ],
    ],
];