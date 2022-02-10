<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents\DrivingLicense;


use EvolutionCMS\Main\Services\FinesSearcher\SearchCommands\ByDrivingLicenseSearchCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\BaseCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ICommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\IExecutableCommand;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotManager;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use TelegramBot\Api\BotApi;

class SetDrivingLicenseDateIssueCommand extends BaseCommand implements ICommand, IExecutableCommand
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
        $this->botClient->sendMessage($this->telegramBotUser->chat_id,"Введіть дату видачі посвідчення Наприклад, 12.12.2020");
        $this->setCurrentCommand();
    }

    public function execute(TelegramBotRequest $telegramBotRequest)
    {
        $drivingLicenseDateIssue = $telegramBotRequest->getMessage();

        $tempData = $this->telegramBotUser->temp_data;



        $command = new ByDrivingLicenseSearchCommand($tempData['driving_license_series'],$tempData['driving_license_number'],$drivingLicenseDateIssue,$tempData['car_number']);
        $this->botManager->addCar($this->telegramBotUser,$command);
    }
}