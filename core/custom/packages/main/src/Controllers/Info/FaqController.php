<?php

namespace EvolutionCMS\Main\Controllers\Info;

use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Support\Helpers;

class FaqController extends BaseController
{

    public function getMFItems($mfValue){
        $result = [];
        foreach ($mfValue as $k=>$item){

            //имя переменной => значение
            if(isset($item['value'])){
                $result[$k] = $item['value'];
            }

            //имя переменной => [ масив и уходим в рекурсию
            if(isset($item['items'])){
                $result[$k] = $this->getMFItems($item['items']);
            }
        }
        return $result;
    }

    public function render()
    {
        $content = json_decode($this->evo->documentObject['faq'][1], true);

        $this->data['faqGroups'] = Helpers::multiFields($content);
    }



}