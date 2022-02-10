<?php


namespace EvolutionCMS\Main\Services\FinesSearcher\SearchCommands;


use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFineSearchCommand;

class ByTaxNumberSearchCommand implements IFineSearchCommand
{


    private $number;
    private $licensePlate;
    /**
     * @var IFinesApi|mixed
     */
    private $finesApi;

    public function __construct($number, $licensePlate)
    {

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
        return $this->number;
    }

    public static function fromArray(array $searchRequest): IFineSearchCommand
    {
        return new self($searchRequest['number'],$searchRequest['licensePlate']);
    }

    public function toArray():array
    {
        return [
            'number' => $this->number,
            'licensePlate' => $this->licensePlate,
        ];
    }

    public function execute(): array
    {
        return $this->finesApi->searchFinesByTaxNumber($this->getDocumentId(),$this->getLicensePlate());

    }
}