<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ILiqPayKeys;
use EvolutionCMS\Main\Services\GovPay\Dto\LiqPayKeysDto;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteTmplvarContentvalue;

class PCRLiqPayKeys implements ILiqPayKeys
{
    public function getLiqPayKeys(): LiqPayKeysDto
    {
        $liqPayKeys = Helpers::multiFields(
            json_decode(
                SiteTmplvarContentvalue::where('contentid', 170)
                    ->where('tmplvarid', 33)
                    ->first()['value'], true))[0];

        return new LiqPayKeysDto($liqPayKeys['public_key'], $liqPayKeys['private_key'],false);
    }
}