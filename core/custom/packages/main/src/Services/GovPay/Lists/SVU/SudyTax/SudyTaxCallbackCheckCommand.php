<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use EvolutionCMS\Main\Services\GovPay\Factories\ServiceFactory;
use Illuminate\Console\Command;

class SudyTaxCallbackCheckCommand extends Command
{
    protected $signature = 'sudytax:findcallbacks';

    protected $description = 'Check unsent callbacks';


    public function handle()
    {
        evo()->logEvent(1,1,'','Start search sudytax unsent callback');
        $serviceFactory = ServiceFactory::makeFactoryForService(162);
        $callbackService = $serviceFactory->getCallbacksService();
        $results = json_encode($callbackService->checkUnsentCallbacks()).PHP_EOL;
        evo()->logEvent(1,1,$results,'End search sudytax unsent callback');
        echo $results;
        die();
    }
}