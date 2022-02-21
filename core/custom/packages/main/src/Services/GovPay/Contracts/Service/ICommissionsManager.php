<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;

interface ICommissionsManager
{
    public function getCommissions(int $subServiceId = 0): array;
}