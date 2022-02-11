<?php

namespace EvolutionCMS\Main\Services\GovPay\Factories;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias;
use EvolutionCMS\Main\Support\Helpers;

class ServiceFactory
{
    public static function makeFactoryForService(int $serviceId): IServiceFactory
    {
        $servicesAlias = new ServicesAlias();

        $formConfig = Helpers::multiFields(
            json_decode(evo()->documentObject[$servicesAlias
                ->getServiceAlias($serviceId).'_fc'][1]??'',
                true))[0]??[];

        $regionIds = evo()->runSnippet('DocInfo',['field'=>'id_region','docid'=>63]);
        $regions = $regionIds? explode(',',$regionIds):[];

        $serviceIds = evo()->runSnippet('DocInfo',['field'=>'type_service','docid'=>63]);
        $services = $serviceIds? explode(',',$serviceIds):[];

        $serviceConfig = [
            'formConfig' => $formConfig,
            'regions'=>$regions,
            'services'=>$services,
        ];

        return evo()->make($servicesAlias->getServiceFactory($serviceId),[
            'serviceId'=>$serviceId,
            'serviceConfig'=>$serviceConfig
        ]);

    }
}