<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\InstallationServicesPoliceProtection;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class InstallationServicesPoliceProtectionFactory extends BaseServiceFactory implements IServiceFactory
{
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(InstallationServicesPoliceProtectionFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(InstallationServicesPoliceProtectionRecipientsGenerator::class,$this->dependencies);
    }
}