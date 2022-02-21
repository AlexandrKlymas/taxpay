<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

interface IFeeCalculator
{
    public function setCommission(float $percent = 0.00, float $min = 0.00, float $max = 0.00, float $fix = 0.00);
    public function calculate(float $sum): float;
}