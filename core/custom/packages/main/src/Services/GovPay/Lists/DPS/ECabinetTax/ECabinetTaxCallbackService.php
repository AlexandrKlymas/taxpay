<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax;

use EvolutionCMS\Main\Controllers\Department\ServiceOrderSuccessController;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseCallbackService;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use Illuminate\Http\Request;

class ECabinetTaxCallbackService extends BaseCallbackService
{
    protected int $serviceId=165;

    public function invoicePDFGenerated(array $params)
    {
        if($this->isValidServiceOrder($params['service_order'])){
            $serviceOrder = $params['service_order'];

            $this->sendInvoiceToEmail($serviceOrder);

            if(!empty($this->errors)){
                $this->logErrors('TAXInvoicePDFGeneratedCallBack',$params);
            }
        }
    }

    private function sendInvoiceToEmail(ServiceOrder $serviceOrder)
    {
        if(!empty($serviceOrder->email)){
            ServiceOrderSuccessController::sendInvoiceToEmail(new Request([
                'email'=>$serviceOrder->email,
                'order_hash'=>$serviceOrder->order_hash,
            ]));
        }
    }
}