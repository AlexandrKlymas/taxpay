<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents\DrivingLicense;

use EvolutionCMS\Main\Services\FinesSearcher\Documents;
use EvolutionCMS\Main\Services\FinesSearcher\Exceptions\ParseException;
use EvolutionCMS\Main\Services\TelegramBot\Commands\BaseCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ICommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\IExecutableCommand;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotHelper;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;

class SetDrivingLicenseCommand extends BaseCommand implements ICommand, IExecutableCommand
{

    public function init(TelegramBotRequest $telegramBotRequest)
    {
        $this->botClient->sendMessage($this->telegramBotUser->chat_id,"Вкажіть серію та номер посвідчення Наприклад, АБВ123456789");
        $this->setCurrentCommand();
    }

    public function execute(TelegramBotRequest $telegramBotRequest)
    {
        $drivingLicense = $telegramBotRequest->getMessage();

        try {
            $parsedDocuments = Documents::parseDrivingLicenseString($drivingLicense);

            $this->telegramBotUser->setTempData('driving_license_series',$parsedDocuments['series']);
            $this->telegramBotUser->setTempData('driving_license_number',$parsedDocuments['number']);


            TelegramBotHelper::runCommand($this->botClient,$this->telegramBotUser,$telegramBotRequest,SetDrivingLicenseDateIssueCommand::class,'init');
        }
        catch (ParseException $e){
            $this->botClient->sendMessage($this->telegramBotUser->chat_id,"Невірний формат");
            $this->setCurrentCommand();
        }


    }
}