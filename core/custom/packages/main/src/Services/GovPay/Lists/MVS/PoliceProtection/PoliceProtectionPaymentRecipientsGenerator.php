<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\PoliceProtection;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Support\FieldHelpers;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;
use EvolutionCMS\Main\Services\GovPay\Models\Bank;
use EvolutionCMS\Main\Services\GovPay\Models\PoliceProtectionCode;

class PoliceProtectionPaymentRecipientsGenerator implements IPaymentRecipientsGenerator
{

    private SumCalculator $sumCalculator;

    public function __construct()
    {
        $this->sumCalculator = new SumCalculator();
    }

    private string $purposeTemplate = '[+police_security_account+], з [+period_from+] по [+period_to+], [+full_name+], [+address+]';

    public function getPaymentRecipients($formFieldsValues): array
    {
        $kod = FieldHelpers::policeSecurityAccountParser($formFieldsValues['police_security_account']);

        $kod_o = $kod['kod_o'];
        $kod_r = $kod['kod_r'];

        $recipientBankName = '';
        $recipientData = PoliceProtectionCode::where('kod_o', $kod_o)->where('kod_r', $kod_r)->firstOrFail();

        $recipientBank = Bank::where('mfo',$recipientData->mfo)->first();
        if($recipientBank){
            $recipientBankName = $recipientBank->name;
        }

        $amount = $this->sumCalculator->calculate($formFieldsValues);


        $mainPaymentRecipientDto = new PaymentRecipientDto($recipientData->okpo,$recipientData->iban,$recipientData->mfo,$amount);
        $mainPaymentRecipientDto->setRecipientName($recipientData->name);
        $mainPaymentRecipientDto->setServiceName('Поліція охорони');
        $mainPaymentRecipientDto->setPurpose(PurposeHelpers::parse($this->purposeTemplate,$formFieldsValues));
        $mainPaymentRecipientDto->setRecipientBankName($recipientBankName);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);

        return [
            $mainPaymentRecipientDto
        ];
    }
}