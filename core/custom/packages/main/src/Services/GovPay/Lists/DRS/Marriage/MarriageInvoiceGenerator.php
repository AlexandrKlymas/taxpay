<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class MarriageInvoiceGenerator extends BaseInvoiceGenerator
{
    public function generate(ServiceOrder $serviceOrder):string
    {
        $invoices = '';
        $recipients = $serviceOrder->mainRecipients;

        $invoices .= \View::make('partials.services.invoices.marriage_invoice')->with([
            'recipients'=>$recipients,
            'order' => $serviceOrder
        ]);

        return \View::make('partials.services.invoices.owner',[
            'invoices' => $invoices,
        ])->render();
    }
}