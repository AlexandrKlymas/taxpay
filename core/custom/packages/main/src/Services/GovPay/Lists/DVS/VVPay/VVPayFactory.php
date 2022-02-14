<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DVS\VVPay;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;

class VVPayFactory extends BaseServiceFactory
{

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(VVPayFormConfigurator::class,$this->dependencies);
    }

    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(VVPayRecipientsGenerator::class,$this->dependencies);
    }
}