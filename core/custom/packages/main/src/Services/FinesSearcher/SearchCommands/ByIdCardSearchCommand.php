<?php
namespace EvolutionCMS\Main\Services\FinesSearcher\SearchCommands;


use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFineSearchCommand;

class ByIdCardSearchCommand implements IFineSearchCommand
{

    private $id;
    private $licensePlate;
    /**
     * @var IFinesApi
     */
    private $finesApi;

    public function __construct($id, $licensePlate)
    {
        $this->id = $id;
        $this->licensePlate = $licensePlate;
        $this->finesApi = evolutionCMS()->make(IFinesApi::class);
    }

    public function getLicensePlate()
    {
        return $this->licensePlate;
    }

    public function getDocumentId()
    {
        return $this->id;
    }

    public static function fromArray(array $searchRequest): IFineSearchCommand
    {
        return new self($searchRequest['id'],$searchRequest['licensePlate']);
    }

    public function toArray():array
    {
        return [
            'id' => $this->id,
            'licensePlate' => $this->licensePlate,
        ];
    }

    public function execute(): array
    {
        return $this->finesApi->searchFinesByIdCard($this->getDocumentId(),$this->getLicensePlate());

    }
}