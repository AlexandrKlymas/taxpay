<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICallbackService;


class BaseCallbackService implements ICallbackService
{
    protected array $errors = [];
    protected string $errorsTitle = ' CallBackService';
    protected int $serviceId=0;

    public function __construct(int $serviceId, string $serviceName = '')
    {
        $this->serviceId = $serviceId;
        $this->errorsTitle = strtoupper($serviceName) . $this->errorsTitle;

    }

    public function liqPayCallback(array $params)
    {
        //
    }

    public function checkFoundCallback(array $params)
    {
        //
    }

    public function invoicePDFGenerated(array $params)
    {
        //
    }

    protected function setError($key,$error,$data=[])
    {
        if(!empty($data)){
            $this->errors[$key][] = [
                'error'=>$error,
                'data'=>$data,
            ];
        }else{
            $this->errors[$key][] = $error;
        }
    }

    protected function isValidLiqPayErrorCallbackRequest(array $params): bool
    {
        if(!empty($params['status']) && $params['status']=='success'){
            return false;
        }
        if(empty($params['request'])){
            $this->setError('request','empty request');

            return false;
        }
        if(empty($params['request']['order_id'])){
            $this->setError('request','empty payment_hash');

            return false;
        }
        return true;
    }

    protected function isValidServiceOrder($serviceOrder = null): bool
    {
        if(empty($serviceOrder)){
            $this->setError('service_order','service order not found');

            return false;
        }
        if(empty($serviceOrder->service_id) || $serviceOrder->service_id != $this->serviceId){

            return false;
        }

        return true;
    }

    protected function logErrors(string $title = '', array $data=[])
    {
        if(empty($title)){
            $title = $this->errorsTitle;
        }
        evo()->logEvent(1,3,json_encode([
            'errors'=>$this->errors,
            'data'=>$data,
        ]),$title);
    }
}