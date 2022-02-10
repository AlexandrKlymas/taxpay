<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DPS\ECabinetTax;

use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;

class ECabinetTaxRecipientsGenerator implements IPaymentRecipientsGenerator
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
//        dd(http_build_query([
//            'full_name' => 'Климась Олександр Олександрович',
//            'payment_id' => '39 84u48839',
//            'address'=>'Київ, Лаврухіна 19/16',
//            'date_from'=>'02.03.2020',
//            'date_to'=>'19.01.2021',
//            'bank_edrpou'=>'123456798',
//            'bank_account'=>'UA3094750923475456456456456456457',
//            'recipient_name'=>'ГОЛОВНЕ УДКСУ У КИЇВСЬКІЙ ОБЛАСТІ',
//            'bank_name'=>'Казначейство України (ул.адм.подат.)',
//            'purpose'=>'*105004940;Сплата50 64844 55;;;;;;',
//            'sum'=>'',
////            'commission'=>'',
////            'amount'=>'',
//        ]));
//        dd($formFieldsValues);
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