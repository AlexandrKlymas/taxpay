<?php
namespace EvolutionCMS\Main\Services\GovPay\Contracts;


interface IFineSearchCommand
{
    public function getLicensePlate();
    public function getDocumentId();

    public function execute(): array;

    public static function fromArray(array $searchRequest): IFineSearchCommand;
    public function toArray():array;
}