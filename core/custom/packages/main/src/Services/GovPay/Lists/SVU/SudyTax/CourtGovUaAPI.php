<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use Illuminate\Support\Facades\Http;
use PHPMailer\PHPMailer\Exception;

class CourtGovUaAPI
{
    private bool $debugMode = true;
    private string $host = '';
    private string $testHost = 'https://payment-test.court.gov.ua/';
    private string $prodHost = 'https://payment.court.gov.ua/';
    private array $services = [
      'notify' => 'govpay24_notify.php',
    ];

    public function __construct()
    {
        $this->setHost($this->prodHost);

        if(evo()->getConfig('dev') === true){
            $this->setHost($this->testHost);
        }
    }

    private function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * @throws Exception
     */
    private function post(string $url, array $data): array
    {
        $response = Http::asForm()
            ->post($url,$data)
            ->body()
        ;

        $result = json_decode($response,true);

        if($this->debugMode){
            evo()->logEvent(1,1,json_encode([
                'url'=>$url,
                'data'=>$data,
                'result'=>$result,
                'response'=>$response
            ]),'Court API post -> '.$url);
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function notify(array $data): array
    {
        return $this->post($this->host.$this->services['notify'],$data);
    }

}