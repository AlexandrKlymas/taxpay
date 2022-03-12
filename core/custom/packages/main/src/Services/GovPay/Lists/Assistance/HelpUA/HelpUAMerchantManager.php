<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\Assistance\HelpUA;

use EvolutionCMS\Main\Services\GovPay\Dto\MerchantKeysDto;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseMerchantManager;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteTmplvarContentvalue;

class HelpUAMerchantManager extends BaseMerchantManager
{
    public function getKeys():MerchantKeysDto
    {
        $liqPayKeys = Helpers::multiFields(
            json_decode(
                SiteTmplvarContentvalue::where('contentid', 179)
                    ->where('tmplvarid', 33)
                    ->first()['value'], true))[0];

        return new MerchantKeysDto($liqPayKeys['public_key'], $liqPayKeys['private_key'],false);
    }
}