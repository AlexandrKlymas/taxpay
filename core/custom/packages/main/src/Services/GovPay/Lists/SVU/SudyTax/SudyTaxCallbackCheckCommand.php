<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use Illuminate\Console\Command;

class SudyTaxCallbackCheckCommand extends Command
{
    protected $signature = 'sudytax:findcallbacks';

    protected $description = 'Check unsent callbacks';


    public function handle()
    {
        evo()->logEvent(1,1,'','Start search sudytax unsent callback');
        $callbackService = new SudyTaxCallbackService(162);
        $results = json_encode($callbackService->checkUnsentCallbacks()).PHP_EOL;
        evo()->logEvent(1,1,$results,'End search sudytax unsent callback');
        echo $results;
        die();
    }
}