<?php
namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;



use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;

interface IDataValidator
{
    public function __construct(IServiceFactory $serviceFactory);

    public function getRules(): array;
    public function validate($fieldValues);
}