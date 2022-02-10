<?php
namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IDataValidator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use Illuminate\Contracts\Container\Container;

interface IServiceFactory
{
    public function __construct(Container $app,$serviceConfig = []);
    public function getFormConfigurator(): IFormConfigurator;
    public function getDataValidator(): IDataValidator;
    public function getPaymentRecipientsGenerator(): IPaymentRecipientsGenerator;
    public function getPaymentCalculator(): IPaymentCalculator;
    public function getInvoiceGenerator(): IInvoiceGenerator;
}