<?php

namespace EvolutionCMS\Main\Services\GovPay\Factories\Calculators;


use EvolutionCMS\Main\Services\GovPay\Calculators\ServiceFeeCalculator;
use EvolutionCMS\Main\Services\GovPay\Models\Commission;

class FeeCalculatorFactory
{
    public static function build($serviceId): ServiceFeeCalculator
    {
        $commission = Commission::where('form_id', $serviceId)->limit(1)->first();

        if ($commission) {
            return new ServiceFeeCalculator($commission->fix_summ, $commission->percent, $commission->min_summ, $commission->max_summ);
        }

        return new ServiceFeeCalculator();
    }
}