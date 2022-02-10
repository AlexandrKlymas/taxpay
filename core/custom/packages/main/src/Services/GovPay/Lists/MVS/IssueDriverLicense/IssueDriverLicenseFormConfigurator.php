<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\IssueDriverLicense;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Fields\AddressField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TotalCaptionField;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Fields\RegionalServiceCenterField;
use EvolutionCMS\Main\Services\GovPay\Fields\SeparatorField;
use EvolutionCMS\Main\Services\GovPay\Fields\ServiceField;
use EvolutionCMS\Main\Services\GovPay\Fields\TerritorialServiceCenterField;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;

class IssueDriverLicenseFormConfigurator extends BaseFormConfigurator implements IFormConfigurator
{

    public function getFormConfig(): array
    {
        $formConfig = $this->serviceConfig['formConfig'];
        $regions = $this->serviceConfig['regions'];
        $services = $this->serviceConfig['services'];




        return [
            new LayoutFields([
                FullNameField::buildField(
                    $formConfig['full_name_title']??'',
                    $formConfig['full_name_placeholder']??''
                ),
            ]),
            new LayoutFields([
                AddressField::buildField(
                    $formConfig['address_title']??'',
                    $formConfig['address_placeholder']??''
                ),
                new TextField(
                    'tax_number',
                    $formConfig['tax_number_title']??'',
                    $formConfig['tax_number_placeholder']??'',
                    true,
                    [
                        'regex:~^(\d{10})$~iu'
                    ]
                )
            ]),
            new SeparatorField('Територіальні сервісні центри та послуги'),
            new LayoutFields([
                RegionalServiceCenterField::buildField(
                    $formConfig['regional_service_center_title']??'',
                    $formConfig['regional_service_center_placeholder']??'',
                    $regions
                ),
                TerritorialServiceCenterField::build(
                    $formConfig['territorial_service_center_title']??'',
                    $formConfig['territorial_service_center_placeholder']??''
                ),
            ]),
            new LayoutFields([
                ServiceField::buildField(
                    $formConfig['service_title']??'',
                    $formConfig['service_placeholder']??'',
                    $services
                ),
            ]),

            TotalCaptionField::build()
        ];
    }

    public function renderDataForPreview($fieldValues): array
    {

        return [
            'Прізвище, ім`я та по-батькові' => $fieldValues['full_name'],
            'Адреса' => $fieldValues['address'],
            'Ідентифікаційний номер' => $fieldValues['tax_number'],
            'РСЦ та послуги' => '',
            'Регіон' => $fieldValues['regional_service_center_title'],
            'РСЦ вашої області' => $fieldValues['territorial_service_center_title'],
            'Послуга' => $fieldValues['service_title'],
        ];
    }
}