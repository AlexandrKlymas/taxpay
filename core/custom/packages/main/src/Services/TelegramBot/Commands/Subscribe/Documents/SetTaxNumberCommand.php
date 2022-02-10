<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents;

use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByTaxNumberSearchCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\BaseCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ICommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\IExecutableCommand;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotManager;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use TelegramBot\Api\BotApi;

class SetTaxNumberCommand extends BaseCommand implements ICommand, IExecutableCommand
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
        $this->botClient->sendMessage($this->telegramBotUser->chat_id,"Введіть індивідуальний податковий номер. Наприклад, 1234567890");
        $this->setCurrentCommand();
    }

    public function execute(TelegramBotRequest $telegramBotRequest)
    {
        $taxNumber = $telegramBotRequest->getMessage();
        $tempData = $this->telegramBotUser->temp_data;

        $command = new ByTaxNumberSearchCommand($taxNumber,$tempData['car_number']);
        $this->botManager->addCar($this->telegramBotUser,$command);
    }
}