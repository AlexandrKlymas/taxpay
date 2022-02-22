<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseOrderGenerator;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class MarriageOrderGenerator extends BaseOrderGenerator
{
    public function createOder(array $formData):ServiceOrder
    {
        $serviceId = $this->serviceFactory->getServiceId();
        $formFieldsValues = $this->serviceFactory->getFormConfigurator()->getFormFieldsValues($formData);
        $recipientListDecorator = $this->serviceFactory->getCommissionsManager()->getRecipientListDecorator($formData);
        $paymentAmountDto = $recipientListDecorator->getAmountDto();
        $commissionDto = $recipientListDecorator->getCommissionDto();

        $serviceOrder = ServiceOrder::new($serviceId, $formFieldsValues, $paymentAmountDto, $commissionDto);

        foreach ($recipientListDecorator->getRecipientsDtoList() as $paymentRecipientDto) {
            PaymentRecipient::new($serviceOrder->id, $paymentRecipientDto);
        }

        return $serviceOrder;
    }
}