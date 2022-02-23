<?php

namespace EvolutionCMS\Main\Console\Bank;

use EvolutionCMS\Main\Services\PaymentsToBankSender\Exceptions\FileNotPutException;
use EvolutionCMS\Main\Services\PaymentsToBankSender\PaymentsSender;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\Exception;

class BankExportCommand extends Command
{
    protected $signature = 'bank:export';

    protected $description = 'Send transaction to bank';

    /**
     * @throws Exception
     * @throws FileNotPutException
     */
    public function handle(){
        evo()->logEvent(123,1,'Начало выгрузки в банк, количество транзакция ','CRON BankExport');
        (new PaymentsSender())->sendConfirmedPaymentsToBank();
    }
}