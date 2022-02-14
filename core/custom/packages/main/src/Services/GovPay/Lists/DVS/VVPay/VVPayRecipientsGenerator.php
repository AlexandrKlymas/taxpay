<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DVS\VVPay;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\Bank;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\VVPayDetails;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;

class VVPayRecipientsGenerator implements IRecipientsGenerator
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
        $districtId = $formFieldsValues['district'];


        $district = VVPayDetails::findOrFail($districtId);

        $bank = Bank::whereMfo($district->mfo)->first();

        $purposeTemplate = '№ ВП-[+series+], дата ВП-[+date+]р., [+ipn+], [+full_name+]';

        $purpose = PurposeHelpers::parse($purposeTemplate,array_merge($formFieldsValues));

        $mainPaymentRecipientDto = new PaymentRecipientDto($district->edrpou,$district->iban,$district->mfo,$this->sumCalculator->calculate($formFieldsValues));
        $mainPaymentRecipientDto->setRecipientName($district->recipient);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);
        $mainPaymentRecipientDto->setPurpose($purpose);
        if(!empty($bank)){
            $mainPaymentRecipientDto->setRecipientBankName($bank->name);
        }

        return [
            $mainPaymentRecipientDto
        ];
    }
}