<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use Illuminate\Http\Request;

class SudyTaxSignatureHelper
{
    public static function makeSign(array $params)
    {

        $mac = evo()->getConfig('g_sys_sudytax_mac');

        $str = '';
        foreach($params as $param){
            $str .= empty($param)?'-':@strlen($param).$param;
        }
        return hash_hmac('sha1', $str, pack("H*",$mac));
    }

    public static function validSign(Request $request): bool
    {
        $params = $request->toArray();
        unset($params['q']);
        unset($params['P_SIGN']);

        $pSign = $request->get('P_SIGN');

        $sign = self::makeSign($params);

        return $pSign == $sign;
    }
}