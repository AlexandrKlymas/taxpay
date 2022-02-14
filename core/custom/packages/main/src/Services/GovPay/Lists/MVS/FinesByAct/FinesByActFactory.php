<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\FinesByAct;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IAfterConfirmExecutable;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IExecutor;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class FinesByActFactory extends BaseServiceFactory implements IAfterConfirmExecutable
{
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(FinesByActFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(FinesByActRecipientsGenerator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getExecutor(): IExecutor
    {
        return $this->container->make(FinesByActExecutor::class,$this->dependencies);
    }
}