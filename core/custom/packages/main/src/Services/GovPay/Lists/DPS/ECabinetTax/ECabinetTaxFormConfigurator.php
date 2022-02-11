<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;

class ECabinetTaxFormConfigurator extends BaseFormConfigurator implements IFormConfigurator
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
            'full_name' => $formData['full_name'],
            'bank_edrpou'=>$formData['bank_edrpou'],
            'bank_account'=>$formData['bank_account'],
            'recipient_name'=>$formData['recipient_name'],
            'bank_name'=>$formData['bank_name'],
            'mfo'=>$formData['mfo'],
            'budgetcode'=>$formData['budgetcode'],
            'payercode'=>$formData['payercode'],
            'phone'=>$formData['phone']??'',
            'email'=>$formData['email']??'',
            'purpose'=>$formData['purpose'],
            'sum'=>$formData['sum'],
        ];
    }

    public function renderDataForPreview($fieldValues): array
    {
        $previewData = [
            'ПІБ'=> $fieldValues['full_name'],
            'Код'=> $fieldValues['payercode'],
        ];

        if(!empty($fieldValues['phone'])){
            $previewData['Телефон'] = $fieldValues['phone'];
        }

        if(!empty($fieldValues['email'])){
            $previewData['Email'] = $fieldValues['email'];
        }

        return $previewData;
    }

    public function getValidationRules(): array
    {
        return [
            'full_name' => 'required',
            'bank_edrpou'=>'required',
            'bank_account'=>'required',
            'recipient_name'=>'required',
            'bank_name'=>'required',
            'mfo'=>'required',
            'budgetcode'=>'required',
            'payercode'=>'required',
            'purpose'=>'required',
            'sum'=>'required',
        ];
    }
}