<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\WeightFinesByAct;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteTmplvarContentvalue;

class WeightFinesByActRecipientsGenerator implements IRecipientsGenerator
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
        $purposeTemplate = '*21081800;*[+fine_series+];[+fine_number+];*[+full_name+];';
        $purpose = PurposeHelpers::parse($purposeTemplate,array_merge($formFieldsValues));
        $serviceName = 'Штрафи перевищення габаритно-вагових норм';

        $formConfig = Helpers::multiFields(
            json_decode(
                SiteTmplvarContentvalue::where('contentid', 160)
                    ->where('tmplvarid', 32)
                    ->first()['value'], true))[0];

        $edrpou = $formConfig['edrpou'];
        $iban = $formConfig['iban'];
        $bankName = $formConfig['description'];
        $description = $formConfig['bankName'];

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
