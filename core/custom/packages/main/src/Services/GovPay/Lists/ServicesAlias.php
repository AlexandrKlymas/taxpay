<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists;


use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR\PCRFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax\ECabinetTaxFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage\MarriageFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\DVS\VVPay\VVPayFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\WeightFinesByAct\WeightFinesByActFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxFactory;
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
    protected array $serviceIdMap =  [
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
            'factory'=> ECabinetTaxFactory::class,
            'alias'=>'e_cabinet_tax',
        ],
        162 => [
            'factory'=> SudyTaxFactory::class,
            'alias'=>'sudy_tax',
        ],
        160 => [
            'factory'=> WeightFinesByActFactory::class,
            'alias'=>'weight_fines_by_act'
        ],
        170 => [
            'factory'=> PCRFactory::class,
            'alias'=>'pcr',
        ],
        176 => [
            'alias'=>'marriage',
            'factory'=> MarriageFactory::class,
        ],
        173 => [
            'factory'=> VVPayFactory::class,
            'alias'=>'vvpay'
        ],
    ];

    public function getService(int $serviceId): array
    {
        if(!array_key_exists($serviceId,$this->serviceIdMap)){
            throw new ServiceNotFoundException('Service not found');
        }
        return $this->serviceIdMap[$serviceId];

    }

    public function getServiceAlias(int $serviceId): string
    {
        return $this->getService($serviceId)['alias'];
    }

    public function getServiceFactory(int $serviceId): string
    {
        return $this->getService($serviceId)['factory'];
    }
}