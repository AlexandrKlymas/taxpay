<?php
namespace EvolutionCMS\Main\Services\GovPay\Calculators;


use EvolutionCMS\Main\Services\GovPay\Dto\CommissionDto;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;

class CommissionsCalculator
{

    /**
     * @var float
     */
    private $liqpayCommissionPercent;
    /**
     * @var float
     */
    private $liqpayMinCommission;
    /**
     * @var float
     */
    private $bankCommissionPercent;
    /**
     * @var float
     */
    private $bankMinCommission;

    public function __construct(float $liqpayCommissionPercent,float $liqpayMinCommission, float $bankCommissionPercent, float $bankMinCommission)
    {
        $this->liqpayCommissionPercent = $liqpayCommissionPercent;
        $this->liqpayMinCommission = $liqpayMinCommission;
        $this->bankCommissionPercent = $bankCommissionPercent;
        $this->bankMinCommission = $bankMinCommission;


    }


    /**
     * @param PaymentAmountDto $paymentAmountDto
     */
    public function calculate(PaymentAmountDto $paymentAmountDto){

        $liqpayCommissionAutoCalculated = round($paymentAmountDto->getTotal() * $this->liqpayCommissionPercent / 100,2);

        $bankCommission = round($paymentAmountDto->getSum() * $this->bankCommissionPercent / 100, 2);


        if ($bankCommission < $this->bankMinCommission) {
            $bankCommission = $this->bankMinCommission;
        }

        if ($liqpayCommissionAutoCalculated < $this->liqpayMinCommission) {
            $liqpayCommissionAutoCalculated = $this->liqpayMinCommission;
        }


        $profit = round($paymentAmountDto->getServiceFee() - $liqpayCommissionAutoCalculated - $bankCommission,2);
        return new CommissionDto($liqpayCommissionAutoCalculated,$bankCommission,$profit);
    }
}