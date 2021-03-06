<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\InstallationServicesPoliceProtection;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\MontcodeItem;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;

class InstallationServicesPoliceProtectionRecipientsGenerator implements IRecipientsGenerator
{
    /**
     * @var SumCalculator
     */
    private SumCalculator $sumCalculator;

    public function __construct()
    {
        $this->sumCalculator = new SumCalculator();
    }

    private string $purposeTemplate = '[+contract_number+], [+contract_date+], [+full_name+], [+company+]';

    public function getPaymentRecipients($formFieldsValues): array
    {
        $region = $formFieldsValues['installation_service_region'];

        /** @var MontcodeItem $recipientData */
        $recipientData = MontcodeItem::where('name_ua',$region)->firstOrFail();

        $amount = $this->sumCalculator->calculate($formFieldsValues);

        $mainPaymentRecipientDto = new PaymentRecipientDto(
            $recipientData->okpo,
            $recipientData->iban,
            $recipientData->mfo,
            $amount
        );
        $mainPaymentRecipientDto->setRecipientName($recipientData->name_ua);
        $mainPaymentRecipientDto->setRecipientBankName($recipientData->description);

        $mainPaymentRecipientDto->setServiceName('Оплата монтажных послуг поліції охороны');
        $mainPaymentRecipientDto->setPurpose(PurposeHelpers::parse($this->purposeTemplate,$formFieldsValues));
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);

        return [
            $mainPaymentRecipientDto
        ];

    }
}