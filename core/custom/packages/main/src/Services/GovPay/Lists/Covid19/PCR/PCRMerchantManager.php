<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Covid19\PCR;

use EvolutionCMS\Main\Services\GovPay\Dto\MerchantKeysDto;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseMerchantManager;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteTmplvarContentvalue;

class PCRMerchantManager extends BaseMerchantManager
{
    public function getKeys(): MerchantKeysDto
    {
        $liqPayKeys = Helpers::multiFields(
            json_decode(
                SiteTmplvarContentvalue::where('contentid', 170)
                    ->where('tmplvarid', 33)
                    ->first()['value'], true))[0];

        return new MerchantKeysDto($liqPayKeys['public_key'], $liqPayKeys['private_key'],false);
    }
}