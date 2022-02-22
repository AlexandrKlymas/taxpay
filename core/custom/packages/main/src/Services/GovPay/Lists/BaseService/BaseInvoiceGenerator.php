<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class BaseInvoiceGenerator implements IInvoiceGenerator
{
    protected IServiceFactory $serviceFactory;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
    }

    public function generate(ServiceOrder $serviceOrder):string
    {
        $invoices = '';
        $recipients = $serviceOrder->mainRecipients;

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