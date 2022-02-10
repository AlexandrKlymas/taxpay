<?php

namespace EvolutionCMS\Main\Services\GovPay\Support;

class StrHelper
{
    public static function removeSpacers(string $string): string
    {
        $resultString = $string;
        $resultString = trim($resultString);
        $resultString = preg_replace("/[\s]+/u", ' ', $resultString);

        return $resultString;
    }

    public static function replaceSymbols(string $string): string
    {
        $resultString = $string;

        $resultString = str_replace('&quot;', '"', $resultString);
        $resultString = str_replace('–', '-', $resultString);
        $resultString = str_replace('\\', '/', $resultString);
        $resultString = str_replace('&', '', $resultString);
        $resultString = str_replace('$', '', $resultString);
        $resultString = str_replace('{', '', $resultString);
        $resultString = str_replace('}', '', $resultString);
        $resultString = str_replace('Ё', 'Е', $resultString);
        $resultString = str_replace('ё', 'е', $resultString);
        $resultString = str_replace('Э', 'Е', $resultString);
        $resultString = str_replace('э', 'е', $resultString);
        $resultString = str_replace('ы', 'и', $resultString);
        $resultString = str_replace('Ы', 'И', $resultString);
        $resultString = str_replace('ъ', '', $resultString);
        $resultString = str_replace('Ъ', '', $resultString);
        $resultString = str_replace('`', '\'', $resultString);
        $resultString = str_replace('ʼ', '\'', $resultString);
        $resultString = str_replace('’', '\'', $resultString);
        $resultString = str_replace('“', '"', $resultString);
        $resultString = str_replace('”', '"', $resultString);
        $resultString = str_replace('«', '"', $resultString);
        $resultString = str_replace('»', '"', $resultString);

        return $resultString;
    }

    public static function purposeClearing(string $string): string
    {
        return preg_replace("/[^A-Za-zАаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЬьЮюЯя0-9 №#:%.,;*+_()'\|\/\[\]\"\-]/u", '', $string);
    }

    public static function onlyUANameSymbols(string $string): string
    {
        return preg_replace("/[^АаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЬьЮюЯя '\"-]/u", '', $string);
    }

    public static function comaSpacer(string $string)
    {
        return preg_replace("/[\s]+,/u", ',', $string);
    }

    public static function trimProposeLength(string $string): string
    {
        return mb_substr($string,0,160,'UTF-8');
    }

    public static function namePrepare(string $name): string
    {
        $name = self::replaceSymbols($name);
        $name = self::removeSpacers($name);
        $name = self::onlyUANameSymbols($name);

        return $name;
    }

    public static function purposePrepare(string $purpose): string
    {
        $purpose = self::replaceSymbols($purpose);
        $purpose = self::removeSpacers($purpose);
        $purpose = self::comaSpacer($purpose);
        $purpose = self::purposeClearing($purpose);
        $purpose = self::trimProposeLength($purpose);

        return $purpose;
    }
}