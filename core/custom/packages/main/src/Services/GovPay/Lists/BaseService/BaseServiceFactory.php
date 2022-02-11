<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;


use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICallbackService;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICommissionsManager;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IDataValidator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IMerchantManager;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPreviewGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

abstract class BaseServiceFactory implements IServiceFactory
{
    protected int $serviceId;
    protected Container $container;
    protected array $dependencies = [];

    public function __construct(Container $container, int $serviceId, array $serviceConfig = [])
    {
        $this->serviceId = $serviceId;
        $this->container = $container;
        $this->dependencies = [
            'serviceId'=>$this->serviceId,
            'serviceFactory' => $this,
            'serviceConfig' => $serviceConfig
        ];
    }

    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    /**
     * @throws BindingResolutionException
     */
    public function getDataValidator(): IDataValidator
    {
        return $this->container->make(BaseDataValidator::class, $this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getMerchantManager(): IMerchantManager
    {
        return $this->container->make(BaseMerchantManager::class, $this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getInvoiceGenerator(): IInvoiceGenerator
    {
        return $this->container->make(BaseInvoiceGenerator::class, $this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getCommissionsManager(): ICommissionsManager
    {
        return $this->container->make(BaseCommissionsManager::class, $this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getFormConfigurator(): IFormConfigurator
    {
        return $this->container->make(BaseFormConfigurator::class, $this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPaymentCalculator(): IPaymentCalculator
    {
        return $this->container->make(BasePaymentCalculator::class, $this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getCallbacksService(): ICallbackService
    {
        return $this->container->make(BaseCallbackService::class, $this->dependencies);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPreviewGenerator(): IPreviewGenerator
    {
        return $this->container->make(BasePreviewGenerator::class, $this->dependencies);
    }

    abstract public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator;
}