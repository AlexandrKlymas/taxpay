<?php
namespace EvolutionCMS\Main\Services\GovPay\Contracts;

interface IFinesApi
{
    public function searchFines($series,$number,$licensePlate): array;
    public function searchFinesByLicense($series, $number, $dataIssue): array;
    public function searchFinesByUkrainianPassport($series,$number,$licensePlate): array;
    public function searchFinesByIdCard($idCard,$licensePlate): array;
    public function searchFinesByTaxNumber($taxNumber,$licensePlate): array;
    public function searchFinesByTechPassport($series,$number,$licensePlate): array;
    public function payFine($docId,$sumPaid,$payId);
    public function getFinesByDate($startDateTime, $endDateTime = null): array;
}