<?php

use EvolutionCMS\Main\Controllers\BankController;
use EvolutionCMS\Main\Controllers\CheckGovUaController;
use EvolutionCMS\Main\Controllers\Department\ServiceController;
use EvolutionCMS\Main\Controllers\Department\ServiceOrderSuccessController;
use EvolutionCMS\Main\Controllers\Department\Services\CarInsuranceController;
use EvolutionCMS\Main\Controllers\Department\Services\DebugPageController;
use EvolutionCMS\Main\Controllers\Department\Services\ECabinetDpsPaymentController;
use EvolutionCMS\Main\Controllers\Department\Services\FinesController;
use EvolutionCMS\Main\Controllers\Department\Services\OnlineRequestPoliceProtectionController;
use EvolutionCMS\Main\Controllers\Department\Services\SudytaxPaymentController;
use EvolutionCMS\Main\Controllers\Info\ContactsController;
use EvolutionCMS\Main\Controllers\LiqPayController;
use EvolutionCMS\Main\Controllers\PaymentController;
use EvolutionCMS\Main\Controllers\SearchController;
use EvolutionCMS\Main\Controllers\TelegramBotController;
use EvolutionCMS\Main\Services\GovPay\Fields\TerritorialServiceCenterField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/ajax-form-callback', [ContactsController::class, 'ajaxForm']);
Route::post('/get-license-plate-info', [CarInsuranceController::class, 'getLicensePlateInfo']);
Route::post('/get-contract', [CarInsuranceController::class, 'getContract']);
Route::post('/get-city', [CarInsuranceController::class, 'getCity']);
Route::post('/pay-osago', [CarInsuranceController::class, 'pay']);
Route::any('/pay-osago-check', [CarInsuranceController::class, 'payCheck']);
Route::any('/pay-osago-callback', [CarInsuranceController::class, 'payCallback']);
Route::any('/osago-print', [CarInsuranceController::class, 'printContract']);

Route::any('/service-validate', [ServiceController::class, 'validate']);
Route::any('/service-preview', [ServiceController::class, 'preview']);
Route::any('/service-order-payment-frame', [PaymentController::class, 'renderFrame']);

Route::any('/service-order-create-and-pay', [ServiceController::class, 'createServiceOrderAndPay']);

Route::any('/service-order-send-invoice-to-email', [ServiceOrderSuccessController::class, 'sendInvoiceToEmail']);

Route::any('/liqpay-result', [LiqPayController::class, 'handle']);
Route::any('/liqpay-server-request', [LiqPayController::class, 'serverHandle']);
Route::any('/telegram-bot-webhook', [TelegramBotController::class, 'handleWebhook']);
Route::any('/telegram-bot-webhook-plr', [TelegramBotController::class, 'handleWebhookPlr']);

Route::any('/check-gov-ua-endpoint', [CheckGovUaController::class, 'getInfoAboutCheck']);

Route::any('/services/fines/searchFines', [FinesController::class, 'searchFines']);

Route::post('/service-field-territorial-service-center-field', function (Request $request) {
    return TerritorialServiceCenterField::getServices($request);
});

Route::post('/service-online-request-send-form', [OnlineRequestPoliceProtectionController::class, 'sendForm']);
Route::get('/search', [SearchController::class, 'search']);


Route::get('/bank-export', [BankController::class, 'export']);
Route::get('/bank-import', [BankController::class, 'import']);

Route::get('/service-finished', [ServiceController::class,'finished']);

Route::get('/bot/search-fines', [TelegramBotController::class,'searchFinesForAllCar']);

Route::get('/gfs', [ECabinetDpsPaymentController::class,'gfsRoute']);
Route::post('/sudytax', [SudytaxPaymentController::class,'sudytaxRoute']);
Route::get('/paymentstests', [DebugPageController::class,'paymentstests']);

