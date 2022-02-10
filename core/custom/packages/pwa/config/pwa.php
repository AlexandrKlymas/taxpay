<?php return [
    'name' => 'GOVPAY24',
    'short_name' => 'GOVPAY24',
    'apple-touch-icon' => '/theme/favicon/pwaiconyellowapple.png',
    'theme_color' => '#ffde59',
    'background_color' => '#ffde59',
    'display' => 'standalone',
    'scope' => '/',
    'start_url' => '/',
    'icons' => [
        [
            'src'=> '/theme/favicon/pwaiconyellow.png',
            'sizes'=>'192x192',
            'type'=> 'image/png',
            'purpose'=> 'any maskable'
        ],
        [
            'src'=>'/theme/favicon/pwaiconyellow.png',
            'sizes'=>'512x512',
            'type'=> 'image/png',
            'purpose'=>'any maskable'
        ]
    ],
    'serviceWorkerSettings' => [
        'startPageId' => '1',
        'offlinePageId' => '1',
          'cacheDocsIds' => '1',
          'cacheFiles' => ['/theme/css/', '/theme/js/']
    ]
];