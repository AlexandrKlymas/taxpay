<?php

return [
    'caption' => 'Получатели',
    'introtext' => '',
    //'roles' => [1], // admin only
    'settings' => [

         'recipients_govpay24_edrpou'=>[
             'caption'=>'ЄДРПОУ счета, на который уходит комисия сервиса',
             'type'=>'text'
         ],
        'recipients_govpay24_account'=>[
            'caption'=>'р/р (iban) счета, на который уходит комисия сервиса',
            'type'=>'text'
        ],

        'recipients_govpay24_mfo'=>[
            'caption'=>'МФО счета, на который уходит комисия сервиса',
            'type'=>'text'
        ],

        'recipients_govpay24_purpose'=>[
            'caption'=>'Шаблон "назначения платежа" для перевода  комисии сервиса',
            'type'=>'text'
        ],

        'recipients_govpay24_name'=>[
            'caption'=>'Имя получателя для перевода комисии сервиса',
            'type'=>'text'
        ],


        'fine_photo_fixation_recipient_id'=>[
            'caption'=>'Id получателя из данными для штрафов по фотофиксации',
            'type'=>'text'
        ],
    ],
];
