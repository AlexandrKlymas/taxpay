<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Assistance\HelpUA;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BasePreviewGenerator;
use Illuminate\Support\Facades\View;

class HelperUAPreviewGenerator extends BasePreviewGenerator
{
    public function getPreview(array $data):string
    {
        $data['service_id'] = $this->serviceFactory->getServiceId();

        return View::make('partials.services.helpua_preview')->with($data)->render();
    }
}