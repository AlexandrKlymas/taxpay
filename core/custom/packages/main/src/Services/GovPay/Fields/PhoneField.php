<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class PhoneField
{
    public static function buildField($title = 'Телефон для звязку', $placeholder = '+380670000000', $required = true): IField
    {
        return new TextField(
            'phone',
            $title,
            $placeholder,
            $required,
            [
                'regex:~^(\+)\d{10,15}$~iu'
            ]

        );
    }
}