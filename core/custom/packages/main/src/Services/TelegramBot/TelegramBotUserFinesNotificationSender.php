<?php

namespace EvolutionCMS\Main\Services\TelegramBot;

use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use EvolutionCMS\Main\Services\TelegramBot\Messages\FineMessage;
use EvolutionCMS\Main\Services\TelegramBot\Models\FineTelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;

class TelegramBotUserFinesNotificationSender
{

    private $evo;
    /**
     * @var FineMessage
     */
    private $message;


    public function __construct()
    {
        $this->evo = evolutionCMS();
        $this->message = new FineMessage();
    }

    public function sendNotification()
    {
        $start = microtime(true);
        $this->evo->logEvent(523, 1, 'Старт отправки уведомлений для телеграма', 'TelegramBotUserFinesNotificationSender');


        /** @var FineTelegramBotUser[] $telegramBotUserFines */
        $telegramBotUserFines = FineTelegramBotUser::with('fine', 'telegramBotUser')->where('notify_new', 0)->get();

        foreach ($telegramBotUserFines as $telegramBotUserFine) {

            $telegramBotUser = $telegramBotUserFine->telegramBotUser;
            $fine = $telegramBotUserFine->fine;


            try {
                $this->message->send($telegramBotUser, $fine);
                $telegramBotUserFine->notify_new = 1;

            } catch (\Exception $e) {
//                evo()->logEvent(523, 3, $e->getMessage(), 'TelegramBotUserFinesNotificationSender');
            }
            $telegramBotUserFine->save();
        }


        $time = round(microtime(true) - $start, 4);
        $this->evo->logEvent(523, 1, "Отправка уведомлений для телеграма завершена, время: $time с.", 'TelegramBotUserFinesNotificationSender');

    }

}