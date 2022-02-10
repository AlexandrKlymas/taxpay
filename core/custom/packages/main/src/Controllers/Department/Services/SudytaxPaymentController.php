<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\GovPay\Exceptions\ServiceNotFoundException;
use EvolutionCMS\Main\Services\GovPay\Factories\Calculators\FeeCalculatorFactory;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxSignatureHelper;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Support\StrHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class SudytaxPaymentController extends BaseController
{
    private int $serviceId = 162;

    public function render()
    {

        $this->serviceId = $this->evo->documentIdentifier;

        try {
            $serviceProcessor = new ServiceManager();
            $this->data['serviceId'] = $this->serviceId;
            $this->data['preview'] = $this->preview(new Request($_GET))['preview'];
            $this->data['commission'] = $serviceProcessor->getCommission($this->serviceId);

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
        $data = array_merge($serviceProcessor->getDataForPreview($serviceId, $formData));

        $preview = View::make('partials.services.sudytax_preview')
            ->with($data)
            ->with(['service_id' => $serviceId])
            ->render();

        return [
            'status' => true,
            'preview' => $preview,
        ];
    }

    public function createServiceOrderAndPay(Request $request)
    {
    }

    public function finished()
    {
        if (!evo()->getLoginUserID()) {
            return;
        }

        $serviceProcessor = new ServiceManager();
        $serviceProcessor->executePaidServiceOrders();
        $serviceProcessor->completedServiceOrders();
    }

    public function sudytaxRoute(Request $request)
    {
        $requestArr = $request->toArray();
        evo()->logEvent(1, 1, json_encode($requestArr), 'COURT Request ' . $requestArr['NAME'] ?? 'NoName');
        unset($requestArr['q']);


        $validator = \Validator::make($requestArr,
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

        $commission = FeeCalculatorFactory::build($this->serviceId)->calculate($requestArr['SUM']);

        if ($requestArr['COMMISSION'] != $commission) {
            evo()->logEvent(1, 1, json_encode([
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

        $validator = \Validator::make($requestArr,
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