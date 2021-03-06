<?php

namespace EvolutionCMS\Main\Services\PaymentsToBankSender;

use DocumentParser;
use EvolutionCMS\Main\Services\GovPay\Managers\StatusManager;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusQuestion;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSubmitted;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Repository\BankTKFtpRepository;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Repository\PaymentSenderImportFileRepository;
use PHPMailer\PHPMailer\Exception;

class ProcessedPaymentChecker
{
    /**
     * @var PaymentSenderImportFileRepository
     */
    private PaymentSenderImportFileRepository $fileRepository;
    /**
     * @var BankTKFtpRepository
     */
    private BankTKFtpRepository $bankRepository;
    /**
     * @var DocumentParser
     */
    private DocumentParser $evo;
    /**
     * @var StatusManager
     */
    private StatusManager $statusManager;

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

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function getProcessedPaymentsAndCheck()
    {
        $importFileName = $this->fileRepository->getImportFileName();
        $importFilePath = $this->fileRepository->getImportFilePath();

        $this->bankRepository->downloadFileFormBank($importFilePath, $importFileName);
        $bankProcessedPayments = $this->loadProcessedPaymentsFromFile($importFilePath);

        $errors = $this->checkProcessedPaymentsFromBank($bankProcessedPayments);

        if (!empty($errors)) {
            $this->evo->logEvent(1, 2, '???????? ' . $importFileName
                . ' ?????????????????? ?? ????????????????', '???????? ' . $importFileName . ' ?????????????????? ?? ????????????????');
            $this->fileRepository->moveFileToErrorFolder($importFileName);
            $this->fileRepository->writeErrorsToFile($importFileName, $errors);
        } else {
            $this->evo->logEvent(1, 1, '???????? ' . $importFileName
                . ' ?????????????????? ?????? ????????????', '???????? ' . $importFileName . ' ?????????????????? ?????? ????????????');
            $this->fileRepository->archiveFile($importFileName);
        }
    }

    /**
     * @throws \Exception
     */
    private function loadProcessedPaymentsFromFile($importFilePath): array
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

    /**
     * @throws \Exception
     */
    private function checkProcessedPaymentsFromBank(array $bankProcessedPayments): array
    {
        $errors = [];

        foreach ($bankProcessedPayments as $bankProcessedPaymentId => $bankProcessedPaymentSum) {
            /** @var ServiceOrder $serviceOrder */
            $serviceOrder = ServiceOrder::where('liqpay_transaction_id', $bankProcessedPaymentId)->first();

            if (empty($serviceOrder)) {
                $errors[] = $bankProcessedPaymentId . ';???? ???????????? ?????????? ????????????????????;';
                continue;
            }

            $serviceOrder->historyUpdate('?????????????????? ??????????????:'.$bankProcessedPaymentId.', ????????????');
            $bankProcessedPaymentSumCheckSum = round($bankProcessedPaymentSum, 2);
            $checkedSum = round($serviceOrder->total - $serviceOrder->liqpay_commission_auto_calculated, 2);

            if ($bankProcessedPaymentSumCheckSum === $checkedSum) {
                $status = StatusSubmitted::getKey();
                $serviceOrder->historyUpdate('?????????????????? ????????????????');
            } else {
                $status = StatusQuestion::getKey();
                $errors[] = $bankProcessedPaymentId . ';?????????? ???? ???????????????????? ???? ??????????????????;';
                $serviceOrder->historyUpdate('???? ?????????????????? ?????????? ????????????????????: ????????='
                    .$bankProcessedPaymentSumCheckSum.'; ????????='.$checkedSum.';');
            }

            if ($this->statusManager->canChange($status, $serviceOrder)) {
                $this->statusManager->change($status, $serviceOrder);
            }
        }

        return $errors;
    }
}