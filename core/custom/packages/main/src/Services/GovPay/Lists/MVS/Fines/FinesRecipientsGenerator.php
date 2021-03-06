<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use Exception;

class FinesRecipientsGenerator implements IRecipientsGenerator
{
    /**
     * @throws Exception
     */
    public function getPaymentRecipients($formFieldsValues): array
    {
        $fine = Fine::findOrFail($formFieldsValues['fine_id']);

        $paidInfo = json_decode($fine->data['paidinfo'],true);

        if(empty($paidInfo)){
            throw new Exception('Fines paid-info is empty');
        }

        $recipientName  = $paidInfo['bank_name'].'/'.$paidInfo['kkvd_id'];

        $mainPaymentRecipientDto = new PaymentRecipientDto($paidInfo['bank_edrpou'],$paidInfo['bank_account'],'',$fine->data['sumPenalty']);

        $mainPaymentRecipientDto->setRecipientName($recipientName);
        $mainPaymentRecipientDto->setRecipientBankName('Казначейство України (ЕАП)');
        $mainPaymentRecipientDto->setPurpose($paidInfo['purpose']);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);

        return [
            $mainPaymentRecipientDto
        ];
    }
}