<?php

namespace EvolutionCMS\Main\Services\GovPay\Managers;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IAfterConfirmExecutable;
use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusReady;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSuccess;
use Exception;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class ServiceManager
{
    public function renderForm($serviceId): string
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);

        return implode('', $serviceFactory->getFormConfigurator()->renderFormFields());
    }

    /**
     * @param $serviceId
     * @param $formData
     */
    public function validate($serviceId, $formData)
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);
        $serviceFactory->getDataValidator()->validate($formData);
    }

    public function getCommission(int $serviceId, int $subServiceId = 0): array
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);

        return $serviceFactory->getCommissionsManager()->getCommissions($subServiceId);
    }

    public function renderPreview(int $serviceId, array $requestData): string
    {
        $this->validate($serviceId, $requestData);

        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);

        return $serviceFactory->getPreviewGenerator()->generatePreview($requestData);
    }

    public function createOder(int $serviceId, array $formData): ServiceOrder
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($serviceId);

        $serviceOrderGenerator = $serviceFactory->getOrderGenerator();

        return $serviceOrderGenerator->createOder($formData);
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
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

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function generateInvoice($serviceOrderId, $forced=false): bool
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
            evo()->logEvent(1,3,json_encode($serviceOrder->toArray()),
                'ErrorInvoicePDF='.$serviceOrder->id);
        }

        $serviceOrder->invoice_file_pdf = $pdf??'';

        $serviceOrder->save();

        if(!$forced){
            $serviceFactory->getCallbacksService()
                ->invoicePDFGenerated(['service_order'=>$serviceOrder,]);
        }

        return false;
    }

    public function generateInvoiceHtmlFile($invoiceHtml, $orderHash): string
    {
        $invoiceFileHtml = 'assets/files/invoices/' . $orderHash . '.html';
        File::put(MODX_BASE_PATH . $invoiceFileHtml, $invoiceHtml);

        return $invoiceFileHtml;
    }

    /**
     * @throws MpdfException
     * @throws Exception
     */
    public function generateInvoicePdfFile($invoiceHtml, $orderHash): string
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


    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function executePaidServiceOrders()
    {
        $confirmedServiceOrders = ServiceOrder::getPaidServiceOrders();

        foreach ($confirmedServiceOrders as $confirmedServiceOrder) {
            $this->executeServiceOrder($confirmedServiceOrder);
        }
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
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

    /**
     * @throws Exception
     */
    public function completedServiceOrder(ServiceOrder $confirmedServiceOrder): void
    {
        $serviceFactory = ServiceFactory::makeFactoryForService($confirmedServiceOrder->service_id);

        if ($serviceFactory instanceof IAfterConfirmExecutable
            && $serviceFactory->getExecutor()->isCompleted($confirmedServiceOrder) !== true) {
            return;
        }

        if ($confirmedServiceOrder->isAllPaymentsFinished() !== true) {
            return;
        }

        $confirmedServiceOrder->historyUpdate('Всі платежі проведено успішно');

        (new StatusManager())->change(StatusReady::getKey(),$confirmedServiceOrder);
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
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