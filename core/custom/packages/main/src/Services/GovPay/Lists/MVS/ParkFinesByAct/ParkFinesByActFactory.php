<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\ParkFinesByAct;

use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;

class ParkFinesByActFactory extends BaseServiceFactory implements IServiceFactory
{

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(ParkFinesByActFormConfigurator::class,$this->dependencies);
    }

    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(ParkFinesByActRecipientsGenerator::class,$this->dependencies);
    }

    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(ParkFinesByActPaymentCalculator::class,$this->dependencies);
    }
}