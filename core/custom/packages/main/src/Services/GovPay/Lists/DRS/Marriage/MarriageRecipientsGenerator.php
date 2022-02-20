<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\SubServices;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteTmplvarContentvalue;
use Exception;

class MarriageRecipientsGenerator implements IRecipientsGenerator
{
    /**
     * @var SumCalculator
     */
    private SumCalculator $sumCalculator;

    private string $serviceName = 'Шлюб за добу';

    public function __construct(SumCalculator $sumCalculator)
    {
        $this->sumCalculator = $sumCalculator;
    }

    /**
     * @throws Exception
     */
    public function getPaymentRecipients($formFieldsValues): array
    {

        $regOffice = SubServices::where('service_id',176)
            ->where('id',$formFieldsValues['registry_office'])->first();

        if(empty($subService)){
            throw new Exception('Recipients Шлюб за добу РАЦС не знайдено');
        }


        $purposeTemplate = 'Шлюб за добу, [+full_name+]';
        $purpose = PurposeHelpers::parse($purposeTemplate,array_merge($formFieldsValues));
        $serviceName = 'Шлюб за добу';

        $recipient = Helpers::multiFields(
            json_decode(
                SiteTmplvarContentvalue::where('contentid', 176)
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
            $this->getGovTax($formFieldsValues),
            $this->getMinUst($formFieldsValues),
            $mainPaymentRecipientDto
        ];
    }

    private function buildRecipient(string $edrpou, string $account, float $amount, string $recipientName, string $purpose, string $bankName, string $serviceName, string $mfo=''): PaymentRecipientDto
    {

        $mainPaymentRecipientDto = new PaymentRecipientDto(
            $edrpou,
            $account,
            $mfo,
            $amount
        );

        $mainPaymentRecipientDto->setRecipientName($recipientName);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);
        $mainPaymentRecipientDto->setPurpose($purpose);
        $mainPaymentRecipientDto->setServiceName($serviceName);
        $mainPaymentRecipientDto->setRecipientBankName($bankName);

        return $mainPaymentRecipientDto;
    }

    private function getMinUst(array $formFieldsValues): PaymentRecipientDto
    {
        $edrpou = 43315602;
        $iban = 'UA188201720313291002201113571';
        $amount = 2740;
        $recipientName = 'ЦМРУ Міністерства юстиції (м. Київ)';
        $purposeTemplate = '*КІ52281*;Державна реєстрація шлюбу, [+full_name+]';
        $purpose = PurposeHelpers::parse($purposeTemplate,array_merge($formFieldsValues));
        $bankName = 'ДКС України (м. Київ)';

        return $this->buildRecipient($edrpou, $iban,$amount,$recipientName,$purpose,$bankName,$this->serviceName);
    }

    private function getGovTax(array $formFieldsValues): PaymentRecipientDto
    {
        $edrpou = 37993783;
        $iban = 'UA908999980314010537000026011';
        $amount = 0.85;
        $recipientName = 'ГУК у м.Києвi/Шевченк.р-н/22090100';
        $purposeTemplate = '*;101;2492017332;22090100; Державне мито, що сплачується за місцем розгляду та оформлення документів; [+full_name+]';
        $purpose = PurposeHelpers::parse($purposeTemplate,array_merge($formFieldsValues));
        $bankName = 'Казначейство України (ЕАП)';

        return $this->buildRecipient($edrpou, $iban,$amount,$recipientName,$purpose,$bankName,$this->serviceName);
    }
}