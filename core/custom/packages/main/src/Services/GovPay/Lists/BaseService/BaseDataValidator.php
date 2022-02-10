<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IDataValidator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use Illuminate\Support\Facades\Validator;

class BaseDataValidator implements IDataValidator
{

    /**
     * @var IServiceFactory
     */
    private $serviceFactory;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
    }

    public function getRules(): array
    {
        return $this->serviceFactory->getFormConfigurator()->getValidationRules();
    }

    public function validate($fieldValues)
    {

        Validator::make($fieldValues, $this->getRules())->validate();
    }
}