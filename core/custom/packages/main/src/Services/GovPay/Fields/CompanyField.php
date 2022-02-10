<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField as IFieldAlias;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\AbstractField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class CompanyField extends TextField implements IFieldAlias
{

    public static function buildField($title = 'Для юр. осіб (назва, ЕДРПОУ)',$phl = 'ТОВ Луч, 12345678',$required = true)
    {
        return new TextField(
            'company',
            $title,
            $phl,
            $required,
            [
                'not_regex:~[^А-ЯЁ\.\w,ЇїЄєІі`\': -]~iu'
            ]
        );
    }


}