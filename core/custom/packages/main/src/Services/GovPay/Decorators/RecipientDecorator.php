<?php

namespace EvolutionCMS\Main\Services\GovPay\Decorators;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFeeCalculator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\BankItem;
use EvolutionCMS\Main\Services\GovPay\Models\CommissionsRecipients;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceCommission;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceRecipient;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;

class RecipientDecorator
{
    protected ServiceRecipient $recipient;

    protected array $commissions;

    protected IFeeCalculator $feeCalculator;

    protected array $formData;

    protected string $checkId;
    protected string $_tk;
    protected string $_gp;
    protected float $liqPayCommission = 0.00;
    protected float $sum = 0.00;
    protected float $amount = 0.00;
    protected float $tk = 0.00;
    protected float $gp = 0.00;
    protected float $gpCommission = 0.00;

    public function __construct(ServiceRecipient $recipient, IFeeCalculator $feeCalculator, array $formData)
    {
        $this->_gp = PaymentRecipient::RECIPIENT_GOVPAY_PROFIT;
        $this->_tk = PaymentRecipient::RECIPIENT_TK_COMMISSION;
        $this->recipient = $recipient;
        $this->feeCalculator = $feeCalculator;
        $this->formData = $formData;
        $commissions = ServiceCommission::where('service_recipient_id', $this->recipient->id)->get()->toArray();

        foreach($commissions as $k=>$commission){
            $commissions[$k]['recipient'] = CommissionsRecipients::find($commission['commissions_recipient_id'])->toArray();
        }
        foreach($commissions as $commission){
            $this->commissions[$commission['recipient']['recipient_type']] = $commission;
        }

        $this->sum = $recipient->sum;
        $this->calcGPCommission();
        $this->amount = $this->sum + $this->gpCommission;
        $this->liqPayCommission = $this->calcLiqPayCommission();
        $this->calcTK();
        $this->calcGP();

        return $this;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function getGP(): float
    {
        return $this->gp;
    }

    public function getTK(): float
    {
        return $this->tk;
    }

    public function getGPCommission(): float
    {
        return $this->gpCommission;
    }

    public function getLiqPayCommission():float
    {
        return $this->liqPayCommission;
    }

    public function getCheckId(): string
    {
        return $this->checkId;
    }

    public function setCheckId(string $checkId)
    {
        $this->checkId = $checkId;
    }

    protected function calcLiqPayCommission(): float
    {
        $liqPayCommissionPercent = floatval(evo()->getConfig('g_liqpey_commission'));
        $liqPayMinCommission = floatval(evo()->getConfig('g_liqpey_commission_min'));

        $liqPayCommissionAutoCalculated =
            round($this->amount * $liqPayCommissionPercent / 100, 2);

        if ($liqPayCommissionAutoCalculated < $liqPayMinCommission) {
            $liqPayCommissionAutoCalculated = $liqPayMinCommission;
        }

        return $liqPayCommissionAutoCalculated;
    }

    protected function calcGPCommission()
    {
        $commission = $this->commissions[$this->_gp];

        $this->gpCommission = $this->feeCalculator->setCommission(
            $commission['percent'],$commission['min'],$commission['max'],$commission['fix']
        )->calculate($this->sum);
    }

    protected function calcGP()
    {
        $this->gp = $this->gpCommission - $this->tk - $this->liqPayCommission;
    }

    protected function calcTK()
    {
        $commission = $this->commissions[$this->_tk];

        $this->tk += $this->feeCalculator->setCommission(
            $commission['percent'],$commission['min'],$commission['max'],$commission['fix']
        )->calculate($this->sum);
    }

    protected function getBankNameByMfo(int $mfo):string
    {
        return BankItem::where('mfo',$mfo)->first()->name;
    }

    public function getRecipientBankName():string
    {
        return $this->getBankNameByMfo($this->recipient->mfo);
    }

    public function getRecipient(): ServiceRecipient
    {
        return $this->recipient;
    }

    public function getRecipientDtoList(): array
    {
        $paymentMainRecipientsDto = new PaymentRecipientDto(
            $this->recipient->edrpou,
            $this->recipient->iban,
            $this->recipient->mfo,
            $this->sum
        );

        $paymentMainRecipientsDto->setRecipientName($this->recipient->recipient_name);
        $paymentMainRecipientsDto->setRecipientBankName($this->getBankNameByMfo($this->recipient->mfo));
        $paymentMainRecipientsDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);
        $paymentMainRecipientsDto->setPurpose(PurposeHelpers::parse($this->recipient->purpose_template,$this->formData));
        $dtoList[] = $paymentMainRecipientsDto;

        $gpCommission = $this->commissions[$this->_gp];
        $paymentGPRecipientsDto = new PaymentRecipientDto(
            $gpCommission['recipient']['edrpou'],
            $gpCommission['recipient']['iban'],
            $gpCommission['recipient']['mfo'],
            $this->gp
        );
        $paymentGPRecipientsDto->setRecipientName($gpCommission['recipient']['recipient_name']);
        $paymentGPRecipientsDto->setRecipientBankName($this->getBankNameByMfo($gpCommission['recipient']['mfo']));
        $paymentGPRecipientsDto->setRecipientType($this->_gp);
        $paymentGPRecipientsDto->setPurpose(PurposeHelpers::parse($gpCommission['recipient']['purpose_template'],$this->formData));
        $dtoList[] = $paymentGPRecipientsDto;

        $tkCommissions = $this->commissions[$this->_tk];
        $paymentTKRecipientsDto = new PaymentRecipientDto(
            $tkCommissions['recipient']['edrpou'],
            $tkCommissions['recipient']['iban'],
            $tkCommissions['recipient']['mfo'],
            $this->tk
        );
        $paymentTKRecipientsDto->setRecipientName($tkCommissions['recipient']['recipient_name']);
        $paymentTKRecipientsDto->setRecipientBankName($this->getBankNameByMfo($tkCommissions['recipient']['mfo']));
        $paymentTKRecipientsDto->setRecipientType($this->_tk);
        $paymentTKRecipientsDto->setPurpose(PurposeHelpers::parse($tkCommissions['recipient']['purpose_template'],$this->formData));
        $dtoList[] = $paymentTKRecipientsDto;

        return $dtoList;
    }
}