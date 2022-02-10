<?php

namespace EvolutionCMS\Main\Services\GovPay\Factories;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines\FinesFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\InstallationServicesPoliceProtection\InstallationServicesPoliceProtectionFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\IssueDriverLicense\IssueDriverLicenseFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\PoliceProtection\PoliceProtectionFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias;
use EvolutionCMS\Main\Support\Helpers;
use Illuminate\Contracts\Container\Container;

class ServiceFactory
{


    public static function makeFactoryForService($serviceId): IServiceFactory
    {
        $servicesAlias = new ServicesAlias();

        $evo = \EvolutionCMS();

        $formConfig = Helpers::multiFields(json_decode(evo()->documentObject[$servicesAlias->getServiceAlias($serviceId).'_fc'][1],true))[0];

        $regionIds = evo()->runSnippet('DocInfo',['field'=>'id_region','docid'=>63]);
        $regions = $regionIds? explode(',',$regionIds):[];

        $serviceIds = evo()->runSnippet('DocInfo',['field'=>'type_service','docid'=>63]);
        $services = $serviceIds? explode(',',$serviceIds):[];

        $serviceConfig = [
            'formConfig' => $formConfig,
            'regions'=>$regions,
            'services'=>$services,
        ];


        return $evo->make($servicesAlias->getServiceFactory($serviceId),[
            'serviceConfig'=>$serviceConfig
        ]);

    }
}