<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\Department\PreviewServiceController;
use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxSignatureHelper;
use EvolutionCMS\Main\Services\GovPay\Support\StrHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\Exception;

class SudytaxPaymentController extends PreviewServiceController
{
    protected bool $autoCommissions = true;
    protected int $serviceId;

    /**
     * @throws Exception
     */
    public function sudytaxRoute(Request $request)
    {
        $this->serviceId = 162;
        $requestArr = $request->toArray();
        evo()->logEvent(1, 1, json_encode($requestArr), 'COURT Request ' . $requestArr['NAME'] ?? 'NoName');
        unset($requestArr['q']);


        $validator = Validator::make($requestArr,
            [
                'AMOUNT' => 'required',
                'SUM' => 'required',
                'COMMISSION' => 'required',
//                'currency'=>'required',
                'ORDER' => 'required',
                'NAME' => 'required',
//                'desc'=>'required',
//                'merch_name'=>'required',
//                'merch_url'=>'required',
//                'merchant'=>'required',
//                'terminal'=>'required',
//                'country'=>'required',
                'DETAILS' => 'required',
                'HOLDER' => 'required',
                'ACCOUNT' => 'required',
                'MFO' => 'required',
                'EDRPOU' => 'required',
                'DESCRIPTION' => 'required',
                'BANK_NAME' => 'required',
                'IBAN' => 'required',
//                'merch_gmt'=>'required',
                'TIMESTAMP' => 'required',
                'BACKREF' => 'required',
                'P_SIGN' => 'required',
            ]
        );

        $isValidSign = SudyTaxSignatureHelper::validSign($request);

        if (!$isValidSign || $validator->fails()) {
            evo()->logEvent(1, 3, json_encode([
                'request' => $request->toArray(),
                'errors' => [
                    'is_valid_sign' => $isValidSign,
                    'validator' => $validator->errors()->toArray(),
                ],
            ]), 'Ошибка в запросе COURT.GOV.UA');

            evo()->sendRedirect(UrlProcessor::makeUrl(123, '', '', 'full'));
        }

        $commission = ServiceFactory::makeFactoryForService($this->serviceId)
            ->getFinalCalculator()
            ->calculate(['sum'=>$requestArr['SUM']])
            ->getServiceFee();

        if($requestArr['COMMISSION']!=$commission){
            evo()->logEvent(1,3,json_encode($requestArr),'SudyCommmission Error');
        }

        if($this->autoCommissions){
            $requestArr['COMMISSION'] = $commission;
        }

        if ($requestArr['COMMISSION'] != $commission) {
            evo()->logEvent(1, 3, json_encode([
                'request' => $request->toArray(),
                'errors' => [
                    'commission' => $requestArr['COMMISSION'] . '!=' . $commission,
                ],
            ]), 'Ошибка комиссии в запросе от COURT.GOV.UA');

            evo()->sendRedirect(UrlProcessor::makeUrl(123, '', '', 'full'));
        }

        $newRequest = [
            'service_id' => $this->serviceId,

            'order' => $requestArr['ORDER'],
            'full_name' => StrHelper::namePrepare($requestArr['NAME']),
            'backref' => $requestArr['BACKREF'],

            'edrpou' => $requestArr['EDRPOU'],
            'iban' => $requestArr['IBAN'],
            'mfo' => $requestArr['MFO'],
            'sum' => $requestArr['SUM'],

            'holder' => $requestArr['HOLDER'],
            'bank_name' => $requestArr['BANK_NAME'],
            'details' => StrHelper::purposePrepare($requestArr['DETAILS']),
            'time' => time(),
        ];

        $validator = Validator::make($requestArr,
            [
                'EMAIL' => ['required','email'],
            ]
        );

        if(!$validator->fails()){
            $newRequest['email'] = $requestArr['EMAIL'];
        }

        evo()->sendRedirect(UrlProcessor::makeUrl($this->serviceId, '', '', 'full') . '?' . http_build_query($newRequest));
    }
}