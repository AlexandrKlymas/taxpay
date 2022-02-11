<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IDataValidator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BaseDataValidator implements IDataValidator
{

    /**
     * @var IServiceFactory
     */
    private IServiceFactory $serviceFactory;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
    }

    public function getRules(): array
    {
        return $this->serviceFactory->getFormConfigurator()->getValidationRules();
    }

    /**
     * @throws ValidationException
     */
    public function validate($fieldValues)
    {
        Validator::make($fieldValues, $this->getRules())->validate();
    }
}