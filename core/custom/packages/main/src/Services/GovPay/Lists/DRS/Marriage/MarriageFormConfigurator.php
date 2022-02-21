<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TotalCaptionField;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\HiddenStaticSumField;
use EvolutionCMS\Main\Services\GovPay\Fields\HiddenTextField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Models\SubServices;

class MarriageFormConfigurator extends BaseFormConfigurator
{
    protected int $serviceId = 176;

    public function getFormConfig(): array
    {
        $registryOfficeId = $_SESSION['fields_data']['registry_office']??0;
        unset($_SESSION['fields_data']);

        $paymentAmountDto = ServiceFactory::makeFactoryForService($this->serviceId)
            ->getFinalCalculator()
            ->calculate(['registry_office'=>$registryOfficeId]);

        return [
            new LayoutFields([
                FullNameField::buildField(),
            ]),

            new LayoutFields([
                HiddenTextField::buildField('registry_office',$registryOfficeId),
            ]),

            new LayoutFields([
                HiddenStaticSumField::buildField($paymentAmountDto->getSum()),
                TotalCaptionField::build('До сплати',$paymentAmountDto->getTotal())
            ])

        ];
    }

    public function renderDataForPreview($fieldValues): array
    {

        $form =  [
            'Прізвище, ім`я та по-батькові'=> $fieldValues['full_name'],
        ];

        if(!empty($fieldValues['registry_office'])){
            $registryOffice = SubServices::where('id',$fieldValues['registry_office'])->first();
            if(!empty($registryOffice)){
                $form['ДРС']  = $registryOffice->name;
            }
        }

        return $form;
    }
}