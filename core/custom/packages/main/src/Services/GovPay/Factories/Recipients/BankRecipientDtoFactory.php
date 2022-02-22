<?php

namespace EvolutionCMS\Main\Services\GovPay\Factories\Recipients;

use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;

class BankRecipientDtoFactory
{
    public static function build(float $amount, array $formFieldsValues): PaymentRecipientDto
    {
        $fio = $formFieldsValues['full_name'] ?? '';

        $bankRecipientDto = new PaymentRecipientDto('39048249','UA903801060000000000006519011','380106',$amount);
        $bankRecipientDto->setPurpose('Комісія згідно договору №2 від 12.05.16р., '. trim($fio));
        $bankRecipientDto->setRecipientName('АТ «БАНК ТРАСТ-КАПІТАЛ»');
        $bankRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TK_COMMISSION);

        return $bankRecipientDto;
    }
}