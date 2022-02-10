<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists;


use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR\PCRCallbackService;
use EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR\PCRFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR\PCRLiqPayKeys;
use EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR\PCRPreviewGenerator;
use EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax\ECabinetTaxCallbackService;
use EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax\ECabinetTaxFormFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\WeightFinesByAct\WeightFinesByActFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxCallbackService;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxFormFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines\FinesFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\FinesByAct\FinesByActFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\InstallationServicesPoliceProtection\InstallationServicesPoliceProtectionFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\IssueDriverLicense\IssueDriverLicenseFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\ParkFinesByAct\ParkFinesByActFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\PoliceProtection\PoliceProtectionFactory;

class ServicesAlias
{

    /**
     * @var string[][]
     */
    protected $serviceIdMap =  [
        26 => [
            'factory'=>PoliceProtectionFactory::class,
            'alias'=>'police_protection'
        ],

        47 => [
            'factory'=>FinesFactory::class,
            'alias'=>'fines'
        ],

        49 => [
            'factory' => InstallationServicesPoliceProtectionFactory::class,
            'alias'=>'installation_services_police_protection'
        ],

        63 => [
            'factory'=>IssueDriverLicenseFactory::class,
            'alias'=>'issue_driver_license'
        ],

        145 => [
            'factory'=>FinesByActFactory::class,
            'alias'=>'fines_by_act'
        ],
        11 => [
            'factory'=> ParkFinesByActFactory::class,
            'alias'=>'park_fines_by_act'
        ],
        165 => [
            'factory'=> ECabinetTaxFormFactory::class,
            'alias'=>'e_cabinet_tax',
            'callback_service'=>ECabinetTaxCallbackService::class,
        ],
        162 => [
            'factory'=> SudyTaxFormFactory::class,
            'alias'=>'sudy_tax',
            'callback_service'=>SudyTaxCallbackService::class,
        ],
        160 => [
            'factory'=> WeightFinesByActFactory::class,
            'alias'=>'weight_fines_by_act'
        ],
        170 => [
            'factory'=> PCRFactory::class,
            'alias'=>'pcr',
            'liqpay_keys'=>PCRLiqPayKeys::class,
            'callback_service'=>PCRCallbackService::class,
            'preview'=>PCRPreviewGenerator::class
        ],


    ];
    public function getService($serviceId){

        if(!array_key_exists($serviceId,$this->serviceIdMap)){
            throw new ServiceNotFoundException('Service not found');
        }
        return $this->serviceIdMap[$serviceId];

    }

    public function getServiceAlias($serviceId){
        return $this->getService($serviceId)['alias'];
    }


    public function getServiceFactory($serviceId){
        return $this->getService($serviceId)['factory'];
    }

    public function getAllServices(): array
    {
        return $this->serviceIdMap;
    }
}