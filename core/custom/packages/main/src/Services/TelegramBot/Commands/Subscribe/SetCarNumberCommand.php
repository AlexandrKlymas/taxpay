<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe;


use EvolutionCMS\Main\Services\TelegramBot\Commands\BaseCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ICommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\IExecutableCommand;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;

class SetCarNumberCommand extends BaseCommand implements ICommand, IExecutableCommand
{

    public function init(TelegramBotRequest $telegramBotRequest)
    {
        $this->botClient->sendMessage($this->telegramBotUser->chat_id,"Вкажіть державний номерний знак транспортного засобу 🚙 Наприклад, АА0000ЇЇ");
        $this->setCurrentCommand();;
    }

    public function execute(TelegramBotRequest $telegramBotRequest)
    {
        $carNumber = $telegramBotRequest->getMessage();
        $this->telegramBotUser->setTempData('car_number',$carNumber);


        $this->runCommand($telegramBotRequest,SetDocumentTypeCommand::class,'init');
    }
}