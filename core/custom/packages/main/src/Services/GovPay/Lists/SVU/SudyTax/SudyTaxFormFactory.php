<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICallbackService;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class SudyTaxFormFactory extends BaseServiceFactory
{
    /**
     * @throws BindingResolutionException
     */
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(SudyTaxFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(SudyTaxRecipientsGenerator::class,$this->dependencies);
    }

    public function getCallbacksService(): ICallbackService
    {
        return $this->container->make(SudyTaxCallbackService::class,$this->dependencies);
    }
}