<?php
namespace EvolutionCMS\Main\Services\GovPay\Calculators\Forms;

class SumCalculator
{

    public function calculate($formData): float
    {
        $sum = 0;
        if(isset($formData['sum'])){
            $sum = round(floatval($formData['sum']),2);
        }
        return $sum;
    }
}