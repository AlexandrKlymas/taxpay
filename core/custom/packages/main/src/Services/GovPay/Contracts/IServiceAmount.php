<?php


namespace EvolutionCMS\Services\Contracts;


interface IServiceAmount
{
    public function __construct($paymentAmount,$systemAmount,$totalAmount);

    public function getPaymentAmount();
    public function getSystemAmount();
    public function getTotalAmount();

}