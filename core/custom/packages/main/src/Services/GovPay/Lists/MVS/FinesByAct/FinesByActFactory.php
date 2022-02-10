<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\FinesByAct;


use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IAfterConfirmExecutable;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IExecutor;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;

class FinesByActFactory extends BaseServiceFactory implements IServiceFactory,IAfterConfirmExecutable
{

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(FinesByActFormConfigurator::class,$this->dependencies);
    }

    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(FinesByActRecipientsGenerator::class,$this->dependencies);
    }

    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(FinesByActPaymentCalculator::class,$this->dependencies);
    }

    public function getExecutor(): IExecutor
    {
        return $this->container->make(FinesByActExecutor::class,$this->dependencies);
    }
}