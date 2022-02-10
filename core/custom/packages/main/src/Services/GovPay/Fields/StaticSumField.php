<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SumField;

class StaticSumField
{
    public static function buildField(string $value=''): IField
    {
        $staticSumField = SumField::build();

        $staticSumField->setValue($value);
        $staticSumField->disable();

        return $staticSumField;
    }
}