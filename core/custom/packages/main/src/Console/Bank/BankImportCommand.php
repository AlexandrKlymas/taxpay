<?php
namespace EvolutionCMS\Main\Console\Bank;

use Illuminate\Console\Command;

class BankImportCommand extends Command
{
    protected $signature = 'bank:import {--day-type=}';

    protected $description = 'Get and check transaction from bank';


    public function handle(){


        $params = [];
        if(!empty($this->option('day-type'))){
            $params[] = $this->option('day-type');
        }

        evo()->logEvent(123,1,'Начало выгрузки в банк, количество транзакция ','CRON BankImport');

        (new \EvolutionCMS\Main\Services\PaymentsToBankSender\ProcessedPaymentChecker(...$params))->getProcessedPaymentsAndCheck();
    }
}