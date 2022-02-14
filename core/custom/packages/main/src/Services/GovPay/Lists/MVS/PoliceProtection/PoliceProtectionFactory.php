<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\PoliceProtection;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class PoliceProtectionFactory extends BaseServiceFactory
{
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(PoliceProtectionFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(PoliceProtectionRecipientsGenerator::class,$this->dependencies);
    }
}