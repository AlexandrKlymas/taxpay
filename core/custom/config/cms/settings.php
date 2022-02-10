<?php
//bot token 1344884733:AAFuMX77FHemD86kGuAaXZTshi-gRQ1fous

$isDev = file_exists(__DIR__.'/.dev');

if($isDev){

    $settings = [
        'dev'=>true,
        'site_path'=> '/home/govpay.div.net.ua/public_html/',
        'main_site_url'=>'govpay.div.net.ua',
        'phpPath'=>'php',
        'main_telegramBotToken'=>'1617584167:AAHVndKPTOrPQ_OENTcMKObMKynKYR9_xhE',
        'support_telegramBotToken'=>'5056325379:AAFYccHOrCD7iPO8BgzytCYG54HgL_g23I8',
    ];

}
else{
    $settings = [
        'prod'=>true,
        'site_path'=> '/home/gospay/govpay24.com/www/',
        'main_site_url'=>'govpay24.com',

        'phpPath'=>'/usr/local/php74/bin/php',
        'main_telegramBotToken'=>'1663883396:AAGRvf3vzS_OmK7CeWb7IEzGWi44BSkTsrU',
        'support_telegramBotToken'=>'5030632440:AAEq2mOuqmnqivX3RepiGbiIacNiMVdBWHo',
    ];
}



return array_merge([
    'main_site_https'=>'on',
    'urkAlpha'=>'А-ЯЁЇїЄєІі',


],$settings);
