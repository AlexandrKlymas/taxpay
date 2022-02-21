<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IOrderGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Factories\Calculators\CommissionsCalculatorFactory as CommissionsCalculatorFactoryAlias;
use EvolutionCMS\Main\Services\GovPay\Factories\Recipients\BankRecipientDtoFactory;
use EvolutionCMS\Main\Services\GovPay\Factories\Recipients\GovPayRecipientDtoFactory;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class BaseOrderGenerator implements IOrderGenerator
{
    protected IServiceFactory $serviceFactory;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
    }

    public function createOder(array $formData):ServiceOrder
    {
        $serviceId = $this->serviceFactory->getServiceId();
        $finalPaymentCalculator = $this->serviceFactory->getFinalCalculator();

        $formConfigurator = $this->serviceFactory->getFormConfigurator();
        $paymentRecipientGenerator = $this->serviceFactory->getPaymentRecipientsGenerator();
        $commissionsCalculator = CommissionsCalculatorFactoryAlias::build();

        $formFieldsValues = $formConfigurator->getFormFieldsValues($formData);

        $this->serviceFactory->getDataValidator()->validate($formFieldsValues);

        $paymentRecipients = $paymentRecipientGenerator->getPaymentRecipients($formFieldsValues);

        $paymentAmountDto = $finalPaymentCalculator->calculate($formFieldsValues);

        $commissions = $commissionsCalculator->calculate($paymentAmountDto);

        $paymentRecipients[] = BankRecipientDtoFactory::build($commissions->getBankCommission(), $formFieldsValues);
        $paymentRecipients[] = GovPayRecipientDtoFactory::build($commissions->getProfit(), $formFieldsValues);


        $serviceOrder = ServiceOrder::new($serviceId, $formFieldsValues, $paymentAmountDto, $commissions);

        foreach ($paymentRecipients as $paymentRecipientDto) {
            PaymentRecipient::new($serviceOrder->id, $paymentRecipientDto);
        }

        return $serviceOrder;
    }
}