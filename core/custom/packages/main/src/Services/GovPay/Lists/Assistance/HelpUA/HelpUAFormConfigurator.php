<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Assistance\HelpUA;

use EvolutionCMS\Main\Services\GovPay\Dto\MerchantKeysDto;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\EmailField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SumField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class HelpUAFormConfigurator extends BaseFormConfigurator
{

    /**
     * @inheritDoc
     */
    public function getFormConfig(): array
    {
        return [
            new LayoutFields([
                FullNameField::buildField(
                    'Your full name',
                    'John Doe',
                    true,
                    'not_regex:~[^A-Za-zА-ЯЁЇїЄєІі`\' _-]~iu'
                ),
            ]),

            new LayoutFields([
                new TextField(
                    'email',
                    'Email',
                    'Your Email',
                    false,
                    []
                ),
            ]),

            new LayoutFields([
                SumField::build(
                    'SUM',
                    '0.00'
                )->setLang('en')
                ->setCurrency('usd'),
            ])

        ];
    }

    public function renderDataForPreview($fieldValues): array
    {
        $form =  [
            'Прізвище, ім`я та по-батькові'=> $fieldValues['full_name'],
        ];

        return $form;
    }

    public function getPaymentFormParams(MerchantKeysDto $merchantKeysDto, ServiceOrder $serviceOrder): array
    {
        return [
            'public_key' => $merchantKeysDto->getPublicKey(),
            'version' => 3,

            'action' => 'pay',

            'amount' => $serviceOrder->total,
            'currency' => 'USD',

            'description' => 'Assistance to Ukraine',
            'order_id' => $serviceOrder->payment_hash,

            'language' => 'uk',
            'paytypes' => 'apay,gpay,card,liqpay,privat24,masterpass,qr',

            'result_url' => evo()->getConfig('site_url') . 'liqpay-result',
            'server_url' => evo()->getConfig('site_url') . 'liqpay-server-request',

        ];
    }
}