<?php

namespace EvolutionCMS\Main\Controllers\Department;

use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteContent;

class   ListController extends BaseController
{
    public function render()
    {
        $departments = SiteContent::withTVs(['image'])->where('parent',2)->active()->orderBy('menuindex')->get();

        foreach ($departments as &$department) {
            $titleWords = explode(',',$department->pagetitle);

            $department->titleFirstWord = array_shift($titleWords);
            $department->anotherFirstWord = implode(' ',$titleWords);

        }
        $this->data['departments'] = $departments;


    }
}