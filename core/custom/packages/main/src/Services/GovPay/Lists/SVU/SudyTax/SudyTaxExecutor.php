<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IExecutor;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;


class SudyTaxExecutor implements IExecutor
{

    /**
     */
    public function execute(ServiceOrder $serviceOrder)
    {
        $serviceOrder->updateServiceData('paid',true);
    }

    public function isCompleted(ServiceOrder $serviceOrder)
    {
        return !empty($serviceOrder->service_data['paid']);
    }
}