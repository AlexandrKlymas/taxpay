<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Assistance\HelpUA;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;

class HelpUARecipientsGenerator implements IRecipientsGenerator
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
        $directPaymentRecipientDto = new PaymentRecipientDto(
            '39048249',
            'UA483052990000026005006227540',
            '305299',
            $this->sumCalculator->calculate($formFieldsValues)
        );

        $directPaymentRecipientDto->setRecipientName('Government Payments LLC');
        $directPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_DIRECT);
        $directPaymentRecipientDto->setPurpose('Assistance to Ukraine');
        $directPaymentRecipientDto->setServiceName('HelpUA');
        $directPaymentRecipientDto->setRecipientBankName('JSC CB PRIVATBANK');

        return [
            $directPaymentRecipientDto
        ];
    }
}