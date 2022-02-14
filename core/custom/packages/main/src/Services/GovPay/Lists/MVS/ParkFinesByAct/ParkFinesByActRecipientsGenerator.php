<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\ParkFinesByAct;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\Bank;
use EvolutionCMS\Main\Services\GovPay\Models\ParkPencodeItem;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;

class ParkFinesByActRecipientsGenerator implements IRecipientsGenerator
{

    /**
     * @var SumCalculator
     */
    private SumCalculator $sumCalculator;

    public function __construct(SumCalculator $sumCalculator)
    {
        $this->sumCalculator = $sumCalculator;
    }

    public function getPaymentRecipients($formFieldsValues): array
    {
        $regionId = $formFieldsValues['region'];

        $region = ParkPencodeItem::findOrFail($regionId);
        $purposeTemplate = '*;21081100;[+fine_series+];[+fine_number+];*[+full_name+];';

        $mfo = $region->mfo ?? '';
        $bank = Bank::whereMfo($region->mfo)->first();

        $purpose = PurposeHelpers::parse($purposeTemplate,array_merge($formFieldsValues));

        $mainPaymentRecipientDto = new PaymentRecipientDto(
            $region->okpo,$region->iban,$mfo,$this->sumCalculator->calculate($formFieldsValues));
        $mainPaymentRecipientDto->setRecipientName($region->description);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);
        $mainPaymentRecipientDto->setPurpose($purpose);
        if($bank){
            $mainPaymentRecipientDto->setRecipientBankName($bank->name);
        }

        return [
            $mainPaymentRecipientDto
        ];
    }
}
