<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class SurnameField
{
    public static function buildField($title = 'Прізвище', $phl = 'Петров',$required = true): IField
    {
        return new TextField(
            'surname',
            $title,
            $phl,
            $required,
            [
                'not_regex:~[^А-ЯЁЇїЄєІі`\' _-]~iu'
            ]
        );
    }
}