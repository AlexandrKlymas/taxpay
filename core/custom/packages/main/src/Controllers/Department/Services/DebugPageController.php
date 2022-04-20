<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;


use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxCallbackService;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxSignatureHelper;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusError;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusFailure;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusReady;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSuccess;
use EvolutionCMS\Main\Services\GovPay\Support\SignHelper;
use Illuminate\Http\Request;


class DebugPageController
{
    public function paymentstests(Request $request)
    {
        $s = $request->get('s');

        switch ($s){
            case 'gfs': return $this->gfs($request);
            case 'sudy': return $this->sudyPay($request);
            default: echo ''; die();
        }
    }

    public function gfs(Request $request)
    {
        $requestArr = $request->toArray();
        unset($requestArr['q']);
        unset($requestArr['s']);

        if(empty($requestArr)){
            $requestArr = [
                'recipient'=>'ГОЛОВНЕ УДКСУ У КИЇВСЬКІЙ ОБЛАСТІ',
                'recipientcode'=>'37583261',
                'mfo'=>'899998',
                'bank'=>'Казначейство України(ел. адм. подат.)',
                'account'=>'UA308999980314010617000010015',
                'payername'=>'Климась Олександр Олександрович',
                'payercode'=>'2964118586',
                'paymentdetails'=>'*;101;2964118586;Спла`та 50 18010200 00;;;;',
                'phone'=>'3456345634563456',
                'email'=>'klimssalex@gmail.com',
                'budgetcode'=>'101',
                'amount'=>'0',
                'submit'=>'true',
                'sign'=>''
            ];
        }

        $mac = evo()->getConfig('g_sys_dpstax_mac');

        $signArr = $requestArr;
        unset($signArr['sign']);

        $sign = (new SignHelper())->makeSign($signArr,$mac);

        if(empty($requestArr['sign'])){
            $requestArr['sign'] = $sign;
        }

        return \View::make('department.services.tests', [
            'method'=>'GET',
            'source'=>'/gfs',
            'fields' => $requestArr,
            'sign' => $sign,
            'url'=>UrlProcessor::makeUrl(1,'','','full').'gfs?'.http_build_query($requestArr),
        ])->render();
    }

    public function sudyPay(Request $request)
    {
        $requestArr = $request->toArray();
        unset($requestArr['q']);
        unset($requestArr['s']);

        if(empty($requestArr)){
            $requestArr = [
                'AMOUNT' => '10.00',
                'SUM' => '1.00',
                'COMMISSION' => '9.00',
                'ORDER' => '32528798800004722221',
                'NAME' => "Климась Олександр Олександрович",
                'EMAIL'=>'klimssalex@gmail.com',
                'DETAILS' => " *;101;2289721670;\tСудов`ий збір, за позовом Мелашенко Олександр Юрійович, Харківський окружний адміністративний суд",
                'HOLDER' => 'Апеляційна палата Вищого антикорупційного суду',
                'ACCOUNT' => '313231003201096000',
                'MFO' => '820172',
                'EDRPOU' => '42836259',
                'DESCRIPTION' => 'Апеляційна палата Вищого антикорупційного суду',
                'BANK_NAME' => 'Державна казначейська служба України, м. Київ',
                'IBAN' => 'UA658201720313231003201096000',
                'TIMESTAMP' => '20220117132126',
                'BACKREF' => 'https://payment-test.court.gov.ua/govpay24_return.php?phash=1db02b3a7476472a81751cf2a1a519bdd50ae037af5dd640cec65a9e0b37fe40',
                'P_SIGN' => '',
            ];
        }

        $signArr = $requestArr;
        unset($signArr['P_SIGN']);

        $sign = SudyTaxSignatureHelper::makeSign($signArr);

        if(empty($requestArr['P_SIGN'])){
            $requestArr['P_SIGN'] = $sign;
        }

        return \View::make('department.services.tests', [
            'method'=>'POST',
            'source'=>'/sudytax',
            'fields' => $requestArr,
            'sign' => $sign,
            'url'=>'',
        ])->render();
    }

    public function sudyCallbacks(Request $request)
    {

        $service_id = 162;
        $dateFrom = '2022-01-01 00:00:00';
        $statuses = [
            'errors'=>[StatusError::getKey(),StatusFailure::getKey()],
            'success'=>[StatusReady::getKey(),StatusSuccess::getKey()],
        ];

        $serviceOrders = ServiceOrder::query()
            ->where(function($query) use ($dateFrom,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->where('created_at','>',$dateFrom);
                $query->where('status',StatusError::getKey());
            })
            ->orWhere(function($query) use ($dateFrom,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->where('created_at','>',$dateFrom);
                $query->where('status',StatusFailure::getKey());
            })
            ->orWhere(function($query) use ($dateFrom,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->where('created_at','>',$dateFrom);
                $query->where('status',StatusReady::getKey());
            })
            ->orWhere(function($query) use ($dateFrom,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->where('created_at','>',$dateFrom);
                $query->where('status',StatusSuccess::getKey());
            })
            ->get()
        ;

        $service = new SudyTaxCallbackService(162);

//        foreach($serviceOrders as $serviceOrder){
//            if(in_array($serviceOrder->status,$statuses['success'])){
//                $service->sendSuccessNotify($serviceOrder);
//                sleep(1);
//            }
//            if(in_array($serviceOrder->status, $statuses['errors'])){
//                $service->sendErrorNotify($serviceOrder);
//                sleep(1);
//            }
//        }
        dd($serviceOrders->count());
        return json_encode([]);
    }
}