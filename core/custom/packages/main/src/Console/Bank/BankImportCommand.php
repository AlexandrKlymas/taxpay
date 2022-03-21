<?php

namespace EvolutionCMS\Main\Console\Bank;

use EvolutionCMS\Main\Services\PaymentsToBankSender\ProcessedPaymentChecker;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\Exception;

class BankImportCommand extends Command
{
    protected $signature = 'bank:import {--day-type=}';

    protected $description = 'Get and check transaction from bank';

    /**
     * @throws Exception
     */
    public function handle(){

        $params = [];
        if(!empty($this->option('day-type'))){
            $params[] = $this->option('day-type');
        }

        evo()->logEvent(123,1,'Начало выгрузки в банк, количество транзакция ','CRON BankImport');

        (new ProcessedPaymentChecker(...$params))->getProcessedPaymentsAndCheck();
    }
}