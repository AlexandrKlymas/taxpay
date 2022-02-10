<?php
namespace EvolutionCMS\Main\Controllers;


use EvolutionCMS\Main\Services\CheckGovUa\CheckGovUa;

use EvolutionCMS\Main\Services\CheckGovUa\Exceptions\CheckNotFoundException;
use EvolutionCMS\Main\Services\CheckGovUa\Exceptions\NotAuthorizeRequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckGovUaController
{
    /**
     * @var CheckGovUa
     */
    private $CheckGovUaService;

    public function __construct()
    {
        $this->CheckGovUaService = new CheckGovUa();
    }

    public function getInfoAboutCheck(Request $request, Response $response){

        $headers = $request->headers->all();


        $findRequest = [
            'checkId'=>$headers['x-check-id'][0],
            'time'=>$headers['x-time'][0],
            'hmac'=>$headers['x-hmac'][0],
        ];


        if (evolutionCMS()->getLoginUserID() && isset($_GET['test'])) {
            $findRequest = [
                'checkId' => '0181-0456-1585-4812',
                'time' => '1613635480',
                'hmac' => 'QVoDqfwNHR2oEs+k7vQZzK2xOIOQwkrhdaTblq51o64=',
            ];

        }

        try {
            $statusCode = 200;
            $responseBody =  $this->CheckGovUaService->getInfoAboutCheck($findRequest);
        }
        catch (NotAuthorizeRequestException $e){
            $statusCode = 401;
            $responseBody =  [
                "errText" => "Запит не авторизований"
            ];
        }
        catch (CheckNotFoundException $e){
            $statusCode = 404;
            $responseBody =  [
                "errText" => "Квитанція не знайдена"
            ];
        } finally {
            evo()->logEvent(1,1,json_encode([
                'request'=>$request->toArray(),
                'headers'=>$request->headers->all(),
                'findRequest'=>$findRequest,
                'responseCode'=>$statusCode,
                'responseBody'=>$responseBody
            ]),'CheckGovUaRequest');
        }
        $response->setStatusCode($statusCode);
        return $responseBody;
    }
}