<?php

use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias;

Event::listen('evolution.OnCheckFound',function ($params){

    $servicesAlias = new ServicesAlias();
    $serviceOrder = $params['service_order'];
    try{
        $service = $servicesAlias->getService($serviceOrder->service_id);
    }catch (ServiceNotFoundException $e){
        evo()->logEvent(1,2,json_encode($params),'NO checkFoundCallback');
        return true;
    }

    if(!empty($service['callback_service'])){
        (new $service['callback_service']($serviceOrder->service_id,$service['alias']))->checkFoundCallback($params);
    }

    return true;
});