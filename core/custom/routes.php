<?php

use EvolutionCMS\Main\Services\FinesSearcher\Api\FakeFinesApi;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserCar;
use GO\Scheduler;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::post('/ajax-form-callback', [\EvolutionCMS\Main\Controllers\Info\ContactsController::class, 'ajaxForm']);
Route::post('/get-license-plate-info', [\EvolutionCMS\Main\Controllers\Department\Services\CarInsuranceController::class, 'getLicensePlateInfo']);
Route::post('/get-contract', [\EvolutionCMS\Main\Controllers\Department\Services\CarInsuranceController::class, 'getContract']);
Route::post('/get-city', [\EvolutionCMS\Main\Controllers\Department\Services\CarInsuranceController::class, 'getCity']);
Route::post('/pay-osago', [\EvolutionCMS\Main\Controllers\Department\Services\CarInsuranceController::class, 'pay']);
Route::any('/pay-osago-check', [\EvolutionCMS\Main\Controllers\Department\Services\CarInsuranceController::class, 'payCheck']);
Route::any('/pay-osago-callback', [\EvolutionCMS\Main\Controllers\Department\Services\CarInsuranceController::class, 'payCallback']);
Route::any('/osago-print', [\EvolutionCMS\Main\Controllers\Department\Services\CarInsuranceController::class, 'printContract']);

Route::any('/service-validate', [\EvolutionCMS\Main\Controllers\Department\ServiceController::class, 'validate']);
Route::any('/service-preview', [\EvolutionCMS\Main\Controllers\Department\ServiceController::class, 'preview']);
Route::any('/service-order-payment-frame', [\EvolutionCMS\Main\Controllers\PaymentController::class, 'renderFrame']);

Route::any('/service-order-create-and-pay', [\EvolutionCMS\Main\Controllers\Department\ServiceController::class, 'createServiceOrderAndPay']);

Route::any('/service-order-send-invoice-to-email', [\EvolutionCMS\Main\Controllers\Department\ServiceOrderSuccessController::class, 'sendInvoiceToEmail']);

Route::any('/liqpay-result', [\EvolutionCMS\Main\Controllers\LiqPayController::class, 'handle']);
Route::any('/liqpay-server-request', [\EvolutionCMS\Main\Controllers\LiqPayController::class, 'serverHandle']);
Route::any('/telegram-bot-webhook', [\EvolutionCMS\Main\Controllers\TelegramBotController::class, 'handleWebhook']);
Route::any('/telegram-bot-webhook-plr', [\EvolutionCMS\Main\Controllers\TelegramBotController::class, 'handleWebhookPlr']);

Route::any('/check-gov-ua-endpoint', [\EvolutionCMS\Main\Controllers\CheckGovUaController::class, 'getInfoAboutCheck']);

Route::any('/services/fines/searchFines', [\EvolutionCMS\Main\Controllers\Department\Services\FinesController::class, 'searchFines']);

Route::post('/service-field-territorial-service-center-field', function (\Illuminate\Http\Request $request) {
    return \EvolutionCMS\Main\Services\GovPay\Fields\TerritorialServiceCenterField::getServices($request);
});

Route::post('/service-online-request-send-form', [\EvolutionCMS\Main\Controllers\Department\Services\OnlineRequestPoliceProtectionController::class, 'sendForm']);
Route::get('/search', [\EvolutionCMS\Main\Controllers\SearchController::class, 'search']);


Route::get('/bank-export', [\EvolutionCMS\Main\Controllers\BankController::class, 'export']);
Route::get('/bank-import', [\EvolutionCMS\Main\Controllers\BankController::class, 'import']);

Route::get('/service-finished', [\EvolutionCMS\Main\Controllers\Department\ServiceController::class,'finished']);

Route::get('/bot/search-fines', [\EvolutionCMS\Main\Controllers\TelegramBotController::class,'searchFinesForAllCar']);

Route::get('/gfs', [\EvolutionCMS\Main\Controllers\Department\Services\ECabinetDpsPaymentController::class,'gfsRoute']);
Route::post('/sudytax', [\EvolutionCMS\Main\Controllers\Department\Services\SudytaxPaymentController::class,'sudytaxRoute']);
Route::get('/paymentstests', [\EvolutionCMS\Main\Controllers\Department\Services\DebugPageController::class,'paymentstests']);

