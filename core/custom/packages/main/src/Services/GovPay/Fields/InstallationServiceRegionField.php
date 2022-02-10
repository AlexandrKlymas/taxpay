<?php
namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SelectField;
use EvolutionCMS\Main\Services\GovPay\Models\MontcodeItem;

class InstallationServiceRegionField
{

    public static function buildField($title = 'Оберіть регіон',$phl = 'Регіон'): IField
    {
        $regions = MontcodeItem::get()->pluck('name_ua', 'name_ua')->toArray();



        return new SelectField(
            'installation_service_region',
            $title,
            $phl,
            $regions
        );
    }



}