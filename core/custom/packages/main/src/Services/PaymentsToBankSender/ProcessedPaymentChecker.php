<?php

namespace EvolutionCMS\Main\Services\PaymentsToBankSender;


use EvolutionCMS\Main\Services\GovPay\Managers\StatusManager;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusQuestion;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSubmitted;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Repository\BankTKFtpRepository;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Repository\PaymentSenderImportFileRepository;

class ProcessedPaymentChecker
{

    /**
     * @var PaymentSenderImportFileRepository
     */
    private $fileRepository;
    /**
     * @var BankTKFtpRepository
     */
    private $bankRepository;
    /**
     * @var \DocumentParser
     */
    private $evo;
    /**
     * @var StatusManager
     */
    private $statusManager;

    public function __construct($dayType = '')
    {
        if ($dayType == 'yesterday') {
            $time = strtotime('-1 day');

        } else {
            $time = time();
        }


        $this->fileRepository = new PaymentSenderImportFileRepository($time);
        $this->bankRepository = new BankTKFtpRepository();
        $this->statusManager = new StatusManager();

        $this->evo = \EvolutionCMS();
    }

    public function getProcessedPaymentsAndCheck()
    {

        $importFileName = $this->fileRepository->getImportFileName();
        $importFilePath = $this->fileRepository->getImportFilePath();

        $this->bankRepository->downloadFileFormBank($importFilePath, $importFileName);
        $bankProcessedPayments = $this->loadProcessedPaymentsFromFile($importFilePath);


        $errors = $this->checkProcessedPaymentsFromBank($bankProcessedPayments);

        if (!empty($errors)) {
            $this->evo->logEvent(1, 2, 'Файл ' . $importFileName . ' обработан с ошибками', 'Файл ' . $importFileName . ' обработан с ошибками');
            $this->fileRepository->moveFileToErrorFolder($importFileName);
            $this->fileRepository->writeErrorsToFile($importFileName, $errors);

        } else {
            $this->evo->logEvent(1, 1, 'Файл ' . $importFileName . ' обработан без ошибок', 'Файл ' . $importFileName . ' обработан без ошибок');
            $this->fileRepository->archiveFile($importFileName);

        }
    }

    private function loadProcessedPaymentsFromFile($importFilePath)
    {
        $handle = @fopen(MODX_BASE_PATH . $importFilePath, "r");
        if (!$handle) {
            throw new \Exception("Can not open file");
        }
        $processedPayments = [];

        while (($buffer = fgets($handle, 4096)) !== false) {
            $tmp = explode(';', $buffer);
            $payment_id = $tmp[0];
            $sum = $tmp[1];
            $processedPayments[$payment_id] = $sum;
        }
        return $processedPayments;
    }

    private function checkProcessedPaymentsFromBank(array $bankProcessedPayments)
    {
        $errors = [];

        foreach ($bankProcessedPayments as $bankProcessedPaymentId => $bankProcessedPaymentSum) {


            /** @var ServiceOrder $serviceOrder */
            $serviceOrder = ServiceOrder::where('liqpay_transaction_id', $bankProcessedPaymentId)->first();

            if (empty($serviceOrder)) {
                $errors[] = $bankProcessedPaymentId . ';Не найден номер транзакции;';
                continue;
            }
            $serviceOrder->historyUpdate('Перевірка платежу:'.$bankProcessedPaymentId.', банком');
            $bankProcessedPaymentSumCheckSum = round($bankProcessedPaymentSum, 2);
            $checkedSum = round($serviceOrder->total - $serviceOrder->liqpay_commission_auto_calculated, 2);


            if ($bankProcessedPaymentSumCheckSum === $checkedSum) {
                $status = StatusSubmitted::getKey();
                $serviceOrder->historyUpdate('Перевірку пройдено');
            } else {
                $status = StatusQuestion::getKey();
                $errors[] = $bankProcessedPaymentId . ';Сумма по транзакции не совпадает;';
                $serviceOrder->historyUpdate('Не співпадає сумма транзакції: банк='.$bankProcessedPaymentSumCheckSum.'; база='.$checkedSum.';');
            }


            if ($this->statusManager->canChange($status, $serviceOrder)) {
                $this->statusManager->change($status, $serviceOrder);
            }

        }


        return $errors;


    }

}