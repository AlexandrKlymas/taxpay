<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class HiddenTextField
{
    public static function buildField(string $name, string $value='', bool $required=true): IField
    {
        $hiddenField = new TextField(
            $name,
            '',
            '',
            $required
        );

        $hiddenField->setValue($value);
        $hiddenField->hide();

        return $hiddenField;
    }
}