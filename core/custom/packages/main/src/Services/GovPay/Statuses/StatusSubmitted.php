<?php


namespace EvolutionCMS\Main\Services\GovPay\Statuses;


use EvolutionCMS\Main\Services\GovPay\Contracts\IStatus;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class StatusSubmitted implements IStatus
{

    public function getTitle()
    {
        return 'Підтверджено';
    }

    public static function getKey()
    {
        return 'submitted';
    }

    public function canChange(ServiceOrder $serviceOrder)
    {
        return in_array($serviceOrder->status,[
            StatusWait::getKey(),
            StatusError::getKey(),
            StatusFailure::getKey(),
            StatusReversed::getKey(),
            StatusQuestion::getKey(),
            StatusSuccess::getKey(),
        ]);
    }

    public function change(ServiceOrder $serviceOrder, array $additionalData)
    {
        $serviceOrder->status = self::getKey();
        $serviceOrder->changeRecipientsStatus(PaymentRecipient::STATUS_CONFIRMED);
        $serviceOrder->save();
    }

    public function isPaid(ServiceOrder $serviceOrder)
    {
        return true;
    }

}