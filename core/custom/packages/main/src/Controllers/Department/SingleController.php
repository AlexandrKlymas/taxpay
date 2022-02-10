<?php

namespace EvolutionCMS\Main\Controllers\Department;

use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteContent;

class SingleController extends BaseController
{
    public function render()
    {
        $this->data['currentDepartmentForSidebar'] = SiteContent::active()->withTVs(['image'])->where('site_content.id',$this->evo->documentIdentifier)->first();
        $this->data['departmentsForSidebar'] = SiteContent::active()->where('parent',2)->where('site_content.id','!=',$this->evo->documentIdentifier)->orderBy('menuindex')->get();




        $units = SiteContent::active()->where('parent',$this->evo->documentIdentifier)->orderBy('menuindex')->get();

        foreach ($units as &$unit) {
            $unit->services = SiteContent::active()->where('parent',$unit->id)->orderBy('menuindex')->get();
        }

        $this->data['units'] = $units;


    }
}