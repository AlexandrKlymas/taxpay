<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;

class PCRPaymentCalculator implements IPaymentCalculator
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