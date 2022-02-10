<?php

return [
    'settings' => [],
    'templates' => [
        'title' => [
            'value'=>false,
            'label'=>'Заголовок',
            'type' => 'row',
            'items.class' => 'd-block',
            'items' => [
                'text' => [
                    'type' => 'text',
                    'placeholder' => 'Заголовок',
                    'class' => 'col-12'
                ],

            ]
        ],
        'intro' => [
            'value'=>false,
            'label'=>'Вступление',
            'type' => 'row',
            'items.class' => 'd-block',
            'items' => [
                'text' => [
                    'type' => 'text',
                    'placeholder' => 'Текст',
                    'class' => 'col-12'
                ],

            ]
        ],
        'text' => [
            'value'=>false,
            'label'=>'Текст',
            'type' => 'row',
            'items.class' => 'd-block',
            'items' => [
                'text' => [
                    'type' => 'richtext',
                    'placeholder' => 'Текст',
                    'class' => 'col-12'
                ],

            ]
        ],
        'text_with_border' => [
            'value'=>false,
            'label'=>'Текст из рамкой и отступом',
            'type' => 'row',
            'items.class' => 'd-block',
            'items' => [
                'text' => [
                    'type' => 'richtext',
                    'placeholder' => 'Текст',
                    'class' => 'col-12'
                ],

            ]
        ],
    ]
];