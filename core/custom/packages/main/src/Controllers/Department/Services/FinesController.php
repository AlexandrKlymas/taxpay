<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;

use Carbon\Carbon;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\FineSearchLog\Models\FineSearchLog;
use EvolutionCMS\Main\Services\FinesSearcher\Documents;
use EvolutionCMS\Main\Services\FinesSearcher\FinesSearcher;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByDrivingLicenseSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByFineSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByTaxNumberSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByTechPassportSearchCommand;
use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use EvolutionCMS\Main\Support\Helpers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FinesController extends BaseController
{


    public function render()
    {
        $renderMode = 'searchForm';


        if (isset($_GET['fine'])) {
            $renderMode = 'showFine';

            try {
                $fine = (new FinesSearcher())->findFineById((int)$_GET['fine']);
                $this->data['fines'] = \View::make('partials.services.list.fines.search_result')->with('fines', [$fine])->render();
            } catch (\Exception $e) {
                $this->data['showFineError'] = 'Не вдалось знайти штраф';
            }
        }

        if ((isset($_GET['pay-fine']))) {
            $renderMode = 'payFine';
            $fineId = (int)$_GET['pay-fine'];

            try {
                (new FinesSearcher())->updateFine($fineId);

                $serviceFactory = ServiceFactory::makeFactoryForService(47);
                $this->data['fineId'] = $fineId;
                $previewGenerator = $serviceFactory->getPreviewGenerator();
                $this->data['finePreview'] = $previewGenerator->generatePreview(['fine_id' => $fineId,]);
            } catch (ValidationException $e) {
                $this->data['previewFineError'] = implode('<br>', array_map(function ($fieldErrors) {
                    return implode(',', $fieldErrors);
                }, $e->errors()));
            } catch (\Exception $e) {
                $this->data['previewFineError'] = 'Не вдалось знайти штраф';
            }
        }

        $this->data['renderMode'] = $renderMode;
    }

    public function searchFines(Request $request)
    {
        if(isset($_GET['test'])){
            $request = new Request([
                'license_plate' => 'АА0100ZC',
                'tax_number' => '',
                'tech_passport' => '',
                'driving_license' => '',
                'driving_license_date_issue' => '',
                'fine_series' => '1АВ',
                'fine_number' => '1546507'
            ]);

        }

        $this->logFineSearchRequest($request);


        try {
            $fineSearchRequest = $this->getFineSearchCommandFromHttpRequest($request);
            $finesList = (new FinesSearcher())->searchFines($fineSearchRequest);

            $result = [
                'status' => true,
            ];
            if ($finesList) {
                $result['fines'] = \View::make('partials.services.list.fines.search_result')->with('fines', $finesList)->render();
            } else {
                $result['error'] = 'штрафів не знайдено';
            }
        } catch (\Exception $e) {

            $result = [
                'status' => false,
                'error' => 'Сталась помилка',
            ];
        }
        return $result;
    }

    private function getFineSearchCommandFromHttpRequest(Request $request)
    {
        $documentType = $this->getDocumentType($request);


        switch ($documentType) {
            case 'fine':
                $request = new ByFineSearchCommand($request->get('fine_series'), $request->get('fine_number'), $request->get('license_plate'));
                break;
            case 'taxNumber':
                $request = new ByTaxNumberSearchCommand($request->get('tax_number'), $request->get('license_plate'));
                break;
            case 'techPassport':
                $techPassport = Documents::parseTechPassportString($request->get('tech_passport'));
                $request = new ByTechPassportSearchCommand($techPassport['series'], $techPassport['number'], $request->get('license_plate'));
                break;
            case 'drivingLicense':
                $parsedDrivingLicense = Documents::parseDrivingLicenseString($request->get('driving_license'));
                $request = new ByDrivingLicenseSearchCommand($parsedDrivingLicense['series'], $parsedDrivingLicense['number'], $request->get('driving_license_date_issue'), $request->get('license_plate'));
                break;
            default:
                throw new \Exception('Method not found');
                break;
        }


        return $request;
    }

    private function getDocumentType(Request $request)
    {
        $documentType = 'fine';
        if ($request->get('tax_number')) {
            $documentType = 'taxNumber';
        } else if ($request->get('tech_passport')) {
            $documentType = 'techPassport';
        } else if ($request->get('driving_license')) {
            $documentType = 'drivingLicense';
        }

        return $documentType;
    }

    private function logFineSearchRequest(Request $request)
    {


        $clearedRequest = Helpers::e($request->toArray());
        FineSearchLog::create([
            'license_plate'=>$clearedRequest['license_plate'],
            'tax_number'=>$clearedRequest['tax_number'],
            'tech_passport'=>$clearedRequest['tech_passport'],
            'driving_license'=>$clearedRequest['driving_license'],
            'driving_license_date_issue'=>!empty($clearedRequest['driving_license_date_issue'])?Carbon::createFromDate($clearedRequest['driving_license_date_issue']):null,
            'fine_series'=>$clearedRequest['fine_series'],
            'fine_number'=>$clearedRequest['fine_number'],
        ]);
    }


}