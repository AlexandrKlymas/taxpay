<?php

namespace EvolutionCMS\Main\Services\TelegramBot\Commands;

use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;

class StartCommand extends BaseCommand implements ICommand
{

    public function init(TelegramBotRequest $telegramBotRequest)
    {

        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
            [
                [
                    [
                        'text' => "\xF0\x9F\x9A\x98 Додати авто"
                    ],
                    [
                        'text' => "\xF0\x9F\x93\x83 Мої постанови"
                    ],
                ],
                [
                    [
                        'text' => "\xE2\xAD\x90 Мої авто"
                    ],
                    [
                        'text' => "\xF0\x9F\x93\x9E Допомога"
                    ]
                ]
            ],
            false,
            true
        );

        $this->botClient->call('setMyCommands', [
                'commands' => json_encode(
                    [
                        [
                            'command' => '/start',
                            'description' => 'Start work with bot'
                        ]
                    ]

                )]
        );


        $this->botClient->sendMessage(
            $this->telegramBotUser->chat_id,
            "Вітаю!\nБуду радий вам допомогти",
            null,
            null,
            null,
            $keyboard
        );
    }
}