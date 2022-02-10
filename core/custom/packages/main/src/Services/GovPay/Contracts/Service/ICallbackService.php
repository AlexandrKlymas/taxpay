<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

interface ICallbackService
{
    public function checkFoundCallback(array $params);
    public function liqPayCallback(array $params);
    public function invoicePDFGenerated(array $params);
}