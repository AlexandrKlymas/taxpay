<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines;


use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BasePaymentCalculator;

class FinesPaymentCalculator extends BasePaymentCalculator
{
    public function calculate(array $fieldValues):float
    {
        $fine = Fine::findOrFail($fieldValues['fine_id']);

        return floatval($fine->data['sumPenalty']);
    }
}