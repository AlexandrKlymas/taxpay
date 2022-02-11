<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\PoliceProtection;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
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
    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(PoliceProtectionPaymentRecipientsGenerator::class,$this->dependencies);
    }
}