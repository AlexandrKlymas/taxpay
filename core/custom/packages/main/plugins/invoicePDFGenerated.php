<?php

use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias;

Event::listen('evolution.OnInvoicePDFGenerated',function ($params){

    $servicesAlias = new ServicesAlias();
    $serviceOrder = $params['service_order'];
    try{
        $service = $servicesAlias->getService($serviceOrder->service_id);
    }catch (ServiceNotFoundException $e){

        return true;
    }

    if(!empty($service['callback_service'])){
        (new $service['callback_service']($serviceOrder->service_id,$service['alias']))->invoicePDFGenerated($params);
    }

    return true;
});