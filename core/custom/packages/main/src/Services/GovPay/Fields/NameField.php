<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class NameField
{
    public static function buildField($title = 'Ім`я', $phl = 'Петро',$required = true): IField
    {
        return new TextField(
            'name',
            $title,
            $phl,
            $required,
            [
                'not_regex:~[^А-ЯЁЇїЄєІі`\' _-]~iu'
            ]
        );
    }
}