<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseInvoiceGenerator;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class MarriageInvoiceGenerator extends BaseInvoiceGenerator
{
    public function generate(ServiceOrder $serviceOrder):string
    {
        $invoices = '';

        $recipientsListDecorator = $this->serviceFactory->getCommissionsManager()
            ->getRecipientListDecorator($serviceOrder->form_data);

        $recipientsListDecorator->loadChecks($serviceOrder);

        $invoices .= \View::make('partials.services.invoices.marriage_invoice')->with([
            'recipients'=>$recipientsListDecorator,
            'order' => $serviceOrder,
        ]);

        return \View::make('partials.services.invoices.owner',[
            'invoices' => $invoices,
        ])->render();
    }
}