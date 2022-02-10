<?php

namespace EvolutionCMS\Main\Services\TelegramBot\Commands;

use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;

class ListCommand extends BaseCommand implements ICommand
{
    public function init(TelegramBotRequest $telegramBotRequest)
    {
        $cars = $this->telegramBotUser->cars;

        if ($cars->count()) {
            $this->botClient->sendMessage($this->telegramBotUser->chat_id, 'Ви додали наступні 🚙 авто:');
            foreach ($cars as $car) {
                $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
                    [
                        [
                            ['text' => "🚫 Відписатись", 'callback_data' => '/unsubscribe?id='.$car->id]
                        ]
                    ]
                );

//                $command::fromArray();

                $this->botClient->sendMessage($this->telegramBotUser->chat_id, 'Номерний знак 🚙 ' . $car->car_number . ', 🆔 документ ' . $car->document,null,null,null,$keyboard);
            }
        } else {
            $this->botClient->sendMessage($this->telegramBotUser->chat_id, "Ви ще не додали жодного 🚙 авто");
        }

    }

}