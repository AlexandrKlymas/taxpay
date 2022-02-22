<?php

namespace EvolutionCMS\Main\Services\GovPay\Decorators;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFeeCalculator;
use EvolutionCMS\Main\Services\GovPay\Dto\CommissionDto;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;
use Illuminate\Database\Eloquent\Collection;

class RecipientListDecorator
{
    protected array $recipients;

    public function __construct(Collection $recipients, IFeeCalculator $feeCalculator, array $formData)
    {
        foreach ($recipients as $recipient){
            $this->addRecipient(new RecipientDecorator($recipient,$feeCalculator,$formData));
        }
    }

    public function addRecipient(RecipientDecorator $recipient)
    {
        $this->recipients[] = $recipient;
    }

    public function getAmountDto(): PaymentAmountDto
    {
        $tk = 0.00;
        $gp = 0.00;
        $sum = 0.00;
        $serviceFee = 0.00;

        foreach($this->recipients as $recipient){
            $sum += $recipient->getSum();
            $gp += $recipient->getGP();
            $tk += $recipient->getTK();
            $serviceFee += $recipient->getGPCommission();
        }

        $total = $sum + $tk + $gp;

        $liqPayCommission = $this->calcLiqPayCommission($total);

        return  new PaymentAmountDto($sum,$serviceFee,$total+$liqPayCommission);
    }

    public function getCommissionDto(): CommissionDto
    {
        $tk = 0.00;
        $gp = 0.00;
        $sum = 0.00;

        foreach($this->recipients as $recipient){
            $sum += $recipient->getSum();
            $gp += $recipient->getGP();
            $tk += $recipient->getTK();
        }

        $total = $sum + $tk + $gp;

        $liqPayCommission = $this->calcLiqPayCommission($total);

        return  new CommissionDto($liqPayCommission,$tk,$gp);
    }

    public function getRecipientsDtoList(): array
    {
        $dtoList = [];

        foreach ($this->recipients as $recipient){
            $dtoList = array_merge($dtoList, $recipient->getRecipientDto());
        }

        return $dtoList;
    }

    protected function calcLiqPayCommission(float $sum): float
    {
        $liqPayCommissionPercent = floatval(evo()->getConfig('g_liqpey_commission'));
        $liqPayMinCommission = floatval(evo()->getConfig('g_liqpey_commission_min'));

        $liqPayCommissionAutoCalculated =
            round($sum * $liqPayCommissionPercent / 100, 2);

        if ($liqPayCommissionAutoCalculated < $liqPayMinCommission) {
            $liqPayCommissionAutoCalculated = $liqPayMinCommission;
        }

        return $liqPayCommissionAutoCalculated;
    }
}