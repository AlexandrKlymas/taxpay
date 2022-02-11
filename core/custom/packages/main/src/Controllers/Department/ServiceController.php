<?php

namespace EvolutionCMS\Main\Controllers\Department;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\LiqPay\LiqPayService;
use Exception;
use Illuminate\Http\Request;


class ServiceController extends BaseController
{
    public function render()
    {
        $serviceId = $this->evo->documentIdentifier;

        try {
            $serviceProcessor = new ServiceManager();
            $this->data['serviceId'] = $this->evo->documentIdentifier;
            $this->data['form'] = $serviceProcessor->renderForm($serviceId);
            $this->data['commission'] = $serviceProcessor->getCommission($serviceId);
        } catch (ServiceNotFoundException $e) {
            $this->data['error'] = 'Послуга в розробці';
        }
    }

    public function validate(Request $request): array
    {
        $serviceId = $request->get('service_id');

        $serviceProcessor = new ServiceManager();

        $serviceProcessor->validate($serviceId, $request->toArray());

        return [
            'status' => true,
        ];
    }

    public function preview(Request $request): array
    {
        $serviceId= $request->get('service_id');
        $formData = $request->toArray();

        return [
            'status' => true,
            'preview' => (new ServiceManager())->renderPreview($serviceId,$formData),
        ];
    }

    public function createServiceOrderAndPay(Request $request): array
    {
        $serviceId = $request->get('service_id');
        $formData = $request->toArray();

        $serviceProcessor = new ServiceManager();

        $serviceOrder = $serviceProcessor->createOder($serviceId, $formData);


        switch ($request->get('payment_method')) {
            case 'apay':
                try{
                    $liqPayService = new LiqPayService();
                    $paymentForm = $liqPayService->payOrder($serviceOrder);
                } catch (Exception $e) {
                    evo()->logEvent('1',2,'LiqPay Request: <pre>'.print_r($_GET,true).'</pre>',
                        'ServiceController cannot create LiqPayController');
                }

                $response = [
                    'status' => true,
                    'paymentForm' => $paymentForm??[],
                    'redirectType' => 'form'
                ];
                break;

            default:

                $response = [
                    'status' => true,
                    'redirectType' => 'link',
                    'redirectLink' => UrlProcessor::makeUrl(146) . '?' . http_build_query(['order_id' => $serviceOrder->id])
                ];
                break;
        }

        return $response;
    }

    public function finished(){
        if(!evo()->getLoginUserID()){
            return;
        }

        $serviceProcessor = new ServiceManager();
        $serviceProcessor->executePaidServiceOrders();
        $serviceProcessor->completedServiceOrders();
    }
}