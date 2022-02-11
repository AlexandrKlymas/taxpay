<?php
namespace EvolutionCMS\Main\Controllers;


use DocumentParser;
use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\LiqPay\LiqPayService;
use Exception;
use Illuminate\Http\Request;
use function EvolutionCMS;

class LiqPayController
{
    /**
     * @var LiqPayService
     */
    private LiqPayService $liqPayService;
    private DocumentParser $evo;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->evo = EvolutionCMS();
        $this->evo->getDatabase()->connect();
        $this->liqPayService = new LiqPayService();
    }

    /**
     * @throws Exception
     */
    private function parseHandleRequest(Request $request)
    {
        if(!empty($request['data'])){
            $data = json_decode(base64_decode($request['data']),true);
            if(!empty($data['order_id'])){
                $serviceOrder = ServiceOrder::findByPaymentHash($data['order_id']);
                if(!empty($serviceOrder)){
                    return $serviceOrder;
                }else{
                    throw new Exception('not found serviceOrder by PayHash');
                }
            }else{
                throw new Exception('empty request order_id');
            }
        }else{
            throw new Exception('empty request [data]');
        }
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function serverHandle(Request $request)
    {
        try{
            $serviceOrder = $this->parseHandleRequest($request);
        } catch (Exception $e) {
            evo()->logEvent(1,3,''.PHP_EOL.json_encode($request->toArray()),
                'LiqPay ServerHandle '.$e->getMessage());
            die();
        }

        sleep(1); //TODO need queue for email sender (invoice email doubling)

        $this->liqPayService->handle($request);
    }

    public function handle(Request $request)
    {
        try {
            $serviceOrder = $this->parseHandleRequest($request);

            $paymentStatus = $this->liqPayService->handle($request);

            if($paymentStatus !== 'success'){
                throw new Exception('status = '.$paymentStatus??'');
            }

            if(empty($serviceOrder)){
                throw new Exception('service order not found');
            }

        }
        catch (Exception $e){
            $this->evo->logEvent(1,3,$e->getMessage() . ' | ' . base64_decode($request['data']),
                'LiqPay Handle ERROR');

            $redirect = UrlProcessor::makeUrl(124);

            if(!empty($serviceOrder)){
                $serviceFactory = ServiceFactory::makeFactoryForService($serviceOrder->service_id);
                $serviceFactory->getCallbacksService()->liqPayCallback([
                    'request'=>$request->toArray(),
                    'status'=>$paymentStatus??'empty status',
                ]);
            }

            if($request->get('controllerResponseType') !== 'json'){
                evo()->sendRedirect($redirect);
                die();
            }

            return [
                'redirect'=> $redirect
            ];
        }

        $serviceFactory = ServiceFactory::makeFactoryForService($serviceOrder->service_id);
        $serviceFactory->getCallbacksService()->liqPayCallback([
            'request'=>$request->toArray(),
            'status'=>$paymentStatus,
        ]);

        $redirect = UrlProcessor::makeUrl(122) . '?' . http_build_query(['order_hash' => $serviceOrder->order_hash]);

        if($request->get('controllerResponseType') !== 'json'){
            evo()->sendRedirect($redirect);
            die();
        }

        return [
            'redirect'=> $redirect
        ];
    }
}