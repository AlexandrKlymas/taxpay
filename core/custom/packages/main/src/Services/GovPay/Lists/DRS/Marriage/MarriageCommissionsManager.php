<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Decorators\RecipientListDecorator;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseCommissionsManager;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceRecipient;

class MarriageCommissionsManager extends BaseCommissionsManager
{
    public function getRecipientListDecorator(array $formData): RecipientListDecorator
    {
        $orderMainRecipients = ServiceRecipient::where('sub_service_id',$formData['registry_office'])->get();

        return new RecipientListDecorator($orderMainRecipients,$this->serviceFactory->getFeeCalculator(),$formData);
    }
}