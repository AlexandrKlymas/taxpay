<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;

class SudyTaxFormConfigurator extends BaseFormConfigurator implements IFormConfigurator
{

    /**
     * @inheritDoc
     */
    public function getFormConfig(): array
    {
        return [];
    }

    public function getFormFieldsValues($formData): array
    {
        return [
            'edrpou' => $formData['edrpou'],
            'iban' => $formData['iban'],
            'mfo'=>$formData['mfo'],
            'sum'=>$formData['sum'],
            'order'=>$formData['order'],
            'full_name'=>$formData['full_name'],
            'email'=>$formData['email'],

            'holder'=>$formData['holder'],
            'bank_name'=>$formData['bank_name'],
            'details'=>$formData['details'],
            'backref'=>$formData['backref'],
        ];
    }

    public function renderDataForPreview($fieldValues): array
    {
        return $fieldValues;
    }
}