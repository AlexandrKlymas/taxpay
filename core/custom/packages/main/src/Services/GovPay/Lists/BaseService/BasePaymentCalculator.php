<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;

class BasePaymentCalculator implements IPaymentCalculator
{

    /**
     * @var SumCalculator
     */
    private SumCalculator $sumCalculator;

    public function __construct(SumCalculator $sumCalculator)
    {
        $this->sumCalculator = $sumCalculator;
    }

    public function calculate(array $fieldValues): float
    {
        return $this->sumCalculator->calculate($fieldValues);
    }
}