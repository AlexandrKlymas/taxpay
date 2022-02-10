<?php

namespace EvolutionCMS\Main\Console\ServiceOrders;

use EvolutionCMS\Main\Models\CarForCheck;
use EvolutionCMS\Main\Services\FinesSearcher\Api\InfoTechFinesApi;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserCar;
use Illuminate\Console\Command;

class ParseFinesCommand extends Command
{
    protected $signature = 'parse:fines';

    protected $description = 'Parse fines';



    public function handle()
    {
        evo()->logEvent(656, 1, 'Начало парсинга штрафов', 'StartParseFinesCommand');
        $finesApi = new InfoTechFinesApi();

        $startDateTime = date('d.m.Y h:i:s', (time() - 7200));

        $data = $finesApi->getFinesByDate($startDateTime);
        foreach ($data as $car) {
            $carInBase = TelegramBotUserCar::query()->where('car_number', $car['LicensePlate'])->first();
            if(!is_null($carInBase)) {
                $hash = md5(json_encode($car));
                CarForCheck::query()->firstOrCreate(['hash'=>$hash], ['car_for_check_id'=>$carInBase->id]);
            }
        }

    }
}