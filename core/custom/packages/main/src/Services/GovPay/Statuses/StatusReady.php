<?php

namespace EvolutionCMS\Main\Services\GovPay\Statuses;


use EvolutionCMS\Main\Services\GovPay\Contracts\IStatus;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class StatusReady implements IStatus
{

    public function getTitle()
    {
        return 'Проведено';
    }

    public static function getKey()
    {
        return 'ready';
    }

    public function canChange(ServiceOrder $serviceOrder)
    {
        return true;
    }

    public function change(ServiceOrder $serviceOrder, array $additionalData)
    {
        $serviceOrder->status = self::getKey();

        $serviceOrder->changeRecipientsStatus(PaymentRecipient::STATUS_FINISHED);

        $serviceOrder->save();
    }

    public function isPaid(ServiceOrder $serviceOrder)
    {
        return true;
    }
}