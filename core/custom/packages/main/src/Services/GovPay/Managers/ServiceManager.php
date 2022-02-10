<?php

namespace EvolutionCMS\Main\Services\GovPay\Managers;

use EvolutionCMS\Main\Services\GovPay\Calculators\FinalPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IAfterConfirmExecutable;
use EvolutionCMS\Main\Services\GovPay\Factories\Calculators\CommissionsCalculatorFactory as CommissionsCalculatorFactoryAlias;
use EvolutionCMS\Main\Services\GovPay\Factories\Recipients\BankRecipientDtoFactory;
use EvolutionCMS\Main\Services\GovPay\Factories\Recipients\GovPayRecipientDtoFactory;
use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Models\Commission;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusReady;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSuccess;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class ServiceManager
{


    public function renderForm($serviceId)
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);

        return implode('', $serviceFactory->getFormConfigurator()->renderFormFields());
    }

    /**
     * @param $serviceId
     * @param $formData
     * @throws ValidationException
     */
    public function validate($serviceId, $formData)
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);
        $serviceFactory->getDataValidator()->validate($formData);
    }

    public function getCommission($serviceId)
    {
        $commission = [
            'total' => [

            ],
            'pension_fund' => [

            ]
        ];
        $serviceCommission = Commission::where('form_id', $serviceId)->limit(1)->first();
        if ($serviceCommission) {
            $commission['total'] = [
                "fix_summ" => $serviceCommission->fix_summ,
                "percent" => $serviceCommission->percent,
                "min_summ" => $serviceCommission->min_summ,
                "max_summ" => $serviceCommission->max_summ,
            ];
        }
        return $commission;
    }


    public function getDataForPreview($serviceId, $formData)
    {
        $this->validate($serviceId, $formData);


        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);
        $finalPaymentCalculator = new FinalPaymentCalculator($serviceId);

        $paymentRecipientGenerator = $serviceFactory->getPaymentRecipientsGenerator();
        $formConfigurator = $serviceFactory->getFormConfigurator();

        $formFieldsValues = $formConfigurator->getFormFieldsValues($formData);



        $recipients = $paymentRecipientGenerator->getPaymentRecipients($formFieldsValues);


        $previewFormData = $serviceFactory->getFormConfigurator()->renderDataForPreview($formFieldsValues);

        return [
            'recipient' => end($recipients),
            'formData' => $previewFormData,
            'amount' => $finalPaymentCalculator->calculate($formData)
        ];
    }

    public function createOder($serviceId, $formData): ServiceOrder
    {

        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);
        $finalPaymentCalculator = new FinalPaymentCalculator($serviceId);

        $formConfigurator = $serviceFactory->getFormConfigurator();
        $paymentRecipientGenerator = $serviceFactory->getPaymentRecipientsGenerator();
        $commissionsCalculator = CommissionsCalculatorFactoryAlias::build();

        $formFieldsValues = $formConfigurator->getFormFieldsValues($formData);

        $this->validate($serviceId, $formFieldsValues);

        $paymentRecipients = $paymentRecipientGenerator->getPaymentRecipients($formFieldsValues);
        $paymentAmountDto = $finalPaymentCalculator->calculate($formFieldsValues);

        $commissions = $commissionsCalculator->calculate($paymentAmountDto);


        $paymentRecipients[] = BankRecipientDtoFactory::build($commissions->getBankCommission(), $formFieldsValues);
        $paymentRecipients[] = GovPayRecipientDtoFactory::build($commissions->getProfit(), $formFieldsValues);


        $serviceOrder = ServiceOrder::new($serviceId, $formFieldsValues, $paymentAmountDto, $commissions);

        foreach ($paymentRecipients as $paymentRecipientDto) {
            PaymentRecipient::new($serviceOrder->id, $paymentRecipientDto);
        }
        return $serviceOrder;
    }


//    /**
//     * @param $paymentData
//     * @param $serviceOrderId
//     */

    public function updateLiqPayPaymentStatus($serviceOrderId, $paymentData)
    {
        $statusChanger = new StatusManager();
        $serviceOrder = ServiceOrder::findOrFail($serviceOrderId);

        evo()->logEvent(1,1,'','updateLiqPayPaymentStatus');
        if($statusChanger->canChange($paymentData['status'],$serviceOrder)){
            $statusChanger->change($paymentData['status'],$serviceOrder,$paymentData);
        }

        if($paymentData['status'] == StatusSuccess::getKey()){
            $this->generateInvoice($serviceOrderId);
        }

    }

    public function generateInvoice($serviceOrderId,$forced=false)
    {
        $serviceOrder = ServiceOrder::findOrFail($serviceOrderId);
        $serviceFactory = ServiceFactory::makeFactoryForService($serviceOrder->service_id);
        $invoiceHtml = $serviceFactory->getInvoiceGenerator()->generate($serviceOrder);
        $serviceOrder->invoice_file_html = $this->generateInvoiceHtmlFile($invoiceHtml, $serviceOrder->order_hash);

        if(!empty($serviceOrder->invoice_file_pdf) && !$forced){
            evo()->logEvent(1,1,$serviceOrder->invoice_file_pdf,'generateInvoice=inDB pdf already');
            return false;
        }

        try{
            $pdf = $this->generateInvoicePdfFile($invoiceHtml, $serviceOrder->order_hash);
        }catch (Exception $e){
            evo()->logEvent(1,3,json_encode($serviceOrder->toArray()),'ErrorInvoicePDF='.$serviceOrder->id);
        }

        $serviceOrder->invoice_file_pdf = $pdf??'';

        $serviceOrder->save();

        if(!$forced){
            evo()->invokeEvent('OnInvoicePDFGenerated', [
                'service_order'=>$serviceOrder,
            ]);
        }


        return false;
    }


    public function generateInvoiceHtmlFile($invoiceHtml, $orderHash)
    {
        $invoiceFileHtml = 'assets/files/invoices/' . $orderHash . '.html';
        File::put(MODX_BASE_PATH . $invoiceFileHtml, $invoiceHtml);
        return $invoiceFileHtml;
    }

    /**
     * @throws MpdfException
     * @throws Exception
     */
    public function generateInvoicePdfFile($invoiceHtml, $orderHash)
    {
        $invoiceFilePdf = 'assets/files/invoices/' . $orderHash . '.pdf';

        if(file_exists(MODX_BASE_PATH .$invoiceFilePdf)){
            evo()->logEvent(1,1,'','generateInvoice=file already exist');
        }

        $mpdf = new Mpdf([
            'tempDir' => EVO_STORAGE_PATH . 'mdf/'
        ]);

        try{
            $mpdf->WriteHTML($invoiceHtml);
            $mpdf->Output(MODX_BASE_PATH . $invoiceFilePdf);
        }catch (MpdfException $e){
            throw new Exception($e->getMessage());
        }

        return $invoiceFilePdf;
    }


    public function executePaidServiceOrders()
    {
        $confirmedServiceOrders = ServiceOrder::getPaidServiceOrders();

        foreach ($confirmedServiceOrders as $confirmedServiceOrder) {
            $this->executeServiceOrder($confirmedServiceOrder);
        }
    }

    public function completedServiceOrders()
    {
        $confirmedServiceOrders = ServiceOrder::getSubmittedServiceOrders();

        foreach ($confirmedServiceOrders as $confirmedServiceOrder) {

            try {
                $this->completedServiceOrder($confirmedServiceOrder);
            }
            catch (Exception $e){
                evo()->logEvent(1,3,$e->getMessage(),'completedServiceOrders');
            }
        }
    }
    public function completedServiceOrder(ServiceOrder $confirmedServiceOrder): void
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($confirmedServiceOrder->service_id);

        if ($serviceFactory instanceof IAfterConfirmExecutable && $serviceFactory->getExecutor()->isCompleted($confirmedServiceOrder) !== true) {
            return;
        }

        if ($confirmedServiceOrder->isAllPaymentsFinished() !== true) {
            return;
        }

        $confirmedServiceOrder->historyUpdate('Всі платежі проведено успішно');

        (new StatusManager())->change(StatusReady::getKey(),$confirmedServiceOrder);
    }

    public function executeServiceOrder(ServiceOrder $confirmedServiceOrder)
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($confirmedServiceOrder->service_id);

         if (!$serviceFactory instanceof IAfterConfirmExecutable) {
            return;
        }

        $executor = $serviceFactory->getExecutor();

        $confirmedServiceOrder->historyUpdate('Проведення платежів');
        $confirmedServiceOrder->save();
        try {
            $executor->execute($confirmedServiceOrder);
        } catch (Exception $e) {
            evo()->logEvent(985, 3, $e->getMessage(), 'ExecuteConfirmedServiceOrders');
        }
    }


}