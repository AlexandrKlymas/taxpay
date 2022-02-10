<?php
namespace EvolutionCMS\Main\Console\Bank;

use Illuminate\Console\Command;

class BankExportCommand extends Command
{
    protected $signature = 'bank:export';

    protected $description = 'Send transaction to bank';


    public function handle(){
        evo()->logEvent(123,1,'Начало выгрузки в банк, количество транзакция ','CRON BankExport');
        (new \EvolutionCMS\Main\Services\PaymentsToBankSender\PaymentsSender())->sendConfirmedPaymentsToBank();
    }
}