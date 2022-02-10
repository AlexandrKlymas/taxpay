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


    public function getRecipientName()
    {
        return $this->recipientName;
    }

    public function getRecipientBankName()
    {
        return $this->recipientBankName;
    }


    public function getPurpose()
    {
        return $this->purpose;
    }

    public function getRecipientType()
    {
        return $this->recipientType;
    }

    public function getServiceName()
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

    private $edrpou;
    private $account;
    private $mfo;
    private $amount;


    private $recipientName;
    private $recipientBankName;
    private $purpose;

    private $recipientType;
    private $serviceName;

    public function __construct(string $edrpou, string $account, string $mfo, float $amount)
    {
        $this->edrpou = $edrpou;
        $this->account = $account;
        $this->amount = $amount;
        $this->mfo = $mfo;
    }
}