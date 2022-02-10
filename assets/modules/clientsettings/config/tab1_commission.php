<?php


return [
    'caption' => 'Комиссия',
    'roles' => [1], // admin only
    'settings' => [
        'liqpey_commission' => [
            'caption' => 'Комиссия Liqpey (процент, без пробелов, через точку)',
            'type'  => 'text',
        ],
        'liqpey_commission_min' => [
            'caption' => 'Комиссия Liqpey (мин сумма, без пробелов, через точку)',
            'type'  => 'text',
        ],
        'bank_commission' => [
            'caption' => 'Комиссия Банка (процент, без пробелов, через точку)',
            'type'  => 'text',
        ],
        'bank_commission_min' => [
            'caption' => 'Комиссия Банка (мин сумма, без пробелов, через точку)',
            'type'  => 'text',
        ],



    ],
];
