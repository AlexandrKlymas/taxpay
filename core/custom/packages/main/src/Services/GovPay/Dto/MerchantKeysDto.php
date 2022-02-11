<?php

namespace EvolutionCMS\Main\Services\GovPay\Dto;

class MerchantKeysDto
{
    private string $publicKey;
    private string $privateKey;
    private bool   $sandBoxMode;

    public function __construct(string $publicKey, string $privateKey, bool $sandBoxMode=false)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->sandBoxMode = $sandBoxMode;
    }

    public function getSandBaxKey():bool
    {
        return $this->sandBoxMode;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}