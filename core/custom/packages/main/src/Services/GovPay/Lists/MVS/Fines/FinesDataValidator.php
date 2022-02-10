<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IDataValidator;
use EvolutionCMS\Main\Services\GovPay\Fields\AddressField;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Fields\PeriodField;
use EvolutionCMS\Main\Services\GovPay\Fields\PoliceSecurityAccountField;
use EvolutionCMS\Main\Services\GovPay\Fields\SumField;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseDataValidator;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;

class FinesDataValidator extends BaseDataValidator implements IDataValidator
{

    public function getRules(): array
    {

        $rules['fine_id'] = [
            'required',
            function ($attribute, $value, $fail) {

                $fine = Fine::findOrFail($value);

                if(!$fine->canBePaid()){
                    $fail(trans('Штраф не може бути оплаченый'));
                }
            }
        ];
        return  $rules;
    }

}