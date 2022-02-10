<?php


namespace EvolutionCMS\Main\Services\FinesSearcher\Api;


use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;

class FakeFinesApi implements IFinesApi
{

    private $fines = [];

    public function __construct()
    {
        $this->fines = json_decode(file_get_contents(EVO_STORAGE_PATH.'fines/fake.json'),true);

    }

    public function searchFines($series, $number, $licensePlate): array
    {
        $filtered =  $this->filter([
            'fineSeries'=>$series,
            'fineNumber'=>$number,
            'licensePlate'=>$licensePlate
        ]);

        return $filtered;
    }

    public function searchFinesByLicense($series, $number, $dataIssue): array
    {
        return $this->filter([
            'licenseSeries'=>$series,
            'licenseNumber'=>$number,
            'licenseDateIssue'=>$dataIssue
        ]);
    }

    public function searchFinesByUkrainianPassport($series, $number, $licensePlate): array
    {
        return $this->filter([
            'passportSeries'=>$series,
            'passportNumber'=>$number,
            'licensePlate'=>$licensePlate
        ]);
    }

    public function searchFinesByIdCard($idCard,$licensePlate): array
    {
        return $this->filter([
            'idCard'=>$idCard,
            'licensePlate'=>$licensePlate
        ]);
    }

    public function searchFinesByTaxNumber($taxNumber, $licensePlate): array
    {
        return $this->filter([
            'taxNumber'=>$taxNumber,
            'licensePlate'=>$licensePlate
        ]);
    }

    public function searchFinesByTechPassport($series, $number, $licensePlate): array
    {
        return $this->filter([
            'carRegCerSeries'=>$series,
            'carRegCerNumber'=>$number,
            'licensePlate'=>$licensePlate
        ]);
    }



    private function filter(array $rules)
    {

        foreach ($rules as $rule) {
            if(empty($rule)){
                return [];
            }
        }


        $filtered =  array_filter($this->fines, function ($fine) use($rules){
            $diff = array_diff($rules,$fine);

            return empty($diff);
        });
        ;

        return $filtered;
    }

    public function payFine($docId, $sumPaid, $payId)
    {
        // TODO: Implement payFine() method.
    }



    public function getFinesByDate($startDateTime, $endDateTime = null): array
    {
        if(is_null($endDateTime)) {
            $endDateTime = date('d.m.Y  h:i:s');
        }
        return $this->filter( [
            'startDateTime' => $startDateTime,
            'endDateTime' => $endDateTime,
        ]);
    }
}