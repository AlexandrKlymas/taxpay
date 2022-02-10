<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\InstallationServicesPoliceProtection;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;


class InstallationServicesPoliceProtectionFactory extends BaseServiceFactory implements IServiceFactory
{
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(InstallationServicesPoliceProtectionFormConfigurator::class,$this->dependencies);
    }
    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(InstallationServicesPoliceProtectionPaymentRecipientsGenerator::class,$this->dependencies);
    }

    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(InstallationServicesPoliceProtectionPaymentCalculator::class,$this->dependencies);
    }
}