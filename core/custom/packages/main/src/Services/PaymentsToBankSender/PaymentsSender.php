<?php

namespace EvolutionCMS\Main\Services\PaymentsToBankSender;


use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Exceptions\FileNotPutException;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Repository\BankTKFtpRepository;
use EvolutionCMS\Main\Services\PaymentsToBankSender\Repository\PaymentSenderExportFileRepository;

class PaymentsSender
{

    /**
     * @var PaymentSenderExportFileRepository
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

    public function __construct()
    {
        $this->fileRepository = new PaymentSenderExportFileRepository(time());
        $this->bankRepository = new BankTKFtpRepository();
        $this->evo = \EvolutionCMS();
    }

    private $transactionsFieldWithSizes = [
        'mfoA' => 9, // Код банка (МФО) А
        'accountA' => 34, // Личный счет клиента банка А *
        'mfoB' => 9, // Код банка (МФО) банка Б
        'accountB' => 34, // Личный счет клиента банка Б *
        'debetcredit' => 1, // Флажок  "дебет/кредит" платежа
        'summ' => 16, // Сума платежа
        'doctype' => 2, // Вид документа
        'op_platezh_nomer' => 10, // Операционный номер платежа
        'kod_valuty' => 3, // Код валюты платежа
        'date_file' => 6, // Дата формирования платежного файла ГГММДД
        'date_platezh' => 6, // Дата осуществления платежа ГГММДД
        'nameA' => 38, // Название плательщика (клиента А)
        'nameB' => 38, // Название получателя (клиента Б)
        'naznachenie_platezh' => 160, // Назначение платежа*
        'add_rekvizit' => 60, // Дополнительные реквизиты
        'kod_naznachenie_platezh' => 3, // Код назначения платежа
        'bis' => 2, // Количество строк БИС (блока информационных строк)
        'idA' => 14, // Ідентифікатор клієнта А
        'idB' => 14 // Идентификатор клиента Б *
    ];


    public function sendConfirmedPaymentsToBank()
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
            $this->bankRepository->sendFileToBank($transactionFile, "/IN/GOSPAY/" . $transactionFileName, true);

            foreach ($recipients as $recipient) {
                $recipient->changeStatusToFinished();
            }

            $this->fileRepository->archiveFile($transactionFileName);
            $this->evo->logEvent(610, 1, 'Файл ' . $transactionFileName . ' отправлен успешно', 'Файл ' . $transactionFileName . ' отправлен успешно');

        } catch (FileNotPutException $e) {
            $this->fileRepository->moveFileToBad($transactionFileName);
            $this->evo->logEvent(611, 2, 'Файл ' . $transactionFileName . ' не отправлен', 'Файл ' . $transactionFileName . ' не отправлен');
            throw $e;
        }

        return true;
    }

    private function createTransactions($recipients)
    {
        $transactions = [];


        /** @var PaymentRecipient $recipient */
        foreach ($recipients as $recipient) {

            $sum = $recipient->amount * 100;

            $datePlatezh = $recipient->serviceOrder->liqpay_payment_date ? $recipient->serviceOrder->liqpay_payment_date->format('ymd') : date('ymd');


            $serviceOrderId = $recipient->serviceOrder->id;;
            $date = date('ymd');


            switch ($recipient->recipient_type) {
                case PaymentRecipient::RECIPIENT_TK_COMMISSION:
                    $opPlatezhNomer = 'KB-' . $serviceOrderId;
                    break;
                case PaymentRecipient::RECIPIENT_GOVPAY_PROFIT:
                    $opPlatezhNomer = 'KH-' . $serviceOrderId;
                    break;
                default:
                    $opPlatezhNomer = $serviceOrderId;
            }


            $transactions[] = [
                'mfoA' => '380106',
                'accountA' => 'UA653801060000000000029025013',
                'mfoB' => $recipient->mfo,
                'accountB' => $recipient->account,
                'debetcredit' => '1',
                'summ' => $sum,
                'doctype' => $recipient->isMainRecipient() ? 14 : 2,
                'op_platezh_nomer' => $opPlatezhNomer,
                'kod_valuty' => '980',
                'date_file' => $date,
                'date_platezh' => $datePlatezh,
                'nameA' => str_pad(' ', 38, " ", STR_PAD_LEFT),
                'nameB' => $recipient->recipient_name,
                'naznachenie_platezh' => $recipient->purpose,
                'add_rekvizit' => '#u' . $recipient->serviceOrder->liqpay_transaction_id,
                'kod_naznachenie_platezh' => str_pad(' ', 3, " ", STR_PAD_LEFT),
                'bis' => '00',
                'idA' => str_pad(' ', 14, " ", STR_PAD_LEFT),
                'idB' => $recipient->edrpou
            ];
        }


        return $transactions;
    }

    private function convertTransactionsToStrings(array $transactions)
    {
        $strings = '';
        foreach ($transactions as $transaction) {
            $transaction = $this->addSpaceToTransactioFields($transaction);
            $strings .= iconv("UTF-8", "cp1251",
                    implode('', $transaction))
                . chr(13) . chr(10);
        }

        return $strings;
    }

    private function addSpaceToTransactioFields($transaction)
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
        if ($lengthDiff<0) {

            $newNameALength = $this->transactionsFieldWithSizes['nameB']-($lengthDiff*-1);
            if($newNameALength>2){
                $transaction['nameA'] = substr($transaction['nameA'],0,$newNameALength);
            }
        }

        return $transaction;
    }

    private function mdStrPad($input, $pad_length, $pad_string, $pad_style, $encoding = "UTF-8")
    {
        $out = str_pad($input, strlen($input) - mb_strlen($input, $encoding) + $pad_length, $pad_string, $pad_style);
        return $out;
    }
}