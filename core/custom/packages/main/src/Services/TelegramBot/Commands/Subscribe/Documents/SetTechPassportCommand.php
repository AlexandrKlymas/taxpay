<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents;

use EvolutionCMS\Main\Services\FinesSearcher\Documents;
use EvolutionCMS\Main\Services\FinesSearcher\Exceptions\ParseException;
use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByTechPassportSearchCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\BaseCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ICommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\IExecutableCommand;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotManager;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use TelegramBot\Api\BotApi;

class SetTechPassportCommand extends BaseCommand implements ICommand, IExecutableCommand
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
        $this->botClient->sendMessage($this->telegramBotUser->chat_id,"Введіть серію та номер тех. паспорту. Наприклад, CXT123456");
        $this->setCurrentCommand();
    }

    public function execute(TelegramBotRequest $telegramBotRequest)
    {
        $techPassport = $telegramBotRequest->getMessage();
        $tempData = $this->telegramBotUser->temp_data;


        try {
            $parsedDocument = Documents::parseTechPassportString($techPassport);

            $command = new ByTechPassportSearchCommand($parsedDocument['series'],$parsedDocument['number'],$tempData['car_number']);
            $this->botManager->addCar($this->telegramBotUser,$command);

         } catch (ParseException $e) {
            $this->botClient->sendMessage($this->telegramBotUser->chat_id, "Невірний формат");
            $this->setCurrentCommand();
        }
    }
}