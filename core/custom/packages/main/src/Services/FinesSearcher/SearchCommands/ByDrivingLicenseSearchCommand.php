<?php

namespace EvolutionCMS\Main\Services\FinesSearcher\SearchCommands;

use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFineSearchCommand;

class ByDrivingLicenseSearchCommand implements IFineSearchCommand
{

    private $series;
    private $number;
    private $dateIssue;
    private $licensePlate;
    /**
     * @var IFinesApi|mixed
     */
    private $finesApi;

    public function __construct($series, $number, $dateIssue, $licensePlate)
    {

        $this->series = $series;
        $this->number = $number;
        $this->dateIssue = $dateIssue;
        $this->licensePlate = $licensePlate;
        $this->finesApi = evolutionCMS()->make(IFinesApi::class);
    }

    public function getSeries()
    {
        return $this->series;
    }
    public function getNumber()
    {
        return $this->number;
    }
    public function getDateIssue()
    {
        return $this->dateIssue;
    }

    public function getLicensePlate()
    {
        return $this->licensePlate;
    }

    public function getDocumentId()
    {
        return $this->series.$this->number;
    }

    public static function fromArray(array $searchRequest): IFineSearchCommand
    {
        return new self($searchRequest['series'],$searchRequest['number'],$searchRequest['dateIssue'],$searchRequest['licensePlate']);
    }

    public function toArray():array
    {
        return [
            'series' => $this->series,
            'number' => $this->number,
            'dateIssue' => $this->dateIssue,
            'licensePlate' => $this->licensePlate,
        ];
    }

    public function execute(): array
    {
        return $this->finesApi->searchFinesByLicense($this->getSeries(),$this->getNumber(),$this->getDateIssue());
    }
}