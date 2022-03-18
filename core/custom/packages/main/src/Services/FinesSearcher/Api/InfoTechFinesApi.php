<?php

namespace EvolutionCMS\Main\Services\FinesSearcher\Api;

use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use Illuminate\Support\Facades\Http;

class InfoTechFinesApi implements IFinesApi
{
    private $token;
    /**
     * @var bool
     */
    private $test;
    /**
     * @var string
     */
    private string $version;

    public function __construct()
    {
        $this->test = false;
        $this->version = 'v3';

        $email = 'admin@govpay24.ua';
        $password = 'mctc6AgWW@vAVii';

        return '';
        return $this->token = Http::asForm()
            ->post('https://services.infotech.gov.ua/Token', [
                'grant_type' => 'password',
                'username' => $email,
                'Password' => $password,
            ]);
    }

    public function searchFines($series, $number, $licensePlate): array
    {
        return $this->request('SearchFines', [
            'series' => $series,
            'nDoc' => $number,
            'licensePlate' => $licensePlate
        ]);
    }

    public function searchFinesByLicense($series, $number, $dataIssue): array
    {

        return $this->request('SearchFinesForLicense', [
            'series' => $series,
            'number' => $number,
            'dateIssue' => $dataIssue,
        ]);
    }

    public function searchFinesByUkrainianPassport($series, $number, $licensePlate): array
    {
        return $this->request('SearchFinesForDocument', [
            'series' => $series,
            'number' => $number,
            'licensePlate' => $licensePlate,
        ]);
    }

    public function searchFinesByIdCard($idCard, $licensePlate): array
    {
        return $this->request('SearchFinesForDocument', [
            'series' => $idCard,
            'number' => '',
            'licensePlate' => $licensePlate,
        ]);
    }

    public function searchFinesByTaxNumber($taxNumber, $licensePlate): array
    {
        return $this->request('SearchFinesByTaxpayerRregNumber', [
            'rnokpp' => $taxNumber,
            'licensePlate' => $licensePlate,
        ]);
    }

    public function searchFinesByTechPassport($series, $number, $licensePlate): array
    {
        return $this->request('SearchFinesByCarRegCert', [
            'series' => $series,
            'number' => $number,
            'licensePlate' => $licensePlate,
        ]);
    }

    private function request($method, $params): array
    {
        $url = 'https://services.infotech.gov.ua/v3/';
        if ($this->test) {
            $url .= 'Test/';
        }
        $url .= $method;

        $response = Http::withHeaders([
            'Authorization' => $this->token['token_type'] . ' ' . $this->token['access_token']
        ])
            ->get($url, $params);


        if (isset($response['Message'])) {
            return [];
        }

        return $response->json();
    }

    public function payFine($docId, $sumPaid, $payId)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->token['token_type'] . ' ' . $this->token['access_token']
        ])
            ->get('https://services.infotech.gov.ua/'.($this->test?'Test/':'').'PayFine', [

                'docId' => $docId,
                'sumPaid' => $sumPaid,
                'payId' => $payId

            ]);

        $status = false;

        $data = [];
        if(!empty($response->body())){
            $data = json_decode($response->body(),true);
        }

        if($response->ok() && empty($response->body())){
            $status = true;
        }
        if($response->failed() && isset($data['Message']) && $data['Message'] === 'Оплата уже була здійснена раніше. Чекається підтвердження з Державної казначейської служби.'){
            $status = true;
        }

        if($status === false){
            throw new \Exception('Fine not paid');
        }
        return true;
    }

    public function getFinesByDate($startDateTime, $endDateTime = null): array
    {
        if(is_null($endDateTime)) {
            $endDateTime = date('d.m.Y h:i:s');
        }
        return $this->request('GetFines', [
            'startDateTime' => $startDateTime,
            'endDateTime' => $endDateTime,
        ]);
    }
}