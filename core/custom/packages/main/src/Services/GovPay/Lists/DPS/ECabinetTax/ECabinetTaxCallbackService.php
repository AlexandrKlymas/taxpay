<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseCallbackService;

class ECabinetTaxCallbackService extends BaseCallbackService
{
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
}