<?php


namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

interface IPaymentCalculator
{
    public function calculate(array $fieldValues);
}