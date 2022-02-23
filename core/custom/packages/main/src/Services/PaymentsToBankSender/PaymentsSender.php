<?php

namespace EvolutionCMS\Main\Services\PaymentsToBankSender;

use DocumentParser;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Exceptions\FileNotPutException;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Repository\BankTKFtpRepository;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Repository\PaymentSenderExportFileRepository;
use Exception;
use function EvolutionCMS;

class PaymentsSender
{
    /**
     * @var PaymentSenderExportFileRepository
     */
    private PaymentSenderExportFileRepository $fileRepository;
    /**
     * @var BankTKFtpRepository
     */
    private BankTKFtpRepository $bankRepository;
    /**
     * @var DocumentParser
     */
    private DocumentParser $evo;

    public function __construct()
    {
        $this->fileRepository = new PaymentSenderExportFileRepository(time());
        $this->bankRepository = new BankTKFtpRepository();
        $this->evo = EvolutionCMS();
    }

    private array $transactionsFieldWithSizes = [
        'mfoA' => 9, // Код банка (МФО) А 1 – 9 (с 1 по 9 строку)
        'accountA' => 34, // Личный счет клиента банка А * 10 – 43
        'mfoB' => 9, // Код банка (МФО) банка Б 44 – 52
        'accountB' => 34, // Личный счет клиента банка Б * 53 – 86
        'debetcredit' => 1, // Флажок  "дебет/кредит" платежа 87 – 87
        'summ' => 16, // Сума платежа 88 – 103
        'doctype' => 2, // Вид документа 104 – 105
        'op_platezh_nomer' => 10, // Операционный номер платежа 106 – 115
        'kod_valuty' => 3, // Код валюты платежа 116 – 118
        'date_file' => 6, // Дата формирования платежного файла ГГММДД 119 – 124
        'date_platezh' => 6, // Дата осуществления платежа ГГММДД 125 – 130
        'nameA' => 38, // Название плательщика (клиента А) 131 – 168
        'nameB' => 38, // Название получателя (клиента Б) 169 – 206
        'naznachenie_platezh' => 160, // Назначение платежа* 207 – 366
        'add_rekvizit' => 60, // Дополнительные реквизиты  367 – 426
        'kod_naznachenie_platezh' => 3, // Код назначения платежа  427 – 429
        'bis' => 2, // Количество строк БИС (блока информационных строк) 430 – 431
        'idA' => 14, // Ідентифікатор клієнта А 432 – 445
        'idB' => 14 // Идентификатор клиента Б * 446 – 459
    ];

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws FileNotPutException
     * @throws Exception
     */
    public function sendConfirmedPaymentsToBank(): bool
    {
        $recipients = PaymentRecipient::getConfirmedPayments();

        evo()->logEvent(123, 1, 'Начало выгрузки в банк, количество транзакций ' . $recipients->count(), 'BankExport');

        if (empty($recipients->count())) {
            echo 'Нет транзакций';
            return true;
        }

        $transactions = $this->createTransactions($recipients);

        $transactionStrings = $this->convertTransactionsToStrings($transactions);

        $transactionFileName = $this->fileRepository->getTransactionFileName();

        $transactionFile = $this->fileRepository->createTransactionFile($transactionStrings);

        try {
            $this->bankRepository->sendFileToBank($transactionFile, "/IN/GOSPAY/"
                . $transactionFileName, true);

            foreach ($recipients as $recipient) {
                $recipient->changeStatusToFinished();
            }

            $this->fileRepository->archiveFile($transactionFileName);
            $this->evo->logEvent(610, 1, 'Файл ' . $transactionFileName
                . ' отправлен успешно', 'Файл ' . $transactionFileName . ' отправлен успешно');

        } catch (FileNotPutException $e) {
            $this->fileRepository->moveFileToBad($transactionFileName);
            $this->evo->logEvent(611, 2, 'Файл ' . $transactionFileName
                . ' не отправлен', 'Файл ' . $transactionFileName . ' не отправлен');
            throw $e;
        }

        return true;
    }

    private function createTransactions($recipients): array
    {
        $transactions = [];

        /** @var PaymentRecipient $recipient */
        foreach ($recipients as $recipient) {

            $sum = $recipient->amount * 100;

            $paymentDate = date('ymd');

            if (!empty($recipient->serviceOrder->liqpay_payment_date)) {
                $paymentDate = $recipient->serviceOrder->liqpay_payment_date->format('ymd');
            }

            $serviceOrderId = $recipient->serviceOrder->id;;

            $date = date('ymd');

            switch ($recipient->recipient_type) {
                case PaymentRecipient::RECIPIENT_TK_COMMISSION:
                    $operationNumber = 'KB-' . $serviceOrderId;
                    break;
                case PaymentRecipient::RECIPIENT_GOVPAY_PROFIT:
                    $operationNumber = 'KH-' . $serviceOrderId;
                    break;
                default:
                    $operationNumber = $serviceOrderId;
            }

            $transactions[] = [
                'mfoA' => '380106',
                'accountA' => 'UA653801060000000000029025013',
                'mfoB' => $recipient->mfo,
                'accountB' => $recipient->account,
                'debetcredit' => '1',
                'summ' => $sum,
                'doctype' => $recipient->isMainRecipient() ? 14 : 2,
                'op_platezh_nomer' => $operationNumber,
                'kod_valuty' => '980',
                'date_file' => $date,
                'date_platezh' => $paymentDate,
                'nameA' => str_pad(' ', $this->transactionsFieldWithSizes['nameA'], ' ', STR_PAD_LEFT),
                'nameB' => mb_substr($recipient->recipient_name, 0, $this->transactionsFieldWithSizes['nameB']),
                'naznachenie_platezh' => $recipient->purpose,
                'add_rekvizit' => '#u' . $recipient->serviceOrder->liqpay_transaction_id,
                'kod_naznachenie_platezh' => str_pad(' ', $this->transactionsFieldWithSizes['kod_naznachenie_platezh'], ' ', STR_PAD_LEFT),
                'bis' => '00',
                'idA' => str_pad(' ', $this->transactionsFieldWithSizes['idA'], " ", STR_PAD_LEFT),
                'idB' => $recipient->edrpou
            ];
        }

        return $transactions;
    }

    private function convertTransactionsToStrings(array $transactions): string
    {
        $strings = '';
        foreach ($transactions as $transaction) {
            $transaction = $this->addSpaceToTransactionFields($transaction);
            $strings .= iconv("UTF-8", "cp1251",
                    implode('', $transaction))
                . chr(13) . chr(10);
        }

        return $strings;
    }

    private function addSpaceToTransactionFields($transaction): array
    {
        //дописываем нужное количество пробелов спереди
        $tmp = [];

        foreach ($transaction as $k => $v) {
            if (isset($this->transactionsFieldWithSizes[$k])) {
                $tmp[$k] = $this->mdStrPad($v, $this->transactionsFieldWithSizes[$k], " ", STR_PAD_LEFT);
            }
        }

        $transaction = array_merge($transaction, $tmp);

        $realNameBLength = mb_strlen($transaction['nameB'], "UTF-8");
        $lengthDiff = $this->transactionsFieldWithSizes['nameB'] - $realNameBLength;
        if ($lengthDiff < 0) {

            $newNameALength = $this->transactionsFieldWithSizes['nameB'] - ($lengthDiff * -1);
            if ($newNameALength > 2) {
                $transaction['nameA'] = substr($transaction['nameA'], 0, $newNameALength);
            }
        }

        return $transaction;
    }

    private function mdStrPad(string $input, int $padLength, string $padString, string $padStyle, string $encoding = "UTF-8"): string
    {
        return str_pad($input, strlen($input) - mb_strlen($input, $encoding) + $padLength, $padString, $padStyle);
    }
}