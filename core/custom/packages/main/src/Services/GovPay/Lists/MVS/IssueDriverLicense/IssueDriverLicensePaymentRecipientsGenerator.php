<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\IssueDriverLicense;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\ServiceFieldPriceCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\Bank;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\RegionalServiceCenter;
use EvolutionCMS\Main\Services\GovPay\Models\Service;
use EvolutionCMS\Main\Services\GovPay\Models\TerritorialServiceCenter;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;

class IssueDriverLicensePaymentRecipientsGenerator implements IPaymentRecipientsGenerator
{

    /**
     * @var ServiceFieldPriceCalculator
     */
    private $calculator;

    public function __construct()
    {
        $this->calculator = new ServiceFieldPriceCalculator();
    }

    private $purposeTemplate = '*;[+regional_service_center_code+];[+service_code+];1;[+tax_number+];*[+full_name+],[+service_title+]';

    public function getPaymentRecipients($formFieldsValues): array
    {

        $amount = $this->calculator->calculate($formFieldsValues);

        $service = Service::find($formFieldsValues['service']);

        $regionalServiceCenter = RegionalServiceCenter::findOrFail($formFieldsValues['regional_service_center']);
        $territorialServiceCenter = TerritorialServiceCenter::findOrFail($formFieldsValues['territorial_service_center']);

        $recipientName = $territorialServiceCenter->name_ua;


        $rsc = TerritorialServiceCenter::where('region_id',$formFieldsValues['regional_service_center'])->where('name_ua','like','РСЦ%')->first();
        if($rsc){
            $recipientName = $rsc->name_ua;
        }



        $bankName = '';
        $mfo = $regionalServiceCenter->mfo;

        if ($mfo) {
            $bank = Bank::where('mfo',$mfo)->first();
            if($bank){
                $bankName = $bank->name;
            }
        }

        $purpose = PurposeHelpers::parse($this->purposeTemplate,array_merge($formFieldsValues,[
            'regional_service_center_code'=>$territorialServiceCenter->code,
            'service_code'=>$service->code,
        ]));

        $mainPaymentRecipientDto = new PaymentRecipientDto(
            $territorialServiceCenter->egrpou,
            $territorialServiceCenter->iban,
            $mfo,
            $amount
        );

        $mainPaymentRecipientDto->setRecipientName($recipientName);
        $mainPaymentRecipientDto->setRecipientBankName($bankName);

        $mainPaymentRecipientDto->setServiceName('Видача водійський посвідчент');
        $mainPaymentRecipientDto->setPurpose($purpose);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);


        return [
            $mainPaymentRecipientDto
        ];

    }
}