<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts;


use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

interface IStatus
{

    public function getTitle();

    public static function getKey();

    public function canChange(ServiceOrder $serviceOrder);

    public function change(ServiceOrder $serviceOrder, array $additionalData);

    public function isPaid(ServiceOrder $serviceOrder);

}