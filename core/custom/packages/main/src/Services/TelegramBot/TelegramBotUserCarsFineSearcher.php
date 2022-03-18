<?php

namespace EvolutionCMS\Main\Services\TelegramBot;

use DocumentParser;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFineSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\FinesSearcher;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserCar;
use Exception;
use Illuminate\Support\Carbon;

class TelegramBotUserCarsFineSearcher
{

    /**
     * @var FinesSearcher
     */
    private FinesSearcher $finesSearcher;
    /**
     * @var DocumentParser
     */
    private DocumentParser $evo;

    public function __construct()
    {
        $this->evo = evolutionCMS();
        $this->finesSearcher = new FinesSearcher();
    }

    public function searchFinesForAllCar()
    {

//        $start = microtime(true);
//        $this->evo->logEvent(523, 1, 'Начало проверки штрафов для авто из телеграма', 'TelegramBotUserCarsFineSearcher');
//
//        /** @var TelegramBotUserCar[] $cars */
//        $cars = TelegramBotUserCar::orderBy('checked_at')->limit(1000)->get();
//
//        foreach ($cars as $car) {
//
//
//            try {
//                $this->searchFinesForCar($car);
//            }
//            catch (Exception $e){
//                $this->evo->logEvent(523, 3, $e->getMessage(), 'TelegramBotUserCarsFineSearcher');
//            }
//        }
//        $time = round(microtime(true) - $start, 4);
//        $this->evo->logEvent(523, 1, "Проверка закончена, время: $time с.", 'TelegramBotUserCarsFineSearcher');

    }

    /**
     * @throws Exception
     */
    public function searchFinesForCar(TelegramBotUserCar $car)
    {
        $commandClass = $car->document_type;

        if(!class_exists($commandClass)){
            throw new Exception("Command $commandClass not found");
        }
        /** @var IFineSearchCommand $command */
        $command = $commandClass::fromArray($car->document_info);

        $telegramBotUser = $car->chat;
        $fines = $this->finesSearcher->searchFines($command);


        $fineIds =array_map(function (Fine $fine){ return $fine->id;},$fines);
        foreach ($fineIds as $fineId) {
            $telegramBotUser->fines()->syncWithoutDetaching($fineId);
        }

        $car->checked_at = Carbon::now();;
        $car->save();
    }
}