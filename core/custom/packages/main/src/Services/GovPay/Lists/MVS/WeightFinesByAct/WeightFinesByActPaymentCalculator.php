<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\WeightFinesByAct;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;

class WeightFinesByActPaymentCalculator implements IPaymentCalculator
{

    /**
     * @var SumCalculator
     */
    private $sumCalculator;

    public function __construct(SumCalculator $sumCalculator)
    {
        $this->sumCalculator = $sumCalculator;
    }

    public function calculate(array $fieldValues)
    {
       return $this->sumCalculator->calculate($fieldValues);
    }
}