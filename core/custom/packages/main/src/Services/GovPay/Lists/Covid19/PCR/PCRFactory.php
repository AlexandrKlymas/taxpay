<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;

use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICallbackService;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;

class PCRFactory extends BaseServiceFactory implements IServiceFactory
{

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(PCRFormConfigurator::class,$this->dependencies);
    }

    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(PCRRecipientsGenerator::class,$this->dependencies);
    }

    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(PCRPaymentCalculator::class,$this->dependencies);
    }
    public function getInvoiceGenerator(): IInvoiceGenerator
    {
        return $this->container->make(PCRInvoiceGenerator::class,$this->dependencies);
    }
    public function getCallbacksService(): ICallbackService
    {
        return $this->container->make(PCRCallbackService::class,$this->dependencies);
    }
}