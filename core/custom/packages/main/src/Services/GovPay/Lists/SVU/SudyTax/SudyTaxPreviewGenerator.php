<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;


use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BasePreviewGenerator;
use Illuminate\Support\Facades\View;

class SudyTaxPreviewGenerator extends BasePreviewGenerator
{
    public function getPreview(array $data):string
    {
        $data['service_id'] = $this->serviceFactory->getServiceId();
        return View::make('partials.services.sudytax_preview')->with($data)->render();
    }
}