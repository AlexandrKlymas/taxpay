<?php

namespace EvolutionCMS\Main\Services\GovPay\Support;

use EvolutionCMS\Main\Services\GovPay\Dto\LiqPayKeysDto;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use Exception;

class MerchantHelper
{
    public function getServiceKeys($serviceOrder): LiqPayKeysDto
    {
        $servicesAlias = new ServicesAlias();

        try{
            $service = $servicesAlias->getService($serviceOrder->service_id);
        }catch (ServiceNotFoundException $e){
            throw new Exception('no service detected');
        }

        if(empty($service['liqpay_keys'])){
            throw new Exception('no keys detected on service');
        }

        return $this->getServiceLiqPayKeys($service['liqpay_keys']);
    }

    /**
     * @throws Exception
     */
    public function getKeysByServiceId(int $serviceId=null):LiqPayKeysDto
    {
        if(empty($serviceId)){
            throw new Exception('empty serviceId');
        }
        $serviceOrder = ServiceOrder::where('service_id',$serviceId)->first();
        if(empty($serviceOrder)){
            throw new Exception('no orderService');
        }

        return $this->getServiceKeys($serviceOrder);
    }

    private function getServiceLiqPayKeys(string $class):LiqPayKeysDto
    {
        return (new $class)->getLiqPayKeys();
    }

    public function getDefaultKeys():LiqPayKeysDto
    {
        $sandboxMode = (int) evo()->getConfig('g_sys_payment_sandbox');
        if($sandboxMode){
            return $this->getSandKeys();
        }
        return $this->getBaseKeys();
    }

    private function getBaseKeys(): LiqPayKeysDto
    {
        return new LiqPayKeysDto(
            evo()->getConfig('g_sys_public_key'),
            evo()->getConfig('g_sys_private_key'),
            false
        );
    }

    private function getSandKeys():LiqPayKeysDto
    {
        return new LiqPayKeysDto(
            evo()->getConfig('g_sys_public_key_sandbox'),
            evo()->getConfig('g_sys_private_key_sandbox'),
            true
        );
    }
}