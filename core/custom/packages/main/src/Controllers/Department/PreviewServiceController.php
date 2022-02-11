<?php

namespace EvolutionCMS\Main\Controllers\Department;


use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PreviewServiceController extends ServiceController
{
    public function render()
    {
        $serviceId = $this->evo->documentIdentifier;
        $requestArr = $_GET;
        unset($requestArr['q']);
        $request = new Request($_GET);

        $this->data['serviceId'] = $serviceId;

        $serviceProcessor = new ServiceManager();

        try{
            $serviceProcessor->validate($serviceId, $requestArr);
            try {
                $this->data['preview'] = $this->preview($request)['preview'];
                $this->data['commission'] = $serviceProcessor->getCommission($serviceId);
            } catch (ServiceNotFoundException $e) {
                $this->data['error'] = 'Послуга в розробці';
            }
        }catch (Exception $validationException){
            $this->data['error'] = 'Запит має помилкові данні';
        }
    }
}