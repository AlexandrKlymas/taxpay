<?php

namespace EvolutionCMS\Main\Services\TelegramBot\Commands;

use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserCar;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;

class UnsubscribeCommand extends BaseCommand implements ICommand
{

    public function init(TelegramBotRequest $telegramBotRequest)
    {
        $carId = (int)$telegramBotRequest->getParams()['id'];

        $car = TelegramBotUserCar::find($carId);

        if ($car) {
            $carNumber = $car->car_number;
            $document = $car->document;
            $car->delete();
            $this->botClient->sendMessage($this->telegramBotUser->chat_id, "Скасовано підписку за номером транспортного засобу 🚙 $carNumber і документом 🆔 $document.");

        } else {
            $this->botClient->sendMessage($this->telegramBotUser->chat_id, "Не зміг розпізнати ідентифікатор необхідної підписки");

        }
    }
}