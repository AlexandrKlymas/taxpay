<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\PoliceProtection;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;

class PoliceProtectionFactory extends BaseServiceFactory implements IServiceFactory
{
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(PoliceProtectionFormConfigurator::class,$this->dependencies);
    }
    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(PoliceProtectionPaymentRecipientsGenerator::class,$this->dependencies);
    }

    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(PoliceProtectionPaymentCalculator::class,$this->dependencies);
    }

}