<?php

namespace EvolutionCMS\Services\AmountCalculators;


class ServiceTaxCalculator
{
    private $fixSum;
    private $percent;
    private $minSum;
    private $maxSum;


    public function __construct($fixSum = '0', $percent = '0', $minSum = '0', $maxSum = '0')
    {

        $this->fixSum = $fixSum;
        $this->percent = $percent;
        $this->minSum = $minSum;
        $this->maxSum = $maxSum;
    }

    public function calculate(float $amount): float
    {
        $commission = 0.00;
        if ($this->fixSum != "0") {
            $commission = $this->fixSum;
        } else if ($this->percent != "0") {
            $commission = $amount * ((double)$this->percent) / 100;
            if ($this->minSum != "0" && (double)$this->minSum > $commission) {
                $commission = (double)$this->minSum;
            }
            if ($this->maxSum != "0" && (double)$this->maxSum < $commission) {
                $commission = (double)$this->maxSum;
            }
        }

        $commission = round($commission,2);
        return $commission;
    }
}