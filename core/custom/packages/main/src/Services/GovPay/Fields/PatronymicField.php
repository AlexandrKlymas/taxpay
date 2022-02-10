<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class PatronymicField
{
    public static function buildField($title = 'По-батькові', $phl = 'Петрович',$required = true): IField
    {
        return new TextField(
            'patronymic',
            $title,
            $phl,
            $required,
            [
                'not_regex:~[^А-ЯЁЇїЄєІі`\' _-]~iu'
            ]
        );
    }
}