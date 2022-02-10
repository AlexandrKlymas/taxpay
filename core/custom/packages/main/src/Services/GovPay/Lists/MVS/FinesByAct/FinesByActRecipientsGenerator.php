<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\FinesByAct;


use EvolutionCMS\Main\Services\GovPay\Calculators\Forms\SumCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\IPaymentRecipientsGenerator;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Models\Bank;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\PencodesItem;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;

class FinesByActRecipientsGenerator implements IPaymentRecipientsGenerator
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

        $series = mb_strtoupper($formFieldsValues['fine_series'],'utf-8');
        $regionId = $formFieldsValues['region'];

        $photoFixation = in_array(mb_strtoupper($series,'utf-8'),['1AB','1АВ']);




        if($photoFixation === true){
            $photoFixationRegionId = !empty(evo()->getConfig('g_fine_photo_fixation_recipient_id'))?evo()->getConfig('g_fine_photo_fixation_recipient_id'):1;
            $region = PencodesItem::where('id',$photoFixationRegionId)->firstOrFail();
            $purposeTemplate = '*21081800;*[+fine_series+];[+fine_number+];*[+full_name+];';
        }
        else{
            $region = PencodesItem::findOrFail($regionId);
            $purposeTemplate = '*;21081300;[+fine_series+];[+fine_number+];*[+full_name+];';
        }



        $mfo = $region->mfo ?? '';
        $bank = Bank::whereMfo($region->mfo)->first();

        $purpose = PurposeHelpers::parse($purposeTemplate,array_merge($formFieldsValues));

        $mainPaymentRecipientDto = new PaymentRecipientDto($region->okpo,$region->iban,$mfo,$this->sumCalculator->calculate($formFieldsValues));
        $mainPaymentRecipientDto->setRecipientName($region->description);
        $mainPaymentRecipientDto->setRecipientType(PaymentRecipient::RECIPIENT_TYPE_MAIN);
        $mainPaymentRecipientDto->setPurpose($purpose);
        if($bank){
            $mainPaymentRecipientDto->setRecipientBankName($bank->name);
        }


        return [
            $mainPaymentRecipientDto
        ];
    }
}
