<?php

namespace EvolutionCMS\Main\Services\GovPay\Dto;

class PaymentAmountDto
{
    private float $sum;
    private float $serviceFee;
    private float $total;

    public function __construct($sum, $serviceFee, $total)
    {
        $this->sum = $sum;
        $this->serviceFee = $serviceFee;
        $this->total = $total;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function getServiceFee(): float
    {
        return $this->serviceFee;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}