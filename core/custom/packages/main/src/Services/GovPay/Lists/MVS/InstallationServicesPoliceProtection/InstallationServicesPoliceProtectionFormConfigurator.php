<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\InstallationServicesPoliceProtection;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Fields\AddressField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\DateField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SumField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TotalCaptionField;
use EvolutionCMS\Main\Services\GovPay\Fields\CompanyField;
use EvolutionCMS\Main\Services\GovPay\Fields\ContractDateAndNumber;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\InstallationServiceRegionField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Fields\SeparatorField;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;

class InstallationServicesPoliceProtectionFormConfigurator extends BaseFormConfigurator implements IFormConfigurator
{

    public function getFormConfig(): array
    {

        $formConfig = $this->serviceConfig['formConfig'];

        return [
            new LayoutFields([
                FullNameField::buildField(
                    $formConfig['full_name_title']??'',
                    $formConfig['full_name_placeholder']??''
                ),
            ]),

            new LayoutFields([
                InstallationServiceRegionField::buildField(
                    $formConfig['installation_service_region_title']??'',
                    $formConfig['installation_service_region_placeholder']??''
                ),
                AddressField::buildField(
                    $formConfig['address_title']??'',
                    $formConfig['address_placeholder']??''
                ),
            ]),
            new SeparatorField('Дані об\'єкта монтажу'),
            new LayoutFields([
                CompanyField::buildField(
                    $formConfig['company_title']??'',
                    $formConfig['company_placeholder']??'',
                    false
                ),
            ]),
            new LayoutFields([
                ContractDateAndNumber::buildField(
                    $formConfig['contract_date_and_number_title']??'',
                    $formConfig['contract_date_and_number_placeholder']??''
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

        return [
            'Прізвище, ім`я та по-батькові' => $fieldValues['full_name'],
            'Регіон' => $fieldValues['installation_service_region'],
            'Адреса' => $fieldValues['address'],
            'Дані об\'єкта монтажу' => '',
            'Для юр. осіб (назва, ЕДРПОУ)' => $fieldValues['company'],
            'Номер договору' => $fieldValues['contract_number'],
            'Дата договору' => $fieldValues['contract_date'],

        ];
    }
}