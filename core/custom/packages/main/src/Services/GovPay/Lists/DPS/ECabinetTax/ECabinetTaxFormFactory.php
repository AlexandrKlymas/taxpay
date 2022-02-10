<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax;

use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class ECabinetTaxFormFactory extends BaseServiceFactory implements IServiceFactory
{

    /**
     * @throws BindingResolutionException
     */
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(ECabinetTaxFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(ECabinetTaxRecipientsGenerator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(ECabinetTaxPaymentCalculator::class,$this->dependencies);
    }
}