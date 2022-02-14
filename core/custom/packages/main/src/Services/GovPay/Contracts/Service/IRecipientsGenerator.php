<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

interface IRecipientsGenerator
{
    public function getPaymentRecipients($formFieldsValues):array;
}