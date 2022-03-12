<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;

use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use Exception;

class HelpuaController extends BaseController
{
    public function route()
    {
        evo()->sendForward(179);
    }

    /**
     * @throws Exception
     */
    public function render()
    {
        $serviceId = evo()->documentIdentifier;

//        $enMenu = [
//            'Послуги'=>'Services',
//            'Питання та відповіді' => 'FAQ',
//            'Оферта'=>'Offer',
//            'Контакти'=>'Contacts',
//        ];
//
//        foreach ($this->data['menu'] as $k=>$menu){
//            foreach($enMenu as $key=>$enMenuItem){
//                if($key==$menu['pagetitle']){
//                    $this->data['menu'][$k]['pagetitle'] = $enMenuItem;
//                }
//            }
//        }

        try {
            $serviceProcessor = new ServiceManager();
            $this->data['serviceId'] = $serviceId;
            $this->data['form'] = $serviceProcessor->renderForm($serviceId);
        } catch (ServiceNotFoundException $e) {
            $this->data['error'] = 'Development service';
        }
    }


}