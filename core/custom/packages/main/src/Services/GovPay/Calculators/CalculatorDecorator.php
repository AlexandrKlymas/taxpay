<?php
namespace EvolutionCMS\Services\AmountCalculators;


use EvolutionCMS\Services\Contracts\IAmountCalculator;

class CalculatorDecorator implements IAmountCalculator
{
    private $calculator;

    public function __construct(IAmountCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function calculate($formData): float
    {
        return $this->calculator->calculate($formData);
    }
}