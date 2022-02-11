<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;


use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\Department\PreviewServiceController;
use EvolutionCMS\Main\Controllers\Department\ServiceController;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Support\SignHelper;
use EvolutionCMS\Main\Services\GovPay\Support\StrHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\Exception;

class ECabinetDpsPaymentController extends PreviewServiceController
{
    private bool $publicDebugMode = false;
    private bool $debugLog = true;

    /**
     * @throws Exception
     */
    public function gfsRoute(Request $request)
    {
        $serviceId = 165;
        $requestArr = $request->toArray();

        if($this->debugLog){
            evo()->logEvent(1,1, json_encode($requestArr),'TAX.GOV.UA>'.$requestArr['payername']??'noname');
        }

        unset($requestArr['q']);

        if($this->publicDebugMode){
            dump('Вхідний запит',$requestArr);
        }

        $validator = Validator::make($requestArr,
            [
                'recipient'=>'required',
                'recipientcode'=>'required',
                'mfo'=>'required',
                'bank'=>'required',
                'account'=>'required',
                'payername'=>'required',
                'payercode'=>'required',
                'paymentdetails'=>'required',
                'budgetcode'=>'required',
                'amount'=>'required',
                'submit'=>'required',
            ]
        );

        $isValidSign = (new SignHelper(true))->validSign($requestArr, evo()->getConfig('g_sys_dpstax_mac'));

        if ($validator->fails() || !$isValidSign) {

            if($this->publicDebugMode){
                dump(
                    'Виявлені помилки',
                    $validator->errors()->toArray(),
                    'Валідність підпису',
                    $isValidSign,
                );
            }

            evo()->logEvent(1,3, json_encode([
                'errors'=>$validator->errors()->toArray(),
                'is_valid_sign'=>$isValidSign,
                'request'=>$requestArr,
            ]),'TAX.GOV.UA Request Error');
            if($this->publicDebugMode){
                echo "<script>document.querySelector('body').style.backgroundColor = 'black';</script>";
                die();
            }

            evo()->sendRedirect(UrlProcessor::makeUrl(123,'','','full'));
        }

        $newRequest = [
            'service_id'=>$serviceId,
            'recipient_name'=>StrHelper::namePrepare($requestArr['recipient']),
            'bank_edrpou'=>$requestArr['recipientcode'],
            'mfo'=>$requestArr['mfo'],
            'bank_name'=>$requestArr['bank'],
            'bank_account'=>$requestArr['account'],
            'full_name'=>StrHelper::namePrepare($requestArr['payername']),
            'payercode'=>$requestArr['payercode'],
            'purpose'=>StrHelper::purposePrepare($requestArr['paymentdetails']),
            'phone'=>$requestArr['phone']??'',
            'email'=>$requestArr['email']??'',
            'budgetcode'=>$requestArr['budgetcode'],
            'sum'=>$requestArr['amount'],
        ];

        evo()->sendRedirect(UrlProcessor::makeUrl($serviceId,'','','full').'?'.http_build_query($newRequest));

    }
}