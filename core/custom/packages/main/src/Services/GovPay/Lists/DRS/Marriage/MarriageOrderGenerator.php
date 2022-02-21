<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use EvolutionCMS\Main\Services\GovPay\Factories\Calculators\CommissionsCalculatorFactory as CommissionsCalculatorFactoryAlias;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseOrderGenerator;
use EvolutionCMS\Main\Services\GovPay\Models\CommissionsRecipients;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers;

class MarriageOrderGenerator extends BaseOrderGenerator
{
    public function createOder(array $formData):ServiceOrder
    {
        $serviceId = $this->serviceFactory->getServiceId();

        $finalPaymentCalculator = $this->serviceFactory->getFinalCalculator();

        $formFieldsValues = $this->serviceFactory->getFormConfigurator()->getFormFieldsValues($formData);

        $paymentRecipients = $this->serviceFactory->getPaymentRecipientsGenerator()->getPaymentRecipients($formFieldsValues);
        $feeCalculator = $this->serviceFactory->getFeeCalculator();
        $commissionsCalculator = CommissionsCalculatorFactoryAlias::build();

        $this->serviceFactory->getDataValidator()->validate($formFieldsValues);

        $paymentAmountDto = $finalPaymentCalculator->calculate($formFieldsValues);

        $commissions = $commissionsCalculator->calculate($paymentAmountDto);

        $commissionsList = $this->serviceFactory->getCommissionsManager()->getCommissions($formData['registry_office']);

        foreach($paymentRecipients as $paymentRecipientId=>$paymentRecipient){
            $paymentRecipientCommissions = [];
            foreach($commissionsList as $commissionItem){
                if($commissionItem['service_recipient_id']==$paymentRecipientId){
                    $paymentRecipientCommissions[] = $commissionItem;
                }
            }
            foreach ($paymentRecipientCommissions as $paymentRecipientCommission){
                $feeCalculator->setCommission(
                    $paymentRecipientCommission['percent'],
                    $paymentRecipientCommission['min'],
                    $paymentRecipientCommission['max'],
                    $paymentRecipientCommission['fix'],
                );
                $amount = $feeCalculator->calculate($paymentRecipient->getAmount());
                $commissionsRecipient = CommissionsRecipients::find($paymentRecipientCommission['commissions_recipient_id']);
                $paymentRecipientsDto = new PaymentRecipientDto(
                    $commissionsRecipient->edrpou,
                    $commissionsRecipient->iban,
                    $commissionsRecipient->mfo,
                    $amount
                );
                $paymentRecipientsDto->setRecipientName($commissionsRecipient->recipient_name);
                $paymentRecipientsDto->setRecipientType($commissionsRecipient->recipient_type);
                $paymentRecipientsDto->setPurpose(PurposeHelpers::parse($commissionsRecipient->purpose_template,$formFieldsValues));
                $paymentRecipients[] = $paymentRecipientsDto;
            }
        }

        $serviceOrder = ServiceOrder::new($serviceId, $formFieldsValues, $paymentAmountDto, $commissions);

        foreach ($paymentRecipients as $paymentRecipientDto) {
            PaymentRecipient::new($serviceOrder->id, $paymentRecipientDto);
        }

        return $serviceOrder;
    }
}