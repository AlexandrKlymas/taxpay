<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\FinesByAct;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;

class FinesByActPaymentCalculator implements \EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator
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