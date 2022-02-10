<?php

namespace EvolutionCMS\Main\Services\FinesSearcher;


use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;

class FinePaidStatusChanger
{
    public function admitFineIsPaid($protocolSeries, $protocolNumber)
    {

        Fine::updateOrCreate([
            'protocol_series' => $protocolSeries,
            'protocol_number' => $protocolNumber,
        ], [
            'paid' => 1
        ]);

    }
}