<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use PHPMailer\PHPMailer\Exception;

class SudyTaxController
{
    private CourtGovUaAPI $api;
    private array $errors = [];
    private int $serviceId = 162;

    public function setApi(CourtGovUaAPI $api)
    {
        $this->api = $api;
    }

    private function setError($key,$error,$data=[])
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

    private function isValidLiqPayErrorCallbackRequest(array $params): bool
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

    private function isValidServiceOrder($serviceOrder = null): bool
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

    /**
     * @throws Exception
     */
    public function liqPayCallback(array $params)
    {
        if($this->isValidLiqPayErrorCallbackRequest($params)){
            $serviceOrder = ServiceOrder::where('payment_hash',$params['request']['order_id'])->first();
            if($this->isValidServiceOrder($serviceOrder)){

                $this->setApi(new CourtGovUaAPI());

                $this->sendErrorNotify($serviceOrder);
                if(!empty($this->errors)){
                    $this->logErrors('SudyTax Errors liqPayErrorCallback',$params);
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    private function sendErrorNotify($serviceOrder)
    {
        $notify = [
            'order'=>$serviceOrder->form_data['order'],
            'status'=>0,
            'id_check'=>null,
            'govpay_id'=>$serviceOrder->id,
            'pay_time'=>$serviceOrder->liqpay_payment_date->format('Y-m-d H:i:s'),
        ];

        $serviceOrder->historyUpdate('Відправлено помилку оплати до court.gov.ua API');

        $result = $this->api->notify($notify);

        if($result['status']==1){
            $serviceOrder->historyUpdate('court.gov.ua API прийняв помилку оплати');
        }else{
            $serviceOrder->historyUpdate('court.gov.ua API НЕ прийняв помилку оплати');
        }
    }

    /**
     * @throws Exception
     */
    private function sendSuccessNotify($serviceOrder)
    {
        $notify = [
            'order'=>$serviceOrder->form_data['order'],
            'status'=>1,
            'id_check'=>$serviceOrder->mainRecipients->first()->check_id,
            'govpay_id'=>$serviceOrder->id,
            'pay_time'=>$serviceOrder->liqpay_payment_date->format('Y-m-d H:i:s'),
        ];

        $serviceOrder->historyUpdate('Відправлено успішну оплату до court.gov.ua API');

        $result = $this->api->notify($notify);


        if(empty($result['status']) || !empty($result['error'])){
            $serviceOrder->historyUpdate('Помилка додання оплати до court.gov.ua API');
            $this->setError('response','api error',$result);
        }else{
            $serviceOrder->historyUpdate('Оплату успішно додано до court.gov.ua API');
        }
    }

    /**
     * @throws Exception
     */
    public function checkFoundCallback($params)
    {
        if($this->isValidServiceOrder($params['serviceOrder'])){
            $serviceOrder = $params['serviceOrder'];
            $this->setApi(new CourtGovUaAPI());

            $this->sendSuccessNotify($serviceOrder);
            if(!empty($this->errors)){
                $this->logErrors('SudyTax Errors checkFoundCallback',$params);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function logErrors(string $title = 'SudyTax Errors', array $data=[])
    {
        evo()->logEvent(1,1,json_encode([
            'errors'=>$this->errors,
            'data'=>$data,
        ]),$title);
    }
}