<?php

namespace EvolutionCMS\Main\Controllers;


class BankController
{
    public function __construct()
    {
        if (empty(evo()->getLoginUserID('mgr'))) {
            evo()->sendRedirect(13);
        }
    }


    public function import()
    {
        $params = [];
        if(isset($_GET['day-type'])){
            $params[] = $_GET['day-type'];
        }
        evo()->logEvent(123,1,'Начало выгрузки в банк, количество транзакция ','Ручной BankImport');
        (new \EvolutionCMS\Main\Services\PaymentsToBankSender\ProcessedPaymentChecker(...$params))->getProcessedPaymentsAndCheck();
        return 'ok';
    }

    public function export()
    {
        evo()->logEvent(123,1,'Начало выгрузки в банк, количество транзакция ','Ручной BankExport');
        (new \EvolutionCMS\Main\Services\PaymentsToBankSender\PaymentsSender())->sendConfirmedPaymentsToBank();
        return 'ok';
    }
}