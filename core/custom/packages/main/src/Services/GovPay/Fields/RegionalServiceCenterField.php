<?php
namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Fields\Base\SelectField;
use EvolutionCMS\Main\Services\GovPay\Models\RegionalServiceCenter;

class RegionalServiceCenterField
{

    public static function buildField($title = 'Обрати регіон',$phl = 'Оберіть...',$regionIds = [],$required = true,$showPrice = true){
        $q = RegionalServiceCenter::select(['*']);
        if($regionIds){
            $q->whereIn('id',$regionIds);
        }
        $regions = $q->get()->pluck('name_ua','id')->toArray();
        return new SelectField(
            'regional_service_center',
            $title,
            $phl,
            $regions,
        );
    }
}