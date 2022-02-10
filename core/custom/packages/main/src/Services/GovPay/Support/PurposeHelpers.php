<?php

namespace EvolutionCMS\Main\Services\GovPay\Support;


class PurposeHelpers
{
    public static function parse($template, $data): string
    {
        $evo = \EvolutionCMS();

        return $evo->parseText($template, $data);
    }
}