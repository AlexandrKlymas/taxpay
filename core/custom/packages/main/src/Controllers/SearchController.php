<?php
namespace EvolutionCMS\Main\Controllers;


use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Http\Request;

class SearchController
{






    public function search(Request $request){
        $q = SiteContent::active()->whereIn('template',[5,11,12,3]);

        if($request->has('query')){
            $q->where('pagetitle','like','%'.$request->get('query').'%');
        }

        $services = $q->get()->toArray();

        $result = [];

        foreach ($services as $service) {
            $result[] = [
                'title'=>$service['pagetitle'],
                'link'=> UrlProcessor::makeUrl($service['id'])
            ];
        }
        return $result;

    }
}