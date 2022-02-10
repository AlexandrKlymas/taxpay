<?php
namespace EvolutionCMS\Main\Services\FinesSearcher;


use EvolutionCMS\Main\Services\FinesSearcher\Exceptions\ParseException;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByDrivingLicenseSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByFineSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByIdCardSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByTaxNumberSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByTechPassportSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByUkrainePassportSearchCommand;

class Documents
{
    /**
     * @var \DocumentParser
     */
    private $evo;

    public function __construct()
    {
        $this->evo = evolutionCMS();
    }

    public static $searchRequests = [
        'byDrivingLicense' => ByDrivingLicenseSearchCommand::class,
        'byFine' => ByFineSearchCommand::class,
        'byIdCard' => ByIdCardSearchCommand::class,
        'byTaxNumber' => ByTaxNumberSearchCommand::class,
        'byTechPassport' => ByTechPassportSearchCommand::class,
        'byUkrainePassport' => ByUkrainePassportSearchCommand::class,
    ];

    public static function getShortName($class){
        $metod = array_search($class,self::$searchRequests);
        if($metod === false){
            throw new \Exception('Request not supported');
        }
        return $metod;
    }
    public static function parseUkrainePassportString($value){
        $urkAlpha = evolutionCMS()->getConfig('urkAlpha');
        preg_match("~([$urkAlpha]{2})([0-9]{6})~ui",$value,$matches);

        if(empty($matches)){
            throw new ParseException('Can not parse Ukraine passport');
        }

        return [
            'series' => $matches[1],
            'number' => $matches[2],
        ];
    }


    public static function parseTechPassportString($value){
        $urkAlpha = evolutionCMS()->getConfig('urkAlpha');
        preg_match("~([$urkAlpha]{3})([0-9]{6})~ui",$value,$matches);

        if(empty($matches)){
            throw new ParseException('Can not parse tech passport');
        }

        return [
            'series' => $matches[1],
            'number' => $matches[2],
        ];
    }

    public static function parseDrivingLicenseString($value){
        $urkAlpha = evolutionCMS()->getConfig('urkAlpha');
        preg_match("~([$urkAlpha]{3})([0-9]*)~ui",$value,$matches);


        if(empty($matches)){
            throw new ParseException('Can not parse driving license');
        }

        return [
            'series' => $matches[1],
            'number' => $matches[2],
        ];
    }
}