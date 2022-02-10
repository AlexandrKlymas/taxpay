<?php


return [
    'settings' => [],
    'templates' => [

        'form' => [
            'label' => 'Настройка формы',
            'type' => 'row',

            'actions' => ['add', 'edit', 'del'],

            'value' => false,

            'items' => [

                'full_name_title' => [
                    'type' => 'text',
                    'title' => 'Название поля "ФИО"',
                    'class' => 'col-6'
                ],
                'full_name_placeholder' => [
                    'type' => 'text',
                    'title' => 'Плейсхолдер поля "ФИО"',
                    'class' => 'col-6'
                ],


                'address_title' => [
                    'type' => 'text',
                    'title' => 'Название поля "Адрес"',
                    'class' => 'col-6'
                ],
                'address_placeholder' => [
                    'type' => 'text',
                    'title' => 'Плейсхолдер поля "Адрес"',
                    'class' => 'col-6'
                ],


                'period_from_title' => [
                    'type' => 'text',
                    'title' => 'Название поля "Період сплати з"',
                    'class' => 'col-6'
                ],
                'period_from_placeholder' => [
                    'type' => 'text',
                    'title' => 'Плейсхолдер поля "Період сплати з"',
                    'class' => 'col-6'
                ],

                'period_to_title' => [
                    'type' => 'text',
                    'title' => 'Название поля "Період сплати по"',
                    'class' => 'col-6'
                ],
                'period_to_placeholder' => [
                    'type' => 'text',
                    'title' => 'Плейсхолдер поля "Період сплати по"',
                    'class' => 'col-6'
                ],


                'police_security_account_title' => [
                    'type' => 'text',
                    'title' => 'Название поля "Номер особового рахунку"',
                    'class' => 'col-6'
                ],
                'police_security_account_placeholder' => [
                    'type' => 'text',
                    'title' => 'Плейсхолдер поля "Номер особового рахунку"',
                    'class' => 'col-6'
                ],

                'sum_title' => [
                    'type' => 'text',
                    'title' => 'Название поля "Сума сплати"',
                    'class' => 'col-6'
                ],
                'sum_placeholder' => [
                    'type' => 'text',
                    'title' => 'Плейсхолдер поля "Сума сплати"',
                    'class' => 'col-6'
                ],


            ],
            'limit' => 1
        ],


    ]
];