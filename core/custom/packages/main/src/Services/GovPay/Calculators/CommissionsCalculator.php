<?php

namespace EvolutionCMS\Main\Services\GovPay\Calculators;

use EvolutionCMS\Main\Services\GovPay\Dto\CommissionDto;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;

class CommissionsCalculator
{
    /**
     * @var float
     */
    private float $liqPayCommissionPercent;
    /**
     * @var float
     */
    private float $liqPayMinCommission;
    /**
     * @var float
     */
    private float $bankCommissionPercent;
    /**
     * @var float
     */
    private float $bankMinCommission;

    public function __construct(float $liqPayCommissionPercent,float $liqPayMinCommission, float $bankCommissionPercent, float $bankMinCommission)
    {
        $this->liqPayCommissionPercent = $liqPayCommissionPercent;
        $this->liqPayMinCommission = $liqPayMinCommission;
        $this->bankCommissionPercent = $bankCommissionPercent;
        $this->bankMinCommission = $bankMinCommission;
    }

    /**
     * @param PaymentAmountDto $paymentAmountDto
     * @return CommissionDto
     */
    public function calculate(PaymentAmountDto $paymentAmountDto): CommissionDto
    {
        $liqPayCommissionAutoCalculated = round($paymentAmountDto->getTotal() * $this->liqPayCommissionPercent / 100,2);

        $bankCommission = round($paymentAmountDto->getSum() * $this->bankCommissionPercent / 100, 2);

        if ($bankCommission < $this->bankMinCommission) {
            $bankCommission = $this->bankMinCommission;
        }

        if ($liqPayCommissionAutoCalculated < $this->liqPayMinCommission) {
            $liqPayCommissionAutoCalculated = $this->liqPayMinCommission;
        }

        $profit = round($paymentAmountDto->getServiceFee() - $liqPayCommissionAutoCalculated - $bankCommission,2);

        return new CommissionDto($liqPayCommissionAutoCalculated,$bankCommission,$profit);
    }
}