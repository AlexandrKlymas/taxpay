<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BasePreviewGenerator;
use Illuminate\Support\Facades\View;

class MarriagePreviewGenerator extends BasePreviewGenerator
{
    public function getPreview(array $data):string
    {
        $data['service_id'] = $this->serviceFactory->getServiceId();
        return View::make('partials.services.marriage_preview')->with($data)->render();
    }
}