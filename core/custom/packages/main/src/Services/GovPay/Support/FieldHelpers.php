<?php
namespace EvolutionCMS\Main\Services\GovPay\Support;


class FieldHelpers
{
    public static function policeSecurityAccountParser($value){
        if (mb_strlen($value, "UTF-8") == 9 && substr($value, 0, 2) == '62') {
            $kod_o = mb_substr($value, 0, 1, "UTF-8");
            $kod_r = mb_substr($value, 1, 2, "UTF-8");
        } else{
            $kod_o = mb_substr($value, 0, 3, "UTF-8");
            $kod_r = mb_substr($value, 3, 2, "UTF-8");
        }

        return [
            'kod_o'=>$kod_o,
            'kod_r'=>$kod_r,
        ];
    }

}