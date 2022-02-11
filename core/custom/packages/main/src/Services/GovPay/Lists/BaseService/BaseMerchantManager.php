<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IMerchantManager;
use EvolutionCMS\Main\Services\GovPay\Dto\MerchantKeysDto;

class BaseMerchantManager implements IMerchantManager
{
    protected int $serviceId;

    public function __construct(int $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function getKeys():MerchantKeysDto
    {
        return $this->getDefaultKeys();
    }

    public function getDefaultKeys():MerchantKeysDto
    {
        $sandboxMode = (int) evo()->getConfig('g_sys_payment_sandbox');
        if($sandboxMode){
            return $this->getSandKeys();
        }
        return $this->getBaseKeys();
    }

    protected function getBaseKeys(): MerchantKeysDto
    {
        return new MerchantKeysDto(
            evo()->getConfig('g_sys_public_key'),
            evo()->getConfig('g_sys_private_key'),
            false
        );
    }

    protected function getSandKeys():MerchantKeysDto
    {
        return new MerchantKeysDto(
            evo()->getConfig('g_sys_public_key_sandbox'),
            evo()->getConfig('g_sys_private_key_sandbox'),
            true
        );
    }
}