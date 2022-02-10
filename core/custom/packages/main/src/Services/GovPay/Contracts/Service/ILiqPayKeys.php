<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

use EvolutionCMS\Main\Services\GovPay\Dto\LiqPayKeysDto;

interface ILiqPayKeys
{
    public function getLiqPayKeys():LiqPayKeysDto;
}