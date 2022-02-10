<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteTmplvarContentvalue;

class PCRRecipientsGenerator implements IPaymentRecipientsGenerator
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
        $purposeTemplate = 'COVID 19 Antigen Rapid Test, [+full_name+]';
        $purpose = PurposeHelpers::parse($purposeTemplate,array_merge($formFieldsValues));
        $serviceName = 'Covid-19 ПЛР тест';

        $recipient = Helpers::multiFields(
            json_decode(
                SiteTmplvarContentvalue::where('contentid', 170)
                    ->where('tmplvarid', 34)
                    ->first()['value'], true))[0];

        $edrpou = $recipient['edrpou'];
        $iban = $recipient['iban'];
        $bankName = $recipient['description'];
        $description = $recipient['bank_name'];

        $mainPaymentRecipientDto = new PaymentRecipientDto(
            $edrpou,
            $iban,
            '',
            $this->sumCalculator->calculate($formFieldsValues)
        );

        $mainPaymentRecipientDto->setRecipientName($description);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);
        $mainPaymentRecipientDto->setPurpose($purpose);
        $mainPaymentRecipientDto->setServiceName($serviceName);
        $mainPaymentRecipientDto->setRecipientBankName($bankName);

        return [
            $mainPaymentRecipientDto
        ];
    }
}
