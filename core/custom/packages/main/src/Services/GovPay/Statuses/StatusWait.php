<?php
namespace EvolutionCMS\Main\Services\GovPay\Statuses;


use EvolutionCMS\Main\Services\GovPay\Contracts\IStatus;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class StatusWait implements IStatus
{

    public function getTitle()
    {
        return 'Очікує оплати';
    }

    public static function getKey()
    {
        return 'wait';
    }

    public function canChange(ServiceOrder $serviceOrder)
    {
        return false;
    }

    public function change(ServiceOrder $serviceOrder, array $additionalData)
    {
        $serviceOrder->status = $this->getKey();
        $serviceOrder->changeRecipientsStatus(PaymentRecipient::STATUS_WAIT);
        $serviceOrder->save();
    }

    public function isPaid(ServiceOrder $serviceOrder)
    {
        return false;
    }

}