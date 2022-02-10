<?php

namespace EvolutionCMS\Main\Controllers;


use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Support\MerchantHelper;
use EvolutionCMS\Main\Services\LiqPay\LiqPayService;

class PaymentController extends BaseController
{
    public function render()
    {
        if(!empty($_GET['order_id'])){
            $serviceOrder = ServiceOrder::findOrFail($_GET['order_id']);

            try{
                $liqPayController = new LiqPayController($serviceOrder->service_id);
            } catch (\Exception $e) {
                evo()->logEvent('1',2,'LiqPay Request: <pre>'.print_r($_GET,true).'</pre>',
                    'PaymentController cannot create LiqPayController');
            }

            if(!empty($liqPayController)){
                $liqPayService = $liqPayController->getLiqPayService();
                $paymentParams = $liqPayService->getPaymentDataAndSignature($serviceOrder);
            }

        }
        $this->data['paymentParams'] = $paymentParams??[];
    }
}