<?php


namespace EvolutionCMS\Main\Services\GovPay\Factories\Recipients;


use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;

class GovPayRecipientDtoFactory
{
    public static function build(float $amount, array $formFieldsValues)
    {

        $purpose = PurposeHelpers::parse(evo()->getConfig('g_recipients_govpay24_purpose'), $formFieldsValues);


        $edrpou = trim(evo()->getConfig('g_recipients_govpay24_edrpou'));
        $account = trim(evo()->getConfig('g_recipients_govpay24_account'));
        $mfo = trim(evo()->getConfig('g_recipients_govpay24_mfo'));
        $recipientName = trim(evo()->getConfig('g_recipients_govpay24_name'));



        $bankRecipientDto = new PaymentRecipientDto($edrpou,$account,$mfo,$amount);
        $bankRecipientDto->setPurpose($purpose);
        $bankRecipientDto->setRecipientName($recipientName);
        $bankRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_GOVPAY_PROFIT);

        return $bankRecipientDto;
    }
}