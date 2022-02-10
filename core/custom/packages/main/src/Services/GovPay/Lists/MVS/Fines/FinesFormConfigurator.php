<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;

class FinesFormConfigurator extends BaseFormConfigurator implements IFormConfigurator
{


    public function getFormConfig(): array
    {
        return [];
    }

    public function getFormFieldsValues($formData): array
    {
        $fine = Fine::findOrFail($formData['fine_id']);

        $paidInfo = json_decode($fine->data['paidinfo'],true);
        $purpose = $paidInfo['purpose'];

        $fullName = '';

        if(preg_match('~\*([^*]*)$~ui',$purpose,$matches)){
            $fullName = $matches[1];
        }

        return [
            'fine_id' => $formData['fine_id'],
            'fine' => $fine->data,
            'full_name'=>$fullName,
        ];
    }


    public function renderDataForPreview($fieldValues): array
    {
        $fine = Fine::findOrFail($fieldValues['fine_id']);
        return [

            'Стаття' => $fine->data['kupap'],
            'Машина' => $fine->data['brand'],

        ];

    }

}