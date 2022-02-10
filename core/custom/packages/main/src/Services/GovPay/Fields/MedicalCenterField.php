<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Models\MedicalCenter;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SelectField;

class MedicalCenterField
{
    public static function buildField($title = 'Медичний заклад',$phl = 'Оберіть...',$medicalCentersIds = [],$required = true, $selected = null){

        $q = MedicalCenter::select(['*']);

        if(!empty($medicalCentersIds)){
            $q->whereIn('id',$medicalCentersIds);
        }

        $centers = $q->get()->pluck('name','id')->toArray();
        $select = new SelectField(
            'medical_center',
            $title,
            $phl,
            $centers,
            $required
        );

        if(!empty($selected) && array_key_exists($selected,$centers)){
            $select->setSelected($selected);
        }

        return $select;
    }
}