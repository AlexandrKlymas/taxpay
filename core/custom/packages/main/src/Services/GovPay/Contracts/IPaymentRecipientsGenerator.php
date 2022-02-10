<?php


namespace EvolutionCMS\Main\Services\GovPay\Contracts;


interface IPaymentRecipientsGenerator
{

    public function getPaymentRecipients($formFieldsValues):array;
}