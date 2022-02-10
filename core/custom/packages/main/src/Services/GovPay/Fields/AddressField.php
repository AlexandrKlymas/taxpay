<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Fields\Base\AbstractField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class AddressField
{

    public static function buildField($title = 'Адреса', $placeholder = 'Васильківська 55, кв. 77', $required = true)
    {
        return new TextField(
            'address',
            $title,
            $placeholder,
            $required,
            [
                'not_regex:~[^А-ЯЁ\.\d\/,ЁЇїЄєьІі`\' -]~iu'
            ]
        );
    }


}