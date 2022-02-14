<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;

class ECabinetTaxRecipientsGenerator implements IRecipientsGenerator
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
        $mainPaymentRecipientDto = new PaymentRecipientDto(
            $formFieldsValues['bank_edrpou'],
            $formFieldsValues['bank_account'],
            $formFieldsValues['mfo'],
            $this->sumCalculator->calculate($formFieldsValues));

        $mainPaymentRecipientDto->setRecipientName($formFieldsValues['recipient_name']);
        $mainPaymentRecipientDto->setRecipientBankName($formFieldsValues['bank_name']);
        $mainPaymentRecipientDto->setPurpose($formFieldsValues['purpose']);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);

        return [
            $mainPaymentRecipientDto
        ];
    }
}