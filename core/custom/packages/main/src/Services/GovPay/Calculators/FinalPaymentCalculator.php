<?php
namespace EvolutionCMS\Main\Services\GovPay\Calculators;

use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;
use EvolutionCMS\Main\Services\GovPay\Factories\Calculators\FeeCalculatorFactory;
use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;

/**
 * TODO Придумать нормальное название
 */
class FinalPaymentCalculator
{
    private $paymentCalculator;
    private $serviceFeeCalculator;

    public function __construct($serviceId)
    {
        $this->paymentCalculator = ServiceFactory::makeFactoryForService($serviceId)->getPaymentCalculator();
        $this->serviceFeeCalculator = FeeCalculatorFactory::build($serviceId);
    }

    public function calculate($formData){

        $sum = $this->paymentCalculator->calculate($formData);
        $serviceFee =  $this->serviceFeeCalculator->calculate($sum);
        $total = $sum + $serviceFee;

        return  new PaymentAmountDto($sum,$serviceFee,$total);
    }
}