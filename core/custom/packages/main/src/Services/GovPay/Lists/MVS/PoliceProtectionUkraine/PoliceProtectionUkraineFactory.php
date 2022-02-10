<?php
namespace EvolutionCMS\Services\ServicesList\MVS\PoliceProtectionUkraine;

use EvolutionCMS\Services\Contracts\IFormConfigurator;
use EvolutionCMS\Services\Contracts\IPaymentRecipients;
use EvolutionCMS\Services\Contracts\IServiceFactory;
use EvolutionCMS\Services\ServicesList\BaseService\BaseServiceFactory;

class PoliceProtectionUkraineFactory extends BaseServiceFactory implements IServiceFactory
{

    public function getFormConfigurator(): IFormConfigurator
    {
        return new PoliceProtectionUkraineFormConfigurator();
    }

    public function getPaymentRecipients(): IPaymentRecipients
    {
        return new PoliceProtectionUkrainePaymentRecipients();
    }

}