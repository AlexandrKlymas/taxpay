<?php

namespace EvolutionCMS\Main\Controllers\Department;

use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteContent;

class UnitController extends BaseController
{
    public function render()
    {
        $id = $this->evo->documentIdentifier;
        $parent = $this->evo->documentObject['parent'];

        $this->data['currentDepartmentForSidebar'] = SiteContent::withTVs(['image'])->where('site_content.id',$parent)->first();
        $this->data['departmentsForSidebar'] = SiteContent::where('parent',2)->where('site_content.id','!=',$parent)->orderBy('menuindex')->get();


        $units = SiteContent::where('id',$id)->get();

        foreach ($units as &$unit) {
            $unit->services = SiteContent::where('parent',$unit->id)->orderBy('menuindex')->get();
        }

        $this->data['units'] = $units;


    }
}