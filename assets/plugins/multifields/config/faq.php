<?php

return [
    'settings' => [],
    'templates' => [

        'group' => [

            'label'=>'Группа вопросов',
            'type' => 'row',
            'items.class' => 'd-block',
            'items' => [

                'children' => [
                    'type' => 'row',
                    'value'=>false,
                    'placeholder' => 'Вопросы и ответы',
                    'class' => 'col-12',
                    'items' => [
                        'question' => [
                            'type' => 'text',
                            'placeholder' => 'Вопрос',
                            'class' => 'col-12'
                        ],
                        'answer' => [
                            'type' => 'richtext',
                            'placeholder' => 'Ответ',
                            'class' => 'col-12'
                        ],
                    ]
                ],
            ]
        ],


    ]
];