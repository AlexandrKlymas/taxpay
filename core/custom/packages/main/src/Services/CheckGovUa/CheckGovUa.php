<?php


namespace EvolutionCMS\Main\Services\CheckGovUa;

use EvolutionCMS\Main\Services\CheckGovUa\Exceptions\CheckNotFoundException;
use EvolutionCMS\Main\Services\CheckGovUa\Exceptions\NotAuthorizeRequestException;
use EvolutionCMS\Main\Services\GovPay\Managers\StatusManager;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;

class CheckGovUa
{
    private $secretKey = 'raE@88PxJo!cuyX#2efCGAWaTF';

    public function getInfoAboutCheck($request)
    {
        $this->authorizeRequest($request);
        return $this->findCheck($request);

    }

    private function authorizeRequest($request)
    {

        $originalHash = $this->base64url_encode(hash_hmac("sha256", $request['checkId'] . $request['time'], $this->secretKey, true));


        if ($originalHash !== $request['hmac']) {
            throw new NotAuthorizeRequestException();
        }
    }

    private function findCheck($request)
    {
        $checkId = preg_replace("/[^0-9]/", '', $request['checkId']);

        $statusManager = new StatusManager();


        /** @var PaymentRecipient $paymentRecipient */
        $paymentRecipient = PaymentRecipient::where('check_id', $checkId)->first();

        if(empty($paymentRecipient) || $statusManager->isPaid($paymentRecipient->serviceOrder) !== true){
            throw new CheckNotFoundException();
        }

        $serviceOrder = $paymentRecipient->serviceOrder;

        if (!$paymentRecipient) {
            return false;
        }

        return [
            "payments" => [
                [
                    "sender" => $serviceOrder->full_name,
                    "recipient" => $paymentRecipient->recipient_name,
                    "amount" => intval($paymentRecipient->amount*100),
                    "date" => $serviceOrder->liqpay_payment_date->format('c'),
                    "description" => $paymentRecipient->purpose,
                    "currencyCode" => 980,
                    "commissionRate" => intval($serviceOrder->service_fee*100)
                ]
            ],
            "link" => evo()->getConfig('site_url').$serviceOrder->invoice_file_pdf,
        ];
    }
    private function  base64url_encode($data) {
        return str_replace(['-', '_'],['+', '/']    , base64_encode($data));
    }

}
