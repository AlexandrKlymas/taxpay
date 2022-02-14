<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\WeightFinesByAct;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class WeightFinesByActFactory extends BaseServiceFactory
{
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(WeightFinesByActFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(WeightFinesByActRecipientsGenerator::class,$this->dependencies);
    }
}