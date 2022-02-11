<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\ParkFinesByAct;

use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class ParkFinesByActFactory extends BaseServiceFactory
{

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(ParkFinesByActFormConfigurator::class,$this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(ParkFinesByActRecipientsGenerator::class,$this->dependencies);
    }
}