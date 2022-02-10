<?php

namespace EvolutionCMS\Main\Controllers\Department;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Controllers\LiqPayController;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\LiqPay\LiqPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

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
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function validate(Request $request)
    {
        $serviceId = $request->get('service_id');

        $serviceProcessor = new ServiceManager();

        try {
            $serviceProcessor->validate($serviceId, $request->toArray());
        } catch (ValidationException $exception) {
            return [
                'status' => false,
                'errors' => $exception->errors()
            ];
        }

        return [
            'status' => true,
        ];
    }

    public function preview(Request $request)
    {
        $serviceId= $request->get('service_id');

        $servicesAlias = new ServicesAlias();

        try{
            $serviceConfig = $servicesAlias->getService($serviceId);
            if(empty($serviceConfig['preview'])){
                throw new ServiceNotFoundException('empty previewGenerator');
            }
        }catch (ServiceNotFoundException $e){

            $formData = $request->toArray();

            $serviceProcessor = new ServiceManager();
            $data = array_merge($serviceProcessor->getDataForPreview($serviceId, $formData));
            $preview = View::make('partials.services.preview')->with($data)->render();

            return [
                'status' => true,
                'preview' => $preview,
            ];
        }

//        $formData = $request->toArray();
//
//        $serviceProcessor = new ServiceManager();
//        $data = array_merge($serviceProcessor->getDataForPreview($serviceId, $formData));
//        $preview = View::make('partials.services.preview')->with($data)->render();
//
//        return [
//            'status' => true,
//            'preview' => $preview,
//        ];

        $previewGenerator = new $serviceConfig['preview'];

        return $previewGenerator->preview($request);
    }

    public function createServiceOrderAndPay(Request $request)
    {

        $serviceId = $request->get('service_id');
        $formData = $request->toArray();

        $serviceProcessor = new ServiceManager();

        $serviceOrder = $serviceProcessor->createOder($serviceId, $formData);


        switch ($request->get('payment_method')) {
            case 'apay':
                try{
                    $liqPayController = new LiqPayController($serviceOrder->service_id);
                    $liqPayService = $liqPayController->getLiqPayService();
                    $paymentForm = $liqPayService->payOrder($serviceOrder);
                } catch (\Exception $e) {
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