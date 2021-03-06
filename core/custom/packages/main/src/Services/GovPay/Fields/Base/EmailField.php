<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;


class EmailField
{
    public static function buildField($title = 'Email',$placeholder= 'Ваш email',$required = true): TextField
    {
        return new TextField(
            'email',
            $title,
            $placeholder,
            $required,
            [
               'email'
            ]
        );
    }
}