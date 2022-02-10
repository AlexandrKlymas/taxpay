<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;

use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PCRPreviewGenerator
{
    public function preview(Request $request)
    {
        $serviceId = $request->get('service_id');
        $formData = $request->toArray();

        $serviceProcessor = new ServiceManager();
        $data = array_merge($serviceProcessor->getDataForPreview($serviceId, $formData));

        $preview = View::make('partials.services.pcr_preview')->with($data)->render();

        return [
            'status' => true,
            'preview' => $preview,
        ];
    }
}