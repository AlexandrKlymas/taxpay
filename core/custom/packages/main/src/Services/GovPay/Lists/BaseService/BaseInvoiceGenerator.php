<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use Illuminate\Contracts\View\View;

class BaseInvoiceGenerator implements IInvoiceGenerator
{

    public function generate(ServiceOrder $serviceOrder): View
    {
        $invoices = '';
        $recipients = $serviceOrder->mainRecipients;

        evo()->invokeEvent('OnCheckFound',[
            'service_order'=>$serviceOrder,
        ]);

        foreach ($recipients as $recipient) {

            $invoices .= \View::make('partials.services.invoices.invoice')->with([
                'recipient'=>$recipient,
                'order' => $serviceOrder
            ]);

        }
        return \View::make('partials.services.invoices.owner',[
            'invoices' => $invoices,
        ]);
    }
}