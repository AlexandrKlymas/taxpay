<?php

namespace EvolutionCMS\Main\Services\CarInsurance;

use Illuminate\Support\Facades\Http;

class CarInsuranceApi
{
    protected $host = 'https://polis.ua/';
    protected $service = 'service/api/';
    protected $login = 'gospay';
    protected $password = 'gospay1';

    /**
     * @param string $licensePlate
     * @return string json car info
     */
    public function getLicensePlateInfo(string $licensePlate): string
    {
        return Http::withBasicAuth($this->login, $this->password)
            ->get($this->host.$this->service.'osgpo/auto-info/find/'.$licensePlate)
            ->body();
    }

    /**
     * @return string json car types list
     */
    public function getCarTypes(): string
    {
        return Http::withBasicAuth($this->login, $this->password)
            ->withHeaders(['Content-Type'=>'application/json'])
            ->get($this->host.$this->service.'car-type')
            ->body();
    }

    /**
     * @return string json cities [city regId]
     */
    public function getZones(): string
    {
        return Http::withBasicAuth($this->login, $this->password)
            ->withHeaders(['Content-Type'=>'application/json'])
            ->get($this->host.$this->service.'reg-city/all/uk')
            ->body();
    }

    public function getPrograms(array $request): string
    {
        return Http::withBasicAuth($this->login, $this->password)
            ->withHeaders([
                'Content-Type'=>'application/json',
                'Content-Length'=>strlen(json_encode($request)),
                ])
            ->post($this->host.$this->service.'osgpo/calc/progs/uk',$request)
            ->body();
    }
    public function getContract(array $request): string
    {
        return Http::withBasicAuth($this->login, $this->password)
            ->withHeaders([
                'Content-Type'=>'application/json',
                'Content-Length'=>strlen(json_encode($request)),
            ])
            ->post($this->host.$this->service.'osgpo/create/contract',$request)
            ->body();
    }
}