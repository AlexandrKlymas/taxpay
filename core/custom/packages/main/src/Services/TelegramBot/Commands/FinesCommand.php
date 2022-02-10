<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Commands;


use EvolutionCMS\Main\Services\TelegramBot\Messages\FineMessage;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserCar;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotUserFinesNotificationSender;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use TelegramBot\Api\BotApi;

class FinesCommand extends BaseCommand implements ICommand
{

    /**
     * @var FineMessage
     */
    private $message;

    public function __construct(BotApi $botClient, TelegramBotUser $telegramBotUser)
    {
        parent::__construct($botClient, $telegramBotUser);
        $this->message = new FineMessage();
    }

    public function init(TelegramBotRequest $telegramBotRequest)
    {
        /** @var TelegramBotUserCar[] $userCars */
        /** @var TelegramBotUser $telegramBotUser */
        $telegramBotUser = TelegramBotUser::with(['fines'=>function(BelongsToMany $q){
            $q->orderBy('d_perpetration');
        }])->findOrFail($this->telegramBotUser->id);


        if (count($telegramBotUser->fines) <= 0) {
            $this->botClient->sendMessage($telegramBotUser->chat_id, "У вас нема актуальних 📋 постанов");
        }

        foreach ($telegramBotUser->fines as $fine) {
            $this->message->send($telegramBotUser, $fine);

        }

    }
}