<?php

return [
    'caption' => 'System',
    'introtext' => '',
    //'roles' => [1], // admin only
    'settings' => [
        'sys_form_email' => [
            'caption' => 'E-mail для писем из форм',
            'type'  => 'text',
        ],
        'sys_public_key' => [
            'caption' => 'Liqpay public_key',
            'type'  => 'text',
        ],
        'sys_private_key' => [
            'caption' => 'Liqpay private_key',
            'type'  => 'text',
        ],

        'sys_public_key_sandbox' => [
            'caption' => 'Liqpay public_key Sandbox',
            'type'  => 'text',
        ],
        'sys_private_key_sandbox' => [
            'caption' => 'Liqpay private_key Sandbox',
            'type'  => 'text',
        ],

        'sys_payment_sandbox'=>[
            'caption' => 'Тестовый платеж',
            'type'  => 'dropdown',
            'elements'  => 'Да==1||Нет==0',
        ],

        'sys_sudytax_mac' => [
            'caption' => 'COURT.GOV.UA mac проверка подлинности запросов',
            'type'  => 'text',
        ],

        'sys_dpstax_mac' => [
            'caption' => 'TAX.GOV.UA mac проверка подлинности запросов',
            'type'  => 'text',
        ],


        'bank_ftp_server' => [
            'caption' => 'Адрес ftp сервера банка',
            'type'  => 'text',
        ],

        'bank_ftp_user' => [
            'caption' => 'Пользователь ftp сервера банка',
            'type'  => 'text',
        ],

        'bank_ftp_pass' => [
            'caption' => 'Пароль пользователя ftp сервера банка',
            'type'  => 'text',
        ],


        'bank_file_number_export' => [
            'caption' => 'Номер файла, для экспорта в банк (001), если пусто будет расчитан автоматичиски',
            'type'  => 'text',
        ],

        'currencies' => [
            'caption' => 'Валюти',
            'type'  => 'custom_tv:multifields',
        ],
 
    ],
];
