<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\SubServices;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;
use Exception;

class MarriageRecipientsGenerator implements IRecipientsGenerator
{
    /**
     * @throws Exception
     */
    public function getPaymentRecipients($formFieldsValues): array
    {
        $regOffice = SubServices::where('service_id',176)
            ->where('id',$formFieldsValues['registry_office'])->first();

        if(empty($regOffice)){
            throw new Exception('Recipients Шлюб за добу РАЦС не знайдено');
        }

        $serviceRecipients = ServiceRecipient::where('sub_service_id',$regOffice->id)
            ->get();

        $recipients = [];

        foreach($serviceRecipients as $serviceRecipient){
             $serviceRecipientDto = new PaymentRecipientDto(
                $serviceRecipient->edrpou,
                $serviceRecipient->iban,
                $serviceRecipient->mfo,
                $serviceRecipient->sum
            );
            $serviceRecipientDto->setRecipientName($serviceRecipient->recipient_name);
            $serviceRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);
            $purpose = PurposeHelpers::parse($serviceRecipient->purpose_template,$formFieldsValues);
            $serviceRecipientDto->setPurpose($purpose);
            $recipients[$serviceRecipient->id] = $serviceRecipientDto;
        }

        return $recipients;
    }
}