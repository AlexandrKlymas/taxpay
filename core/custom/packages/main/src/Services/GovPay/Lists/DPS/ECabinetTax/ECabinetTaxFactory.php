<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax;


use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICallbackService;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPreviewGenerator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class ECabinetTaxFactory extends BaseServiceFactory
{

    /**
     * @throws BindingResolutionException
     */
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(ECabinetTaxFormConfigurator::class, $this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator
    {
        return $this->container->make(ECabinetTaxRecipientsGenerator::class, $this->dependencies);
    }

    public function getPreviewGenerator(): IPreviewGenerator
    {
        return $this->container->make(ECabinetTaxPreviewGenerator::class, $this->dependencies);
    }
}