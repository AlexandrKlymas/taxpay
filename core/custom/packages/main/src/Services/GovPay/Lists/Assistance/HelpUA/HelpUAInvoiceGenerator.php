<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Assistance\HelpUA;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class HelpUAInvoiceGenerator extends BaseInvoiceGenerator
{
    public function generate(ServiceOrder $serviceOrder): string
    {
        $invoices = '';

        $paymentRecipient = PaymentRecipient::where('service_order_id', $serviceOrder->id)->first();

        $invoices .= \View::make('partials.services.invoices.helpua_invoice')->with([
            'order' => $serviceOrder,
            'recipient' => $paymentRecipient,
        ]);

        return \View::make('partials.services.invoices.owner', [
            'invoices' => $invoices,
        ])->render();
    }
}