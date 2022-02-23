<?php

namespace EvolutionCMS\Main\Controllers;

use EvolutionCMS\Main\Services\PaymentsToBankSender\Exceptions\FileNotPutException;
use EvolutionCMS\Main\Services\PaymentsToBankSender\PaymentsSender;
use EvolutionCMS\Main\Services\PaymentsToBankSender\ProcessedPaymentChecker;
use PHPMailer\PHPMailer\Exception;

class BankController
{
    public function __construct()
    {
        if (empty(evo()->getLoginUserID('mgr'))) {
            evo()->sendRedirect(13);
        }
    }

    /**
     * @throws Exception
     */
    public function import(): string
    {
        $params = [];
        if(isset($_GET['day-type'])){
            $params[] = $_GET['day-type'];
        }
        evo()->logEvent(123,1,'Начало выгрузки в банк, количество транзакция ','Ручной BankImport');
        (new ProcessedPaymentChecker(...$params))->getProcessedPaymentsAndCheck();
        return 'ok';
    }

    /**
     * @throws Exception
     * @throws FileNotPutException
     */
    public function export(): string
    {
        evo()->logEvent(123,1,'Начало выгрузки в банк, количество транзакция ','Ручной BankExport');
        (new PaymentsSender())->sendConfirmedPaymentsToBank();
        return 'ok';
    }
}