<?php


namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;

class FineSeries extends TextField implements IField
{

    public function getValues($formData): array
    {
        $value = $formData[$this->name] ?? '';

        return [
            $this->name => mb_strtoupper($value,'UTF-8')
        ];
    }
}