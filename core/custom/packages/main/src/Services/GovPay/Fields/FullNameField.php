<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class FullNameField
{

    public static function buildField($title = 'Прізвище, ім`я та по-батькові',$phl = 'Петров Петро Петрович',$required = true): IField
    {
        return new TextField(
            'full_name',
            $title,
            $phl,
            $required,
            [
                'not_regex:~[^А-ЯЁЇїЄєІі`\' _-]~iu'
            ]
        );
    }


}