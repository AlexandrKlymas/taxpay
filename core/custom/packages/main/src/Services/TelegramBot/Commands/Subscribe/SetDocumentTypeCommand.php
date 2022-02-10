<?php

namespace EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe;


use EvolutionCMS\Main\Services\TelegramBot\Commands\BaseCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ICommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents\DrivingLicense\SetDrivingLicenseCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents\SetIdCardCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents\SetTaxNumberCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents\SetTechPassportCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\Documents\SetUkrainePassportCommand;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotHelper;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;


class SetDocumentTypeCommand extends BaseCommand implements ICommand
{


    public function init(TelegramBotRequest $telegramBotRequest)
    {
        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
            [
                [
                    [
                        'text' => "Посвідчення водія",
                        'callback_data' => TelegramBotHelper::convertClassToCommand(SetDrivingLicenseCommand::class)
                    ],
                ],
                [
                    [
                        'text' => "Свідотства про реєстрацію ТЗ",
                        'callback_data' => TelegramBotHelper::convertClassToCommand(SetTechPassportCommand::class)
                    ],
                ],
                [
                    [
                        'text' => "індивідуальний податковий номер",
                        'callback_data' => TelegramBotHelper::convertClassToCommand(SetTaxNumberCommand::class)
                    ],
                ],
                [
                    [
                        'text' => "Паспорт громадянина україни",
                        'callback_data' => TelegramBotHelper::convertClassToCommand(SetUkrainePassportCommand::class)
                    ],
                ],
                [
                    [
                        'text' => "Ід карта",
                        'callback_data' => TelegramBotHelper::convertClassToCommand(SetIdCardCommand::class)
                    ],
                ]
            ]
        );
        $this->botClient->sendMessage($this->telegramBotUser->chat_id, 'Виберіть, будь ласка, одиз із наступних документів:', null, null, null, $keyboard);
    }
}