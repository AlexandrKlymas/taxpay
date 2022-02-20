<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICommissionsManager;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class MarriageFactory extends BaseServiceFactory
{

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(MarriageFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(MarriageRecipientsGenerator::class,$this->dependencies);
    }

    public function getInvoiceGenerator(): IInvoiceGenerator
    {
        return $this->container->make(MarriageInvoiceGenerator::class,$this->dependencies);
    }

    public function getCommissionsManager(): ICommissionsManager
    {
        return $this->container->make(MarriageCommissionsManager::class,$this->dependencies);
    }
}