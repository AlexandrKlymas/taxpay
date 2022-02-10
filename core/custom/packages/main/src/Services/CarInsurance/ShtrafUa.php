<?php

namespace EvolutionCMS\Main\Services\CarInsurance;

use Illuminate\Support\Facades\Http;

class ShtrafUa
{
    protected $url = 'https://apis.shtrafua.com/gospay/search';
    protected $key = 'vspq49mUeW5cj7klKxOOo1fLcCdpLfXd3rboBhGP';

    public function findFine(string $licensePlate): array
    {
        return json_decode(
            Http::withHeaders(['x-api-key' => $this->key])
            ->withHeaders(['Content-Type'=>'application/json'])
            ->post($this->url,['car_number'=>$licensePlate])
            ->body(),true);
    }
}