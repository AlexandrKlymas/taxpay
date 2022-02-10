<?php


namespace EvolutionCMS\Main\Services\GovPay\Statuses;


use EvolutionCMS\Main\Services\GovPay\Contracts\IStatus;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class StatusReversed implements IStatus
{

    public function getTitle()
    {
        return 'Платіж повернуто';
    }

    public static function getKey()
    {
        return 'reversed';
    }

    public function canChange(ServiceOrder $serviceOrder)
    {
        return [StatusWait::getKey(), StatusError::getKey(), StatusFailure::getKey(), StatusReversed::getKey()];
    }

    public function change(ServiceOrder $serviceOrder, array $additionalData)
    {
        $serviceOrder->liqpay_status = $additionalData['status'];
        $serviceOrder->liqpay_response = $additionalData;
        $serviceOrder->liqpay_transaction_id = $additionalData['transaction_id'];
        $serviceOrder->status = self::getKey();

        $serviceOrder->changeRecipientsStatus(PaymentRecipient::STATUS_WAIT);

        $serviceOrder->save();
    }

    public function isPaid(ServiceOrder $serviceOrder)
    {
        return false;
    }
}