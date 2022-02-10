<?php


namespace EvolutionCMS\Main\Services\GovPay\Dto;


class CommissionDto
{
    public function getLiqpayCommissionAutoCalculated()
    {
        return $this->liqpayCommissionAutoCalculated;
    }

    public function getBankCommission()
    {
        return $this->bankCommission;
    }
    public function getProfit()
    {
        return $this->profit;
    }

    private $liqpayCommissionAutoCalculated;
    private $bankCommission;
    private $profit;

    public function __construct($liqpayCommissionAutoCalculated, $bankCommission, $profit)
    {
        $this->liqpayCommissionAutoCalculated = $liqpayCommissionAutoCalculated;
        $this->bankCommission = $bankCommission;
        $this->profit = $profit;
    }
}