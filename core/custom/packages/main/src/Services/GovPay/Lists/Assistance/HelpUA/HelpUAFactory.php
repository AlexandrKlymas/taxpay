<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Assistance\HelpUA;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IMerchantManager;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IOrderGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPreviewGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class HelpUAFactory extends BaseServiceFactory
{
    /**
     * @throws BindingResolutionException
     */
    public function getPaymentRecipientsGenerator(): IRecipientsGenerator
    {
        return $this->container->make(HelpUARecipientsGenerator::class,$this->dependencies);
    }

    public function getMerchantManager(): IMerchantManager
    {
        return $this->container->make(HelpUAMerchantManager::class, $this->dependencies);
    }

    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(HelpUAFormConfigurator::class, $this->dependencies);
    }

    public function getPreviewGenerator(): IPreviewGenerator
    {
        return $this->container->make(HelperUAPreviewGenerator::class, $this->dependencies);
    }

    public function getOrderGenerator(): IOrderGenerator
    {
        return $this->container->make(HelpUAOrderGenerator::class, $this->dependencies);
    }

    public function getInvoiceGenerator(): IInvoiceGenerator
    {
        return $this->container->make(HelpUAInvoiceGenerator::class, $this->dependencies);
    }
}