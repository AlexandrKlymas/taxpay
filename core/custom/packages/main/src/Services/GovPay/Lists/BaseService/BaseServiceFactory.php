<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IDataValidator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IInvoiceGenerator;
use Illuminate\Contracts\Container\Container;

class BaseServiceFactory
{
    protected $container;
    protected $dependencies = [];

    public function __construct(Container $container,$serviceConfig = [])
    {
        $this->container = $container;
        $this->dependencies = [
            'serviceFactory'=> $this,
            'serviceConfig'=>$serviceConfig
        ];
    }

    public function getDataValidator(): IDataValidator
    {
        return $this->container->make(BaseDataValidator::class,$this->dependencies);
    }


    public function getInvoiceGenerator(): IInvoiceGenerator
    {
        return $this->container->make(BaseInvoiceGenerator::class,$this->dependencies);
    }
}