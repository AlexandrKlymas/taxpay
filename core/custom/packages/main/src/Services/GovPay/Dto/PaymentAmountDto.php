<?php


namespace EvolutionCMS\Main\Services\GovPay\Dto;



class PaymentAmountDto
{

    private $sum;
    private $serviceFee;
    private $total;

    public function __construct($sum, $serviceFee, $total)
    {
        $this->sum = $sum;
        $this->serviceFee = $serviceFee;
        $this->total = $total;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function getServiceFee()
    {
        return $this->serviceFee;
    }

    public function getTotal()
    {
        return $this->total;
    }
}