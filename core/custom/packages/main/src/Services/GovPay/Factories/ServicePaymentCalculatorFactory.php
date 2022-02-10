<?php

namespace EvolutionCMS\Services\Factories;


use EvolutionCMS\Services\AmountCalculators\ServicePaymentCalculator;
use EvolutionCMS\Services\Models\Commission;

class ServicePaymentCalculatorFactory
{
    public static function build($serviceId): ServicePaymentCalculator
    {
        $commission = Commission::where('form_id', $serviceId)->limit(1)->first();

        if ($commission) {
            return new ServicePaymentCalculator($commission->fix_summ, $commission->percent, $commission->min_summ, $commission->max_summ);
        }

        return new ServicePaymentCalculator();
    }
}