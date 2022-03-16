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

            if($serviceOrder->service_id == 179){
                $enMenu = [
                    'Послуги'=>'Services',
                    'Питання та відповіді' => 'FAQ',
                    'Оферта'=>'Offer',
                    'Контакти'=>'Contacts',
                ];

                foreach ($this->data['menu'] as $k=>$menu){
                    foreach($enMenu as $key=>$enMenuItem){
                        if($key==$menu['pagetitle']){
                            $this->data['menu'][$k]['pagetitle'] = $enMenuItem;
                        }
                    }
                }
                $this->data['service_id'] = $serviceOrder->service_id;
            }
        }

        $this->data['paymentParams'] = $paymentParams??[];
    }
}