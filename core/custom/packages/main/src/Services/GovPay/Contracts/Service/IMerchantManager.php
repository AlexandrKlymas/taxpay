<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

use EvolutionCMS\Main\Services\GovPay\Dto\MerchantKeysDto;

interface IMerchantManager
{
    public function __construct(int $serviceId);
    public function getKeys():MerchantKeysDto;
    public function getDefaultKeys():MerchantKeysDto;
}