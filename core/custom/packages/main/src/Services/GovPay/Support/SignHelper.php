<?php

namespace EvolutionCMS\Main\Services\GovPay\Support;

class SignHelper
{
    private bool $debugMode;

    public function __construct(bool $debugMode=false)
    {
        $this->debugMode = $debugMode;
    }

    public function makeSign(array $requestArr, string $mac): string
    {
        if($this->debugMode){
            dump('Відний запит до перевірки підпису',
            $requestArr,
            'mac',
            $mac
            );
        }
        $jsonString = json_encode($requestArr);
        if($this->debugMode){
            dump('$jsonString',
                $jsonString
            );
        }
        $data = base64_encode($jsonString);
        if($this->debugMode){
            dump('$data',
                $jsonString
            );
        }

        $signString = $mac . $data . $mac;

        if($this->debugMode){
            dump('$signString',
                $signString
            );
        }

        $sign = base64_encode(sha1($signString));

        if($this->debugMode){
            dump('$sign',
                $sign
            );
        }

        return $sign;
    }

    public function validSign(array $requestArr, string $mac): bool
    {
        $sign = $requestArr['sign'];
        unset($requestArr['sign']);

        return $sign == $this->makeSign($requestArr,$mac);
    }
}