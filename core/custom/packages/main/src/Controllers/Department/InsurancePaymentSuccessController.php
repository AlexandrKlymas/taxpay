<?php

namespace EvolutionCMS\Main\Controllers\Department;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\CarInsurance\CarInsuranceService;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\LiqPay\LiqPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class InsurancePaymentSuccessController extends BaseController
{
    public function render()
    {
        $service = new CarInsuranceService();
        $lastContract = $service->getLastContract();

        if(!empty($lastContract)){
            $this->data['invoice_pdf'] = $service->printContract($lastContract);
        }else{
            $this->data['invoice_pdf'] = '';
        }
    }
}