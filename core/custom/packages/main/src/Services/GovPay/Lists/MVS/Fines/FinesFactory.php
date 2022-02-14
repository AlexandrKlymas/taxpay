<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IAfterConfirmExecutable;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IDataValidator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IExecutor;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class FinesFactory extends BaseServiceFactory implements IServiceFactory, IAfterConfirmExecutable
{
    public function getDataValidator(): IDataValidator
    {
        return $this->container->make(FinesDataValidator::class,$this->dependencies);
    }

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(FinesFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(FinesRecipientsGenerator::class,$this->dependencies);
    }

    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(FinesPaymentCalculator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getExecutor(): IExecutor
    {
        return $this->container->make(FinesExecutor::class,$this->dependencies);
    }
}