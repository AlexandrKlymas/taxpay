<?php
namespace EvolutionCMS\Main\Services\GovPay\Calculators\Forms;

use EvolutionCMS\Main\Services\GovPay\Models\Service;

class ServiceFieldPriceCalculator
{
    public function calculate(array $formData): float
    {
        $servicePrice = Service::findOrFail($formData['service'])->price;
        return round(floatval($servicePrice),2);
    }
}