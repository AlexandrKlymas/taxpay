<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseCallbackService;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusError;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusFailure;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusReady;
use PHPMailer\PHPMailer\Exception;

class SudyTaxCallbackService extends BaseCallbackService
{
    private CourtGovUaAPI $api;

    public function __construct(IServiceFactory $serviceFactory)
    {
        parent::__construct($serviceFactory);
        $this->api = new CourtGovUaAPI();
    }

    /**
     * @throws Exception
     */
    public function liqPayCallback(array $params)
    {
        if($this->isValidLiqPayErrorCallbackRequest($params)){
            $serviceOrder = ServiceOrder::where('payment_hash',$params['request']['order_id'])->first();
            if($this->isValidServiceOrder($serviceOrder)){
                if(!$serviceOrder->hasInServiceData('court_callback')){
                    $this->sendErrorNotify($serviceOrder);
                }
            }
        }
        if(!empty($this->errors)){
            $this->logErrors('liqPayErrorCallback',$params);
        }
    }
    public function invoicePDFGenerated(array $params)
    {
        if($this->isValidServiceOrder($params['service_order'])){
            $serviceOrder = $params['service_order'];

            $this->sendInvoiceToEmail($serviceOrder);
            $this->sendCourtSuccessCallBack($serviceOrder);
        }
        if(!empty($this->errors)){
            $this->logErrors('invoicePDFGenerated Callback',$params);
        }
    }

    public function sendErrorNotify($serviceOrder)
    {
        $notify = [
            'order'=>$serviceOrder->form_data['order'],
            'status'=>0,
            'id_check'=>null,
            'govpay_id'=>$serviceOrder->id,
            'pay_time'=>$serviceOrder->liqpay_payment_date->format('Y-m-d H:i:s'),
            'backref'=>$serviceOrder->form_data['backref']??'',
        ];

        $notify['p_sign'] = SudyTaxSignatureHelper::makeSign($notify);

        $serviceOrder->historyUpdate('Відправлено помилку оплати до court.gov.ua API');

        $result = $this->api->notify($notify);

        $serviceOrder->updateServiceData('court_callback',true);

        if($result['status']==1){
            $serviceOrder->historyUpdate('court.gov.ua API прийняв помилку оплати');
        }else{
            $serviceOrder->historyUpdate('court.gov.ua API НЕ прийняв помилку оплати');
        }

        $serviceOrder->save();
    }


    public function sendSuccessNotify($serviceOrder)
    {
        $notify = [
            'order'=>$serviceOrder->form_data['order'],
            'status'=>1,
            'id_check'=>$serviceOrder->mainRecipients->first()->check_id,
            'govpay_id'=>$serviceOrder->id,
            'pay_time'=>$serviceOrder->liqpay_payment_date->format('Y-m-d H:i:s'),
            'backref'=>$serviceOrder->form_data['backref']??'',
        ];

        $notify['p_sign'] = SudyTaxSignatureHelper::makeSign($notify);

        $serviceOrder->historyUpdate('Відправлено успішну оплату до court.gov.ua API');

        $result = $this->api->notify($notify);

        $serviceOrder->updateServiceData('court_callback',true);

        if(empty($result['status']) || !empty($result['error'])){
            $serviceOrder->historyUpdate('Помилка додання оплати до court.gov.ua API');
            $this->setError('response','API error',$result);
        }else{
            $serviceOrder->historyUpdate('Оплату успішно додано до court.gov.ua API');
        }

        $serviceOrder->save();
    }

    public function sendCourtSuccessCallBack(ServiceOrder $serviceOrder)
    {
        if(!$serviceOrder->hasInServiceData('court_callback')){
            $this->sendSuccessNotify($serviceOrder);
        }
    }

    public function sendCourtErrorCallBack(ServiceOrder $serviceOrder)
    {
        if(!$serviceOrder->hasInServiceData('court_callback')){
            $this->sendErrorNotify($serviceOrder);
        }
    }

    public function checkUnsentCallbacks(): array
    {
        $service_id = $this->serviceId;
        $dateFrom = date('Y-m-d H:i:s',strtotime('-1 day'));
        $dateTo = date('Y-m-d H:i:s',time());
        $statuses = [
            'errors'=>[StatusError::getKey(),StatusFailure::getKey()],
            'success'=>[StatusReady::getKey()],
        ];

        $serviceOrders = ServiceOrder::query()
            ->where(function($query) use ($dateFrom,$dateTo,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->whereNull('service_data');
                $query->where('status',StatusError::getKey());
                $query->where('created_at','>',$dateFrom);
                $query->where('created_at','<',$dateTo);
            })
            ->orWhere(function($query) use ($dateFrom,$dateTo,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->whereNull('service_data');
                $query->where('status',StatusFailure::getKey());
                $query->where('created_at','>',$dateFrom);
                $query->where('created_at','<',$dateTo);
            })
            ->orWhere(function($query) use ($dateFrom,$dateTo,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->whereNull('service_data');
                $query->where('status',StatusReady::getKey());
                $query->where('created_at','>',$dateFrom);
                $query->where('created_at','<',$dateTo);
            })
            ->limit(1000)
            ->get()
        ;

        if(empty($serviceOrders)){
            return ['no orders'];
        }

        $count = $serviceOrders->count();
        $success  = 0;
        $errors = 0;

        foreach($serviceOrders as $serviceOrder){
            if(!$serviceOrder->hasInServiceData('court_callback')){
                if(in_array($serviceOrder->status,$statuses['success'])){
                    $success++;
                    $this->sendSuccessNotify($serviceOrder);
                    sleep(1);
                }
                if(in_array($serviceOrder->status, $statuses['errors'])){
                    $errors++;
                    $this->sendErrorNotify($serviceOrder);
                    sleep(1);
                }
            }
        }

        return ['success'=>$success,'errors'=>$errors,'count'=>$count,'form'=>$dateFrom,'to'=>$dateTo];
    }
}