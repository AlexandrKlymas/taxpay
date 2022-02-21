<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;

interface IFinalCalculator
{
    public function calculate(array $formData): PaymentAmountDto;
}