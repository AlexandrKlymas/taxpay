<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;


use EvolutionCMS\Main\Models\MedicalCenter;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\DisabledTextField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SumField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TotalCaptionField;
use EvolutionCMS\Main\Services\GovPay\Fields\FineSeries;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\HiddenStaticSumField;
use EvolutionCMS\Main\Services\GovPay\Fields\HiddenTextField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Fields\MedicalCenterField;
use EvolutionCMS\Main\Services\GovPay\Fields\NameField;
use EvolutionCMS\Main\Services\GovPay\Fields\PatronymicField;
use EvolutionCMS\Main\Services\GovPay\Fields\SeparatorField;
use EvolutionCMS\Main\Services\GovPay\Fields\StaticSumField;
use EvolutionCMS\Main\Services\GovPay\Fields\SurnameField;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;


class PCRFormConfigurator extends BaseFormConfigurator implements IFormConfigurator
{

    public function getFormConfig(): array
    {
        return [
            new LayoutFields([
                FullNameField::buildField(),
            ]),

            new LayoutFields([
                HiddenTextField::buildField('medical_center',$_SESSION['fields_data']['medical_center']??''),
            ]),

            new LayoutFields([
                HiddenStaticSumField::buildField($_SESSION['fields_data']['sum']??''),
                TotalCaptionField::build()
            ])

        ];
    }

    public function renderDataForPreview($fieldValues): array
    {

        $form =  [
            'Прізвище, ім`я та по-батькові'=> $fieldValues['full_name'],
        ];

        if(!empty($fieldValues['medical_center'])){
            $med = MedicalCenter::where('id',$fieldValues['medical_center'])->first();
            if(!empty($med)){
                $form['Медичний заклад']  = $med->name;
            }
        }

        return $form;
    }
}