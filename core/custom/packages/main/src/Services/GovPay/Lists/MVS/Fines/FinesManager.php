<?php

namespace EvolutionCMS\Main\Services\GovPay\Managers;


use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFineSearchRequest;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines\Documents;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines\SearchRequests\ByDrivingLicenseSearchRequest;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines\SearchRequests\ByFineSearchRequest;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines\SearchRequests\ByIdCardSearchRequest;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines\SearchRequests\ByTaxNumberSearchRequest;

class FinesManager
{

    private $finesApi;

    public function __construct()
    {
        $this->finesApi = evolutionCMS()->make(IFinesApi::class);

    }

    public function searchFines(IFineSearchRequest $searchRequest)
    {
        $type = Documents::getShortName(get_class($searchRequest));
        $method = $type.'Search';
        $fines = $this->{$method}($searchRequest);




        return $fines;
    }

    private function byFineSearch(ByFineSearchRequest $searchRequest)
    {

        return $this->finesApi->searchFines($searchRequest->getSeries(),$searchRequest->getNumber(),$searchRequest->getLicensePlate());
    }

    private function byIdCardSearch(ByIdCardSearchRequest $searchRequest)
    {
        return $this->finesApi->searchFinesForIdCard($searchRequest->getDocumentId(),$searchRequest->getLicensePlate());
    }

    private function byTaxNumberSearch(ByTaxNumberSearchRequest $searchRequest)
    {
        return $this->finesApi->searchFinesForTaxNumber($searchRequest->getDocumentId(),$searchRequest->getLicensePlate());
    }

    private function byTechPassportSearch(ByDrivingLicenseSearchRequest $searchRequest)
    {
        return $this->finesApi->searchFinesByTechPassport($searchRequest->getSeries(),$searchRequest->getNumber(),$searchRequest->getLicensePlate());
    }

    private function byUkrainePassportSearch(ByDrivingLicenseSearchRequest $searchRequest)
    {
        return $this->finesApi->searchFinesByUkrainianPassport($searchRequest->getSeries(),$searchRequest->getNumber(),$searchRequest->getLicensePlate());

    }

    private function byDrivingLicenseSearch(ByDrivingLicenseSearchRequest $searchRequest)
    {
        return $this->finesApi->searchFinesByTechPassport($searchRequest->getSeries(),$searchRequest->getNumber(),$searchRequest->getLicensePlate());
    }
}