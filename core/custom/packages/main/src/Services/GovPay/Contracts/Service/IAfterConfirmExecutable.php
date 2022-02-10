<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;
interface IAfterConfirmExecutable
{
    public function getExecutor(): IExecutor;
}