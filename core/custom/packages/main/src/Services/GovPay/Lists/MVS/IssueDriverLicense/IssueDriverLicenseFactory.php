<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\IssueDriverLicense;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class IssueDriverLicenseFactory extends BaseServiceFactory implements IServiceFactory
{

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(IssueDriverLicenseFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(IssueDriverLicenseRecipientsGenerator::class,$this->dependencies);
    }

    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(IssueDriverLicensePaymentCalculator::class,$this->dependencies);
    }
}