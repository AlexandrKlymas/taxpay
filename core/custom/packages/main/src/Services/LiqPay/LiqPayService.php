<?php

namespace EvolutionCMS\Main\Services\LiqPay;

use EvolutionCMS\Main\Services\GovPay\Dto\MerchantKeysDto;
use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use Illuminate\Http\Request;
use LiqPay;
use PHPMailer\PHPMailer\Exception;

class LiqPayService
{
    /**
     * @var LiqPay
     */
    private LiqPay $liqPay;

    private bool $debugMode = true;

    private MerchantKeysDto $merchantKeysDto;

    protected function initLiqPaySDK(int $serviceId)
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);

        $this->merchantKeysDto = $serviceFactory->getMerchantManager()->getKeys();

        $this->liqPay = new LiqPay($this->merchantKeysDto->getPublicKey(), $this->merchantKeysDto->getPrivateKey());
    }

    public function getPaymentDataAndSignature(ServiceOrder $serviceOrder): array
    {
        $this->initLiqPaySDK($serviceOrder->service_id);

        $formParams = $this->getFormParams($serviceOrder);

        $data = base64_encode(json_encode($formParams));

        $signature = $this->liqPay->cnb_signature($formParams);

        return [
            'data' => $data,
            'signature' => $signature,
        ];
    }

    public function payOrder(ServiceOrder $serviceOrder): string
    {
        $this->initLiqPaySDK($serviceOrder->service_id);

        $formFields = $this->getFormParams($serviceOrder);

        $form = $this->liqPay->cnb_form($formFields);

        return $form;

    }

    private function getFormParams(ServiceOrder $serviceOrder): array
    {
        $this->initLiqPaySDK($serviceOrder->service_id);

        $serviceFactory = ServiceFactory::makeFactoryForService($serviceOrder->service_id);

        return $serviceFactory->getFormConfigurator()->getPaymentFormParams($this->merchantKeysDto,$serviceOrder);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function handle(Request $request)
    {
        $request = $request->toArray();

        $log = $request;

        $log['decode_data'] = isset($request['data']) ? json_decode(base64_decode($request['data']), true):'empty-data';

        if($this->debugMode){
            evo()->logEvent('412',1,json_encode($log),'LiqPay handle');
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
            throw new \Exception('Amount did not match'.$serviceOrder->total.'!='.$data['amount']);
        }

        $this->initLiqPaySDK($serviceOrder->service_id);

        $sign = base64_encode(sha1($this->merchantKeysDto->getPrivateKey()
            . $request['data'] . $this->merchantKeysDto->getPrivateKey(), 1));

        if ($sign !== $request['signature']) {
            throw new \Exception('bad signature');
        }

        $serviceManager = new ServiceManager();
        $serviceManager->updateLiqPayPaymentStatus($serviceOrder->id, $data);

        return $data['status'];
    }
}