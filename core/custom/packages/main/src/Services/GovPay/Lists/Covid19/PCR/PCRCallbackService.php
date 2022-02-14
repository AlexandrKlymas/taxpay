<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseCallbackService;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotPlr;
use PHPMailer\PHPMailer\Exception;

class PCRCallbackService extends BaseCallbackService
{
    /**
     * @throws Exception
     */
    public function liqPayCallback(array $params)
    {
        if($this->isValidLiqPayErrorCallbackRequest($params)){
            $serviceOrder = ServiceOrder::where('payment_hash',$params['request']['order_id'])->first();
            if($this->isValidServiceOrder($serviceOrder)){
                $this->sendErrorTelegramNotify($serviceOrder);
            }
        }
        if(!empty($this->errors)){
            $this->logErrors('liqPayErrorCallback',$params);
        }
    }

    private function sendErrorTelegramNotify($serviceOrder)
    {
        $notify = $serviceOrder->form_data['full_name']. PHP_EOL
            .'- замовлення: '.$serviceOrder->id . PHP_EOL
            .'- сума: '. $serviceOrder->total .' грн'. PHP_EOL
            .'- статус: '.hex2bin('E29D8C').' помилка оплати';

        $to = $serviceOrder->form_data['medical_center'];

        TelegramBotPlr::sendNotify($to,$notify);
    }

    private function sendSuccessTelegramNotify($serviceOrder)
    {
        $notify = $serviceOrder->form_data['full_name']. PHP_EOL
            .'- замовлення: '.$serviceOrder->id . PHP_EOL
            .'- сума: '. $serviceOrder->total .' грн'. PHP_EOL
            .'- статус: '.hex2bin('E29C85').' оплата пройшла успішно';

        $to = $serviceOrder->form_data['medical_center'];

        TelegramBotPlr::sendNotify($to,$notify);
    }

    public function invoicePDFGenerated(array $params)
    {
        if($this->isValidServiceOrder($params['service_order'])){
            $serviceOrder = $params['service_order'];
            $this->sendSuccessTelegramNotify($serviceOrder);
        }
        if(!empty($this->errors)){
            $this->logErrors('PCR BOT Errors Invoice',$params);
        }
    }
}