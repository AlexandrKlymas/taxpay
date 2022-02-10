<?php


namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\AbstractField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;
use EvolutionCMS\Main\Services\GovPay\Support\FieldHelpers;
use EvolutionCMS\Main\Services\GovPay\Models\PoliceProtectionCode;

class PoliceSecurityAccountField
{

    public static function buildField($title = 'Номер особового рахунку',$placeholder = '123456789',$required = true): IField
    {
        $rules = [
            'regex:~^(\d{9,11})$~iu',
            function ($attribute, $value, $fail) {
                $kod = FieldHelpers::policeSecurityAccountParser($value);
                $kod_o = $kod['kod_o'];
                $kod_r = $kod['kod_r'];
                $result = PoliceProtectionCode::where('kod_o',$kod_o)->where('kod_r',$kod_r)->first();

                if(empty($result)){
                    $fail(trans('validation.custom.police_security_account.exists'));
                }
            },
        ];

        return new TextField('police_security_account',$title,$placeholder,$required,$rules);
    }

}