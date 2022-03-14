<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Assistance\HelpUA;

use EvolutionCMS\Main\Services\GovPay\Dto\CommissionDto;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseOrderGenerator;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class HelpUAOrderGenerator extends BaseOrderGenerator
{
    public function createOder(array $formData):ServiceOrder
    {
        $serviceId = $this->serviceFactory->getServiceId();
        $formFieldsValues = $this->serviceFactory->getFormConfigurator()->getFormFieldsValues($formData);
        $formFieldsValues['currency'] = $formData['currency'];

        $paymentAmountDto = new PaymentAmountDto($formFieldsValues['sum'],0.00,$formFieldsValues['sum']);
        $commissionDto = new CommissionDto($this->calcLiqPayCommission($formFieldsValues['sum']),0.00, 0.00);
        $serviceOrder = ServiceOrder::new($serviceId, $formFieldsValues, $paymentAmountDto, $commissionDto);

        $paymentRecipientGenerator = $this->serviceFactory->getPaymentRecipientsGenerator();
        $paymentRecipients = $paymentRecipientGenerator->getPaymentRecipients($formFieldsValues);


        foreach ($paymentRecipients as $paymentRecipientDto) {
            PaymentRecipient::new($serviceOrder->id, $paymentRecipientDto);
        }


        return $serviceOrder;
    }

    public function calcLiqPayCommission(float $sum): float
    {
        $liqPayCommissionPercent = floatval(evo()->getConfig('g_liqpey_commission'));
        $liqPayMinCommission = floatval(evo()->getConfig('g_liqpey_commission_min'));

        $liqPayCommissionAutoCalculated =
            round($sum * $liqPayCommissionPercent / 100, 2);

        if ($liqPayCommissionAutoCalculated < $liqPayMinCommission) {
            $liqPayCommissionAutoCalculated = $liqPayMinCommission;
        }

        return $liqPayCommissionAutoCalculated;
    }
}