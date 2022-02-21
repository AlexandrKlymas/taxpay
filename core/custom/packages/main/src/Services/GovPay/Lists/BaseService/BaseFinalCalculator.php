<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFeeCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFinalCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;

class BaseFinalCalculator implements IFinalCalculator
{
    protected int $serviceId;
    protected IServiceFactory $serviceFactory;
    private IPaymentCalculator $paymentCalculator;
    private IFeeCalculator $serviceFeeCalculator;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->serviceId = $this->serviceFactory->getServiceId();
        $this->paymentCalculator = $this->serviceFactory->getPaymentCalculator();
        $this->serviceFeeCalculator = $this->serviceFactory->getFeeCalculator();
    }

    public function calculate($formData): PaymentAmountDto
    {
        $commissions = $this->serviceFactory->getCommissionsManager()->getCommissions()['total'];
        if(!empty($commissions)){
            $this->serviceFeeCalculator->setCommission(
                $commissions['percent'],
                $commissions['min'],
                $commissions['max'],
                $commissions['fix']
            );
        }

        $sum = $this->paymentCalculator->calculate($formData);
        $serviceFee =  $this->serviceFeeCalculator->calculate($sum);
        $total = $sum + $serviceFee;

        return  new PaymentAmountDto($sum,$serviceFee,$total);
    }
}