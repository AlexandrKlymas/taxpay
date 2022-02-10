<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Support\SignHelper;
use EvolutionCMS\Main\Services\GovPay\Support\StrHelper;
use EvolutionCMS\Main\Services\LiqPay\LiqPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;


class ECabinetDpsPaymentController extends BaseController
{
    private bool $debugMode = false;
    public function render()
    {
        $serviceId = $this->evo->documentIdentifier;

        try {
            $serviceProcessor = new ServiceManager();
            $this->data['serviceId'] = $this->evo->documentIdentifier;
            $this->data['preview'] = $this->preview(new Request($_GET))['preview'];
            $this->data['commission'] = $serviceProcessor->getCommission($serviceId);

        } catch (ServiceNotFoundException $e) {
            $this->data['error'] = 'Послуга в розробці';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function validate(Request $request)
    {
        $serviceId = $request->get('service_id');

        $serviceProcessor = new ServiceManager();

        try {
            $serviceProcessor->validate($serviceId, $request->toArray());
        } catch (ValidationException $exception) {
            return [
                'status' => false,
                'errors' => $exception->errors()
            ];
        }

        return [
            'status' => true,
        ];
    }

    public function preview(Request $request)
    {
        $serviceId = $request->get('service_id');
        $formData = $request->toArray();

        $serviceProcessor = new ServiceManager();

        $data = array_merge(
            $serviceProcessor->getDataForPreview($serviceId, $formData),
            ['request'=>$formData]
        );
        $commissions = $serviceProcessor->getCommission($serviceId);
        $data['commissions']['percent'] = floatval($commissions['total']['percent']/100);
        $data['commissions']['min'] = floatval($commissions['total']['min_summ']);
        $data['commissions'] = json_encode($data['commissions']);
        unset($data['request']['q']);

        $preview = View::make('partials.services.dps_preview')->with($data)->render();

        return [
            'status' => true,
            'preview' => $preview,
        ];
    }

    public function createServiceOrderAndPay(Request $request)
    {
    }

    public function finished(){
        if(!evo()->getLoginUserID()){
            return;
        }

        $serviceProcessor = new ServiceManager();
        $serviceProcessor->executePaidServiceOrders();
        $serviceProcessor->completedServiceOrders();
    }
    public function gfsRoute(Request $request)
    {
        $requestArr = $request->toArray();

        evo()->logEvent(1,1, json_encode($requestArr),'TAX.GOV.UA>'.$requestArr['payername']??'noname');

        unset($requestArr['q']);

        if($this->debugMode){
            dump('Вхідний запит',$requestArr);
        }

        $validator = \Validator::make($requestArr,
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

            if($this->debugMode){
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
            if($this->debugMode){
                echo "<script>document.querySelector('body').style.backgroundColor = 'black';</script>";
                die();
            }

            evo()->sendRedirect(UrlProcessor::makeUrl(123,'','','full'));
        }

        $newRequest = [
            'service_id'=>165,
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

        evo()->sendRedirect(UrlProcessor::makeUrl(165,'','','full').'?'.http_build_query($newRequest));

    }
}