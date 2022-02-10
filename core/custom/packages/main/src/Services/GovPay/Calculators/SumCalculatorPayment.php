<?php


namespace EvolutionCMS\Services\AmountCalculators;


use EvolutionCMS\Services\Contracts\IPaymentAmountCalculator;

class SumCalculatorPayment implements IPaymentAmountCalculator
{

    public function calculate($formData): float
    {
        $sum = 0;
        if(isset($formData['sum'])){
            $sum = floatval($formData['sum']);
        }
        return $sum;
    }
}