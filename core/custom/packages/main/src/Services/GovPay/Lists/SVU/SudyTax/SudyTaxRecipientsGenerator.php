<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;

class SudyTaxRecipientsGenerator implements IPaymentRecipientsGenerator
{
    /**
     * @var SumCalculator
     */
    private $sumCalculator;

    public function __construct(SumCalculator $sumCalculator)
    {
        $this->sumCalculator = $sumCalculator;
    }

    public function getPaymentRecipients($formFieldsValues): array
    {
        $mainPaymentRecipientDto = new PaymentRecipientDto(
            $formFieldsValues['edrpou'],
            $formFieldsValues['iban'],
            $formFieldsValues['mfo'],
            $this->sumCalculator->calculate($formFieldsValues));

        $mainPaymentRecipientDto->setRecipientName($formFieldsValues['holder']);
        $mainPaymentRecipientDto->setServiceName('Сплата судових зборів');
        $mainPaymentRecipientDto->setRecipientBankName($formFieldsValues['bank_name']);
        $mainPaymentRecipientDto->setPurpose($formFieldsValues['details']);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);

        return [
            $mainPaymentRecipientDto
        ];
    }
}