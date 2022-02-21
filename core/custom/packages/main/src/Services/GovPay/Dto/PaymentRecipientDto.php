<?php
namespace EvolutionCMS\Main\Services\GovPay\Dto;

class PaymentRecipientDto
{

    public function getEdrpou(): string
    {
        return $this->edrpou;
    }

    public function getAccount(): string
    {
        return $this->account;
    }


    public function getMfo(): string
    {
        return $this->mfo;
    }


    public function getAmount(): float
    {
        return $this->amount;
    }


    public function getRecipientName(): string
    {
        return $this->recipientName;
    }

    public function getRecipientBankName(): string
    {
        return $this->recipientBankName;
    }


    public function getPurpose(): string
    {
        return $this->purpose;
    }

    public function getRecipientType(): string
    {
        return $this->recipientType;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function setRecipientName($recipientName): void
    {
        $this->recipientName = $recipientName;
    }

    public function setRecipientBankName($recipientBankName): void
    {
        $this->recipientBankName = $recipientBankName;
    }

    public function setPurpose($purpose): void
    {
        $this->purpose = $purpose;
    }

    public function setRecipientType($recipientType): void
    {
        $this->recipientType = $recipientType;
    }
    public function setServiceName($serviceName): void
    {
        $this->serviceName = $serviceName;
    }

    private string $edrpou='';
    private string $account='';
    private string $mfo='';
    private float $amount;


    private string $recipientName='';
    private string $recipientBankName='';
    private string $purpose='';

    private string $recipientType='';
    private string $serviceName='';

    public function __construct(string $edrpou, string $account, string $mfo, float $amount)
    {
        $this->edrpou = $edrpou;
        $this->account = $account;
        $this->amount = $amount;
        $this->mfo = $mfo;
    }
}