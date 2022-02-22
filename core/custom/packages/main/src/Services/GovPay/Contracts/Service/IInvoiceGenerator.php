<?php
namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;


use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

interface IInvoiceGenerator
{
    public function generate(ServiceOrder $serviceOrder):string;
}