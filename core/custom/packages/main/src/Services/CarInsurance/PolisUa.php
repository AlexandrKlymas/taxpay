<?php

namespace EvolutionCMS\Main\Services\CarInsurance;

use Illuminate\Support\Facades\Http;

class PolisUa
{
    private bool $testMode;
    protected $debugMode = true;
    protected $host = '';
    protected $testHost = 'https://test.polis.ua/';
    protected $prodHost = 'https://polis.ua/';
    protected $service = 'service/api/';
    protected $login = 'gospay';
    protected $password = 'gospay1';
    public $agentId = 'gospay';

    public function __construct(bool $testMode=false)
    {
        $this->testMode = $testMode;
        $this->host = $this->prodHost;

        if($this->testMode){
            $this->host = $this->testHost;
        }
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    protected function get(string $url): array
    {
        $response = Http::withBasicAuth($this->login, $this->password)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->get($url)
            ->body();
        $result = [];
        if(!empty($response)){
            $result = json_decode($response, true);
        }

        if($this->debugMode){
            evo()->logEvent(1,2,json_encode(['url'=>$url,'result'=>$result]),'POLISUA get '.$url);
        }

        return $result;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    protected function post(string $url, array $request): array
    {
        $result = json_decode(Http::withBasicAuth($this->login, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Locale' => 'uk_UA',
                'Content-Length' => strlen(json_encode($request)),
            ])
            ->post($url, $request)
            ->body(), true);

        if($this->debugMode){
            evo()->logEvent(1,2,json_encode(['url'=>$url,'request'=>$request,'result'=>$result]),'POLISUA post '.$url);
        }

        return $result;
    }

    /**
     * @param string $licensePlate
     * @return string json car info
     */
    public function getLicensePlateInfo(string $licensePlate): array
    {
        return $this->get($this->prodHost . $this->service . 'osgpo/auto-info/find/v2/' . $licensePlate);
    }

    /**
     * @return string json car types list
     */
    public function getCarTypes(): array
    {
        return $this->get($this->prodHost . $this->service . 'car-type');
    }

    public function getCarRegZones(): array
    {
        return $this->get($this->prodHost . $this->service . 'car-registration-zone');
    }

    /**
     * @return string json cities [city regId]
     */
    public function getZones(): array
    {
        return $this->get($this->prodHost . $this->service . 'reg-city/all/uk');
    }

    public function getPrograms(array $request): array
    {
        return $this->post($this->host . $this->service . '/program/search', $request);
    }

    public function getContract(array $request): array
    {
        return $this->post($this->host . $this->service . 'contract', $request);
    }

    public function bindPayment(string $contractId,$request):array
    {
        return $this->post($this->host . $this->service . 'contract/' . $contractId . '/payment',$request);
    }
    public function getPayment(string $contractId): array
    {
        return $this->get($this->host . $this->service .'payment-requisites/' . $contractId);
    }

    public function printContract(string $contractId): string
    {
        $contractFilePdf = '/assets/files/car_insutance/contracts/' . $contractId . '.pdf';
        if(file_exists(MODX_BASE_PATH . $contractFilePdf)){
            return $contractFilePdf;
        }
        $result = Http::withBasicAuth($this->login, $this->password)
            ->withHeaders(['Content-Type' => 'application/pdf'])
            ->get($this->host . $this->service . 'contract/' . $contractId . '/print/9');

        file_put_contents(MODX_BASE_PATH . $contractFilePdf, $result);

        return $contractFilePdf;
    }
}