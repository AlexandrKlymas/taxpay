<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

interface IOrderGenerator
{
    public function createOder(array $formData):ServiceOrder;
}