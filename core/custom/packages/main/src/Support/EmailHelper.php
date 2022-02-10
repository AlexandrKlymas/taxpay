<?php

namespace EvolutionCMS\Main\Support;

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

class EmailHelper
{
    public static function sendEmail(string $to, string $body, string $from=null, string $subject='', string $message='', array $files=[]): bool
    {
//        if(empty($from)){
//            $from = evo()->getConfig('emailsender');
//        }
//
//        $SPApiClient = new ApiClient(
//            evo()->getConfig('g_sendpulse_id'),
//            evo()->getConfig('g_sendpulse_secret'),
//            new FileStorage()
//        );
//        $attachments = [];
//        foreach($files as $file){
//            $attachments[basename($file)] = file_get_contents($file);
//        }
//
//        $email = [
//            'html' => $body,
//            'text' => $message,
//            'subject' => $subject,
//            'from'=>[
//                'name' => 'Govpay24',
//                'email' => $from,
//            ],
//            'to'=>[
//                [
//                    'name' => '',
//                    'email' => $to,
//                ],
//            ],
//            'attachments'=>$attachments,
//        ];
//
//        $result = $SPApiClient->smtpSendMail($email);
//
        return evo()->sendmail([
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'body' => $body
        ], $message, $files);
//
//        return true;
    }
}