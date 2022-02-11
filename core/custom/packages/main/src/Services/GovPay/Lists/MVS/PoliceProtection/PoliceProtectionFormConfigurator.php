<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\PoliceProtection;


use EvolutionCMS\Main\Services\GovPay\Fields\AddressField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\DateField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SumField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TotalCaptionField;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Fields\PoliceSecurityAccountField;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;

class PoliceProtectionFormConfigurator extends BaseFormConfigurator
{
    public function getFormConfig(): array
    {

        $formConfig = $this->serviceConfig['formConfig'];
        return [
            new LayoutFields([
                FullNameField::buildField(
                    $formConfig['full_name_title']??'',
                    $formConfig['full_name_placeholder']??''
                )
            ]),
            new LayoutFields([
                AddressField::buildField(
                    $formConfig['address_title']??'',
                    $formConfig['address_placeholder']??''
                ),
            ]),
            new LayoutFields([
                new DateField(
                    'period_from',
                    $formConfig['period_from_title']??'',
                    $formConfig['period_from_placeholder']??''
                ),
                new DateField(
                    'period_to',
                    $formConfig['period_to_title']??'',
                    $formConfig['period_to_placeholder']??''
                ),
            ]),

            new LayoutFields([
                PoliceSecurityAccountField::buildField(
                    $formConfig['police_security_account_title']??'',
                    $formConfig['police_security_account_placeholder']??''
                ),
                SumField::build(
                    $formConfig['sum_title']??'',
                    $formConfig['sum_placeholder']??''
                ),
            ]),
            TotalCaptionField::build()
        ];
    }
    public function renderDataForPreview($fieldValues): array
    {
        $data = $this->getFormFieldsValues($fieldValues);

        return [
            'Прізвище, ім`я та по-батькові' => $data['full_name'],
            'Номер особового рахунку' => $data['police_security_account'],
            'Адреса' => $data['address'],
            'Період сплати з' => $data['period_from'],
            'Період сплати по' => $data['period_to'],
        ];
    }
}