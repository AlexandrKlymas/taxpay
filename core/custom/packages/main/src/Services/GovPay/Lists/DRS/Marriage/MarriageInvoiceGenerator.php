<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class MarriageInvoiceGenerator extends BaseInvoiceGenerator
{
    public function generate(ServiceOrder $serviceOrder)
    {
        $invoices = '';
        $recipients = $serviceOrder->mainRecipients;

        evo()->invokeEvent('OnCheckFound',[
            'service_order'=>$serviceOrder,
        ]);

        foreach ($recipients as $recipient) {

            $invoices .= \View::make('partials.services.invoices.pcr_invoice')->with([
                'recipient'=>$recipient,
                'order' => $serviceOrder
            ]);

        }
        return \View::make('partials.services.invoices.owner',[
            'invoices' => $invoices,
        ])->render();
    }
}