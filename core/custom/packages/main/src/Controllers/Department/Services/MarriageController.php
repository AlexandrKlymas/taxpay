<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Models\SubServices;

class MarriageController extends BaseController
{
    public function render()
    {

        if(empty($_GET['registry_office'])){
            evo()->sendRedirect(UrlProcessor::makeUrl(13));
        }
        $registryOffice = SubServices::find($_GET['registry_office']);
        if(empty($registryOffice)){
            evo()->sendRedirect(UrlProcessor::makeUrl(13));
        }

        $_SESSION['fields_data'] = [
            'registry_office'=>$registryOffice->id,
        ];

        $serviceId = $this->evo->documentIdentifier;

        try {
            $serviceProcessor = new ServiceManager();
            $this->data['serviceId'] = $this->evo->documentIdentifier;
            $this->data['form'] = $serviceProcessor->renderForm($serviceId);
            $this->data['registry_office_name'] = $registryOffice->name;

        } catch (ServiceNotFoundException $e) {
            $this->data['error'] = 'Послуга в розробці';
        } catch (\Exception $e) {
            throw $e;
        }
    }
}