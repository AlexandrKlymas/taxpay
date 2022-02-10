<?php

namespace EvolutionCMS\Services\ServicesList\MVS\PoliceProtectionUkraine;


use EvolutionCMS\Services\Contracts\IFormConfigurator;
use EvolutionCMS\Services\Fields\AddressField;
use EvolutionCMS\Services\Fields\FullNameField;
use EvolutionCMS\Services\Fields\PeriodField;
use EvolutionCMS\Services\Fields\PoliceSecurityAccountField;
use EvolutionCMS\Services\Fields\SumField;

class PoliceProtectionUkraineFormConfigurator implements IFormConfigurator
{

    public function getFormConfig(): array
    {
        /*
        Прізвище, ім`я та по-батькові: ІВіфва
        Номер особового рахунку: 11111111111
        Адреса: Васильківська
        Період сплати з: 23.12.2020
        Період сплати по: 24.12.2020
         */
        return [
            new FullNameField(true),
            new PoliceSecurityAccountField(true),
            new AddressField(true),
            new PeriodField(true),
            new SumField(true),
        ];
    }
}