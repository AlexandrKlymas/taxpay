<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;

class PCRDataValidator extends \EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseDataValidator implements \EvolutionCMS\Main\Services\GovPay\Contracts\Service\IDataValidator
{
    public function getRules(): array
    {

        $rules['name'] = ['required',];
        $rules['surname'] = ['required',];
        $rules['patronymic'] = ['required',];
        return  $rules;
    }
}