<?php


namespace EvolutionCMS\Services\Dto;


class ServiceAmount implements \EvolutionCMS\Services\Contracts\IServiceAmount
{

    private $paymentAmount;
    private $serviceAmount;
    private $totalAmount;

    public function __construct($paymentAmount, $serviceAmount, $totalAmount)
    {
        $this->paymentAmount = $paymentAmount;
        $this->serviceAmount = $serviceAmount;
        $this->totalAmount = $totalAmount;
    }

    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    public function getServiceAmount()
    {

        // TODO: Implement getServiceAmount() method.
    }

    public function getTotalAmount()
    {
        // TODO: Implement getTotalAmount() method.
    }
}