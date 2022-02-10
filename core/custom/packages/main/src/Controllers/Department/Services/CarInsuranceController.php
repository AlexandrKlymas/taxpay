<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;


use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\CarInsurance\CarInsuranceService;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteTmplvarContentvalue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CarInsuranceController extends BaseController
{
    public function render()
    {
        try {
            $this->data['serviceId'] = $this->evo->documentIdentifier;
            $this->data['content_builder'] = Helpers::multiFields(
                json_decode(
                    SiteTmplvarContentvalue::where('contentid', $this->evo->documentIdentifier)
                        ->where('tmplvarid', 2)
                        ->first()['value'], true));
            $this->data['form'] = $this->licensePlateForm();
            $this->data['commission'] = [];
        } catch (\Exception $e) {
            $this->data['error'] = 'Послуга в розробці';
        }
    }

    public function licensePlateForm()
    {
        return \View::make('partials.form.insurance.licensePlate',['content'=>$this->data['content_builder']])->toHtml();
    }

    public function getLicensePlateInfo(Request $request): JsonResponse
    {
        return Response::json((new CarInsuranceService())->getLicensePlateInfo($request));
    }

    public function getContract(Request $request): JsonResponse
    {
        return Response::json((new CarInsuranceService())->getContract($request));
    }

    public function sendOtp(Request $request): JsonResponse
    {
        return Response::json((new CarInsuranceService())->sendOtp($request));
    }

    public function checkOtp(Request $request): JsonResponse
    {
        return Response::json((new CarInsuranceService())->checkOtp($request));
    }

    public function getCity(Request $request): JsonResponse
    {
        return Response::json((new CarInsuranceService())->getCity($request->get('city')));
    }

    public function pay(): JsonResponse
    {
        return Response::json((new CarInsuranceService())->pay());
    }

    public function payCheck(Request $request)
    {
        (new CarInsuranceService())->payCheck($request);
    }

    public function payCallback(Request $request)
    {
        (new CarInsuranceService())->payCallback($request);
    }
    public function printContract(Request $request)
    {
        $link = (new CarInsuranceService())->printContract($request['contract_id']);
        evo()->sendRedirect($link);
    }
}