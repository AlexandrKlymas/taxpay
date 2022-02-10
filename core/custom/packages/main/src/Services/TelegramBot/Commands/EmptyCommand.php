<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Commands;


use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

class EmptyCommand extends BaseCommand implements ICommand
{


    public function init(TelegramBotRequest $telegramBotRequest)
    {
        $this->botClient->sendMessage($this->telegramBotUser->chat_id,'Будь ласка, скористайтесь відповідною командою');
    }

}