<?php
namespace EvolutionCMS\Services\AmountCalculators;


use EvolutionCMS\Services\Contracts\IPaymentAmountCalculator;

class CalculatorDecoratorPayment implements IPaymentAmountCalculator
{
    private $calculator;

    public function __construct(IPaymentAmountCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function calculate($formData): float
    {
        return $this->calculator->calculate($formData);
    }
}