<?php

namespace EvolutionCMS\Main\Services\TelegramBot;

use EvolutionCMS\Main\Services\GovPay\Contracts\IFineSearchCommand;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserCar;
use TelegramBot\Api\BotApi;

class TelegramBotManager
{

    /**
     * @var \DocumentParser
     */
    private $evo;
    /**
     * @var BotApi
     */
    private $bot;

    public function __construct()
    {
        $this->evo = \EvolutionCMS();
        $this->bot = new BotApi($this->evo->getConfig('main_telegramBotToken'));
    }

    public function addCar(TelegramBotUser $telegramBotUser,IFineSearchCommand $command)
    {
//        $car = new TelegramBotUserCar([
//            'car_number'=>$command->getLicensePlate(),
//            'document_type'=>get_class($command),
//            'document_info'=>$command->toArray()
//        ]);
//        $telegramBotUser->cars()->save($car);
//
//
//
//
//        $this->bot->sendMessage(
//            $telegramBotUser->chat_id,
//            "🚙 Авто ".$command->getLicensePlate()." та 🆔".$command->getDocumentId()." додано в Ваш список. Відтепер ви отримуватимете повідомлення про нові постанови."
//        );
//
//        (new TelegramBotUserCarsFineSearcher())->searchFinesForCar($car);
//        (new TelegramBotUserFinesNotificationSender())->sendNotification();

    }
}