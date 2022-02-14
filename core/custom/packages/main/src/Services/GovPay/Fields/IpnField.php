<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class IpnField
{
    public static function buildField($title = 'ІПН (ІНДИВІДУАЛЬНИЙ ПОДАТКОВИЙ НОМЕР)',$phl = '0123456789',$required = true): IField
    {
        return new TextField(
            'ipn',
            $title,
            $phl,
            $required,
            [
                'regex:~^\d{10,10}$~iu'
            ]
        );
    }
}