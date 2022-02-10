<?php


namespace EvolutionCMS\Main\Services\GovPay\Statuses;


use Carbon\Carbon;
use Composer\XdebugHandler\Status;
use EvolutionCMS\Main\Services\GovPay\Contracts\IStatus;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class StatusSuccess implements IStatus
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    public function __construct()
    {
        $this->serviceManager = new ServiceManager();
    }

    public function getTitle()
    {
        return 'Сплачено';
    }

    public static function getKey()
    {
        return 'success';
    }

    public function canChange(ServiceOrder $serviceOrder)
    {
        return in_array($serviceOrder->status,[StatusWait::getKey(), StatusError::getKey(), StatusFailure::getKey(), StatusReversed::getKey()]);
    }

    public function change(ServiceOrder $serviceOrder, array $additionalData)
    {

        $serviceOrder->liqpay_status = $additionalData['status'];

        if(!empty($additionalData)){
            $serviceOrder->liqpay_response = $additionalData;
            $serviceOrder->liqpay_transaction_id = $additionalData['transaction_id'];
            $serviceOrder->liqpay_payment_date = Carbon::createFromTimestampMs($additionalData['create_date']);
            $serviceOrder->liqpay_real_commission = $additionalData['receiver_commission'];
        }
        if(!empty($additionalData['sender_phone'])){
            $serviceOrder->updateFormData('phone',$additionalData['sender_phone']);
        }



        $serviceOrder->status = self::getKey();

        $serviceOrder->changeRecipientsStatus(PaymentRecipient::STATUS_WAIT);
        $serviceOrder->save();

//        $this->serviceManager->generateInvoice($serviceOrder->id);
        $this->serviceManager->executeServiceOrder($serviceOrder);

    }

    public function isPaid(ServiceOrder $serviceOrder)
    {
        return true;
    }

}