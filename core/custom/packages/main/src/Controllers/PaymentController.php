<?php

namespace EvolutionCMS\Main\Controllers;


use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\LiqPay\LiqPayService;

class PaymentController extends BaseController
{
    public function render()
    {
        if(!empty($_GET['order_id'])){
            $serviceOrder = ServiceOrder::findOrFail($_GET['order_id']);
            $paymentParams = (new LiqPayService())->getPaymentDataAndSignature($serviceOrder);
        }

        $this->data['paymentParams'] = $paymentParams??[];
    }
}