<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands;

use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotHelper;
use TelegramBot\Api\BotApi;

class BaseCommand
{
    protected $botClient;
    protected $telegramBotUser;

    public function __construct(BotApi $botClient,TelegramBotUser $telegramBotUser)
    {

        $this->botClient = $botClient;
        $this->telegramBotUser = $telegramBotUser;
    }


    public function setCurrentCommand(){
        $this->telegramBotUser->setCommand(TelegramBotHelper::convertClassToCommand(get_called_class()));
    }

    public function runCommand($telegramBotRequest,$commandClass,$action){
        TelegramBotHelper::runCommand($this->botClient,$this->telegramBotUser,$telegramBotRequest,$commandClass,$action);
    }
}