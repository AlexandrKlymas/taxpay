<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax;


use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BasePreviewGenerator;
use Illuminate\Support\Facades\View;

class ECabinetTaxPreviewGenerator extends BasePreviewGenerator
{
    public function getPreview(array $data):string
    {
        $data['service_id'] = $this->serviceFactory->getServiceId();
        return View::make('partials.services.dps_preview')->with($data)->render();
    }
}