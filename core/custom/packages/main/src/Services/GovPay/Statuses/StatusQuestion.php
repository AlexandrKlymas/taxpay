<?php


namespace EvolutionCMS\Main\Services\GovPay\Statuses;


use Composer\XdebugHandler\Status;
use EvolutionCMS\Main\Services\GovPay\Contracts\IStatus;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class StatusQuestion implements IStatus
{

    public function getTitle()
    {
        return 'Нестандартная ситуація';
    }

    public static function getKey()
    {
        return 'question';
    }

    public function canChange(ServiceOrder $serviceOrder)
    {
        return in_array($serviceOrder->status,[
            StatusWait::getKey(),
            StatusError::getKey(),
            StatusFailure::getKey(),
            StatusReversed::getKey(),
            StatusSuccess::getKey(),
        ]);
    }

    public function change(ServiceOrder $serviceOrder, array $additionalData)
    {
        $serviceOrder->status = self::getKey();
        $serviceOrder->changeRecipientsStatus(PaymentRecipient::STATUS_WAIT);
        $serviceOrder->save();
    }

    public function isPaid(ServiceOrder $serviceOrder)
    {
        return true;
    }
}