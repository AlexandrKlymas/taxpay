<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFinalCalculator;

class MarriageFinalCalculator extends BaseFinalCalculator
{
    public function calculate(array $formData): PaymentAmountDto
    {
        $commissions = $this->serviceFactory->getCommissionsManager()->getCommissions($formData['registry_office']);

        $sum = 0.00;
        $serviceFee = 0.00;

        foreach($commissions as $commission){
            $this->serviceFeeCalculator->setCommission(
                $commission['percent'],
                $commission['min'],
                $commission['max'],
                $commission['fix']
            );
            $sum += $this->paymentCalculator->calculate(['sum'=>$commission['sum']]);
            $serviceFee += $this->serviceFeeCalculator->calculate($commission['sum']);
        }

        $total = $sum + $serviceFee;

        return  new PaymentAmountDto($sum,$serviceFee,$total);
    }
}