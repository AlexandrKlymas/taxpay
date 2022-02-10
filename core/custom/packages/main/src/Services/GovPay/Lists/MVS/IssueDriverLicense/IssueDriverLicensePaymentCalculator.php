<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\IssueDriverLicense;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\ServiceFieldPriceCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;

class IssueDriverLicensePaymentCalculator implements IPaymentCalculator
{

    /**
     * @var ServiceFieldPriceCalculator
     */
    private $serviceFieldPriceCalculator;

    public function __construct()
    {
        $this->serviceFieldPriceCalculator = new ServiceFieldPriceCalculator();
    }

    public function calculate(array $fieldValues)
    {
        return $this->serviceFieldPriceCalculator->calculate($fieldValues);
    }
}