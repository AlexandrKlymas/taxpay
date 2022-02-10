<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Controllers\LiqPayController;
use EvolutionCMS\Main\Models\MedicalCenter;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotPlr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class PcrController extends BaseController
{
    public function render()
    {

        if(empty($_GET['medical_center'])){
            evo()->sendRedirect(UrlProcessor::makeUrl(13));
        }
        $medicalCenter = MedicalCenter::find($_GET['medical_center']);
        if(empty($medicalCenter)){
            evo()->sendRedirect(UrlProcessor::makeUrl(13));
        }

        $_SESSION['fields_data'] = [
            'medical_center'=>$medicalCenter->id,
            'sum'=>$this->evo->documentObject['fix_sum'][1]
        ];

        $serviceId = $this->evo->documentIdentifier;

        try {
            $serviceProcessor = new ServiceManager();
            $this->data['serviceId'] = $this->evo->documentIdentifier;
            $this->data['form'] = $serviceProcessor->renderForm($serviceId);
            $this->data['medical_center_name'] = $medicalCenter->name;
            $this->data['commission'] = $serviceProcessor->getCommission($serviceId);

        } catch (ServiceNotFoundException $e) {
            $this->data['error'] = 'Послуга в розробці';
        } catch (\Exception $e) {
            throw $e;
        }
    }
}