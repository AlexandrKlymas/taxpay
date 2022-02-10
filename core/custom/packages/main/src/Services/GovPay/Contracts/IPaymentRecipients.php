<?php


namespace EvolutionCMS\Services\Contracts;


interface IPaymentRecipients
{
    /**
     * @return IServiceAmount[]
     */
    public function getPaymentRecipients($formData):array;
}