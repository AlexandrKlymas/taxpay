<?php

namespace EvolutionCMS\Main\Services\GovPay\Factories\Calculators;


use EvolutionCMS\Main\Services\GovPay\Calculators\CommissionsCalculator;
use function EvolutionCMS;

class CommissionsCalculatorFactory
{
    public static function build(): CommissionsCalculator
    {
        $evo = EvolutionCMS();

        $liqPayCommissionPercent = floatval($evo->getConfig('g_liqpey_commission'));
        $liqPayMinCommission = floatval($evo->getConfig('g_liqpey_commission_min'));
        $bankCommissionPercent = floatval($evo->getConfig('g_bank_commission'));
        $bankMinCommission = floatval($evo->getConfig('g_bank_commission_min'));

        return new CommissionsCalculator($liqPayCommissionPercent,$liqPayMinCommission, $bankCommissionPercent, $bankMinCommission);
    }
}