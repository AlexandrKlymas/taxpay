<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents;

use EvolutionCMS\Main\Services\FinesSearcher\Documents;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByUkrainePassportSearchCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\BaseCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ICommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\IExecutableCommand;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotManager;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use TelegramBot\Api\BotApi;

class SetUkrainePassportCommand extends BaseCommand implements ICommand, IExecutableCommand
{

    /**
     * @var TelegramBotManager
     */
    private $botManager;


    public function __construct(BotApi $botClient, TelegramBotUser $telegramBotUser)
    {
        parent::__construct($botClient, $telegramBotUser);
        $this->botManager = new TelegramBotManager();
    }

    public function init(TelegramBotRequest $telegramBotRequest)
    {
        $this->botClient->sendMessage($this->telegramBotUser->chat_id,"Введіть серію та номер паспорту. Наприклад, КВ123456");
        $this->setCurrentCommand();
    }

    public function execute(TelegramBotRequest $telegramBotRequest)
    {
        $passport = $telegramBotRequest->getMessage();
        $tempData = $this->telegramBotUser->temp_data;

        try {
            $parsedPassport = Documents::parseUkrainePassportString($passport);

            $command = new ByUkrainePassportSearchCommand($parsedPassport['series'],$parsedPassport['number'],$tempData['car_number']);
            $this->botManager->addCar($this->telegramBotUser,$command);
        }
        catch (\Exception $e){
            $this->botClient->sendMessage($this->telegramBotUser->chat_id, "Невірний формат");
            $this->setCurrentCommand();
        }
    }
}