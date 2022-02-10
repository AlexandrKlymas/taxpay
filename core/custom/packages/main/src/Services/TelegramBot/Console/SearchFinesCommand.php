<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Console;


use EvolutionCMS\Main\Services\TelegramBot\TelegramBotUserCarsFineSearcher;
use GO\Scheduler;
use Illuminate\Console\Command;

class SearchFinesCommand extends Command
{
    protected $signature = 'bot:search-fines';

    protected $description = 'Search and update fines';


    public function handle()
    {
        (new TelegramBotUserCarsFineSearcher())->searchFinesForAllCar();
    }
}