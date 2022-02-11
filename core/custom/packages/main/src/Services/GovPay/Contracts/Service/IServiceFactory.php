<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;


use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use Illuminate\Contracts\Container\Container;

interface IServiceFactory
{
    public function __construct(Container $app, int $serviceId, array $serviceConfig = []);
    public function getFormConfigurator(): IFormConfigurator;
    public function getDataValidator(): IDataValidator;
    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator;
    public function getPaymentCalculator(): IPaymentCalculator;
    public function getInvoiceGenerator(): IInvoiceGenerator;
    public function getCommissionsManager(): ICommissionsManager;
    public function getCallbacksService(): ICallbackService;
    public function getServiceId():int;
}