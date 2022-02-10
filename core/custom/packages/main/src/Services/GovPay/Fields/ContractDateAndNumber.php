<?php
namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class ContractDateAndNumber extends TextField implements IField
{

     private static $regex = '~^(\d\d\.\d\d\.\d\d\d\d) № (\d\d\d\d\d\d)$~ui';

    public static function buildField($title = 'Дата та номер договору',$phl = '07.01.2021 1234'){


        $rules = [
            'regex:'.self::$regex,
        ];
        return new self('contract_date_and_number',$title,$phl,true,$rules);
    }



    public function getValues($formData): array
    {
        $name = $this->name;
        $value = $formData[$this->name] ?? '';

        preg_match(self::$regex,$value,$matches);


        return [
            $name => $value,
            'contract_date' => $matches[1],
            'contract_number' => $matches[2],
        ];
    }


    public function getViewFile(): string
    {
        return 'partials.services.fields.base.text';
    }
}