<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Dto\MerchantKeysDto;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

abstract class BaseFormConfigurator implements IFormConfigurator
{
    protected array $serviceConfig = [];

    public function __construct(array $serviceConfig)
    {
        $this->serviceConfig = $serviceConfig;

    }

    /**
     * @return IField[]
     */
    abstract public function getFormConfig(): array;

    public function renderFormFields(array $formConfig = []): array
    {
        $renderedFields = [];
        foreach ($this->getFormConfig() as $field) {
            $renderedFields[] = \View::make($field->getViewFile(), $field->getDataForRender());
        }

        return $renderedFields;
    }

    public function getValidationRules():array
    {
        $rules = [];
        foreach ($this->getFormConfig() as $field) {
            $rules = array_merge($rules, $field->getValidationRules());
        }
        return $rules;
    }

    public function getFormFieldsValues($formData):array
    {
        $formDataForSave = [];
        foreach ($this->getFormConfig() as $field) {
            $formDataForSave = array_merge($formDataForSave,$field->getValues($formData));
        }
        return $formDataForSave;
    }

    public function getPaymentFormParams(MerchantKeysDto $merchantKeysDto, ServiceOrder $serviceOrder): array
    {
        /** @var PaymentRecipient $mainRecipient */
        $mainRecipient = $serviceOrder->mainRecipients->first();


        return [
            'public_key' => $merchantKeysDto->getPublicKey(),
            'version' => 3,

            'action' => 'pay',

            'amount' => $serviceOrder->total,
            'currency' => 'UAH',

            'description' => $mainRecipient->purpose,
            'order_id' => $serviceOrder->payment_hash,

            'language' => 'uk',
            'paytypes' => 'apay,gpay,card,liqpay,privat24,masterpass,qr',

            'result_url' => evo()->getConfig('site_url') . 'liqpay-result',
            'server_url' => evo()->getConfig('site_url') . 'liqpay-server-request',

        ];
    }
}