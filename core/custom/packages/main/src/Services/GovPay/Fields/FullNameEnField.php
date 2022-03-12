<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class FullNameEnField
{
    public static function buildField($title = 'Your full name',$phl = 'John Doe',$required = true): IField
    {
        return new TextField(
            'full_name',
            $title,
            $phl,
            $required,
            [
                'not_regex:~[^A-Za-zА-ЯЁЇїЄєІі`\' _-]~iu'
            ]
        );
    }
}