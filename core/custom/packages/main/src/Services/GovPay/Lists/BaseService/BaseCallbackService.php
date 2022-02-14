<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Controllers\Department\ServiceOrderSuccessController;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICallbackService;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\Exception;

class BaseCallbackService implements ICallbackService
{
    protected array $errors = [];
    protected string $errorsTitle = ' CallBackService';
    protected int $serviceId;
    protected string $serviceName = '';
    protected IServiceFactory $serviceFactory;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->serviceId = $this->serviceFactory->getServiceId();
        $this->errorsTitle = strtoupper($this->serviceName) . $this->errorsTitle;

    }

    public function liqPayCallback(array $params)
    {
        //
    }

    public function checkFoundCallback(array $params)
    {
        //
    }

    /**
     * @throws Exception
     */
    public function invoicePDFGenerated(array $params)
    {
        if($this->isValidServiceOrder($params['service_order'])){

            $serviceOrder = $params['service_order'];

            $this->sendInvoiceToEmail($serviceOrder);
        }
        if(!empty($this->errors)){
            $this->logErrors($this->errorsTitle,$params);
        }
    }

    protected function sendInvoiceToEmail(ServiceOrder $serviceOrder)
    {
        if(!empty($serviceOrder->email)){
            ServiceOrderSuccessController::sendInvoiceToEmail(new Request([
                'email'=>$serviceOrder->email,
                'order_hash'=>$serviceOrder->order_hash,
            ]));
        }
    }

    protected function setError($key, $error, $data = [])
    {
        if (!empty($data)) {
            $this->errors[$key][] = [
                'error' => $error,
                'data' => $data,
            ];
        } else {
            $this->errors[$key][] = $error;
        }
    }

    protected function isValidLiqPayErrorCallbackRequest(array $params): bool
    {
        if (!empty($params['status']) && $params['status'] == 'success') {
            return false;
        }
        if (empty($params['request'])) {
            $this->setError('request', 'empty request');

            return false;
        }
        if (empty($params['request']['order_id'])) {
            $this->setError('request', 'empty payment_hash');

            return false;
        }
        return true;
    }

    protected function isValidServiceOrder($serviceOrder = null): bool
    {
        if (empty($serviceOrder)) {
            $this->setError('service_order', 'service order not found');

            return false;
        }
        if (empty($serviceOrder->service_id) || $serviceOrder->service_id != $this->serviceId) {

            return false;
        }

        return true;
    }

    /**
     * @throws Exception
     */
    protected function logErrors(string $title = '', array $data = [])
    {
        if (empty($title)) {
            $title = $this->errorsTitle;
        }
        evo()->logEvent(1, 3, json_encode([
            'errors' => $this->errors,
            'data' => $data,
        ]), $title);
    }
}