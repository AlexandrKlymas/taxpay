<?php


namespace EvolutionCMS\Main\Services\FinesSearcher\SearchCommands;


use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFineSearchCommand;

class ByTechPassportSearchCommand implements IFineSearchCommand
{
    /**
     * @var IFinesApi|mixed
     */
    private $finesApi;

    /**
     * @return mixed
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }
    private $series;
    private $number;
    private $licensePlate;

    public function __construct($series, $number, $licensePlate)
    {

        $this->series = $series;
        $this->number = $number;
        $this->licensePlate = $licensePlate;
        $this->finesApi = evolutionCMS()->make(IFinesApi::class);
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
        return new self($searchRequest['series'],$searchRequest['number'],$searchRequest['licensePlate']);
    }

    public function toArray():array
    {
        return [
            'series' => $this->series,
            'number' => $this->number,
            'licensePlate' => $this->licensePlate,
        ];
    }

    public function execute(): array
    {
        return $this->finesApi->searchFinesByTechPassport($this->getSeries(),$this->getNumber(),$this->getLicensePlate());

    }
}