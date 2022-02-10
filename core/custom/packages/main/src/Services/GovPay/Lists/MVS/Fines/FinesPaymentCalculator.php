<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;

class FinesPaymentCalculator implements IPaymentCalculator
{

    private $serviceId;
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
        $fine = Fine::findOrFail($fieldValues['fine_id']);

        return $fine->data['sumPenalty'];
    }
}