<?php

namespace EvolutionCMS\Main\Services\LiqPay;


use Carbon\Carbon;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

class LiqPayService
{

    private $sandBoxMode;
    /**
     * @var \LiqPay
     */
    private $liqay;
    /**
     * @var \DocumentParser
     */
    private $evo;
    private $publicKey;

    private $privateKey;

    public function __construct($publicKey, $privateKey, $sandBoxMode = 0)
    {
        $this->evo = \EvolutionCMS();

        if (empty($publicKey) || empty($privateKey)) {
            throw new InvalidArgumentException('publicKey or private key is empty');
        }

        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;


        $this->sandBoxMode = $sandBoxMode;

        $this->liqay = new \LiqPay($this->publicKey, $this->privateKey);

    }

    public function getPaymentDataAndSignature(ServiceOrder $serviceOrder)
    {
        $formParams = $this->getFormParams($serviceOrder);

        $data = base64_encode(json_encode($formParams));
        $signature = $this->liqay->cnb_signature($formParams);

        return [
            'data' => $data,
            'signature' => $signature,
        ];
    }

    public function payOrder(ServiceOrder $serviceOrder)
    {
        $formFields = $this->getFormParams($serviceOrder);

        $form = $this->liqay->cnb_form($formFields);
        return $form;

    }

    private function getFormParams(ServiceOrder $serviceOrder)
    {



        /** @var PaymentRecipient $mainRecipient */
        $mainRecipient = $serviceOrder->mainRecipients->first();


        return [
            'public_key' => $this->publicKey,
            'version' => 3,

            'action' => 'pay',

            'amount' => $serviceOrder->total,
            'currency' => 'UAH',

            'description' => $mainRecipient->purpose,
            'order_id' => $serviceOrder->payment_hash,

            'language' => 'uk',
            'paytypes' => 'apay,gpay,card,liqpay,privat24,masterpass,qr',

            'result_url' => $this->evo->getConfig('site_url') . 'liqpay-result',
            'server_url' => $this->evo->getConfig('site_url') . 'liqpay-server-request',

        ];
    }

    public function handle(Request $request)
    {
        $request = $request->toArray();

        $log = $request;

        $log['decode_data'] = isset($request['data']) ? json_decode(base64_decode($request['data']), true):'empty-data';


        evo()->logEvent('412',1,json_encode($log),'LiqPay handle');


        $sign = base64_encode(sha1($this->privateKey . $request['data'] . $this->privateKey, 1));

        if ($sign !== $request['signature']) {
            throw new \Exception('bad signature');
        }

        $data = json_decode(base64_decode($request['data']), true);


        if (!($data['action'] === 'pay')) {
            throw new \Exception('Payment is not success');
        }

        $serviceOrder = ServiceOrder::findByPaymentHash($data['order_id']);

        if(empty($serviceOrder)){
            throw new \Exception('serviceOrder not found by PayHash');
        }

        if ($serviceOrder->total !== $data['amount']) {
            throw new \Exception('Amount did not match');
        }

        $serviceManager = new ServiceManager();
        $serviceManager->updateLiqPayPaymentStatus($serviceOrder->id, $data);

        return $data['status'];
    }
}