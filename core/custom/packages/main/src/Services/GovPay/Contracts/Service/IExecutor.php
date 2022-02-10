<?php
namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

interface IExecutor
{
    public function execute(ServiceOrder $serviceOrder);

    public function isCompleted(ServiceOrder $serviceOrder);
}