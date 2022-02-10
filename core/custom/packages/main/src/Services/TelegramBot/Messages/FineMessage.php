<?php

namespace EvolutionCMS\Main\Services\TelegramBot\Messages;


use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use EvolutionCMS\Main\Services\TelegramBot\Messages\MessageKeyboardGenerator\FineMessageKeyboardGenerator;
use EvolutionCMS\Main\Services\TelegramBot\Models\FineTelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserMessage;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

class FineMessage
{
    /**
     * @var \DocumentParser
     */
    private $evo;
    /**
     * @var BotApi
     */
    private $bot;
    /**
     * @var FineMessageKeyboardGenerator
     */
    private $fineMessageKeyboardGenerator;

    public function __construct()
    {
        $this->evo = evolutionCMS();
        $this->bot = new BotApi($this->evo->getConfig('main_telegramBotToken'));
        $this->fineMessageKeyboardGenerator = new FineMessageKeyboardGenerator();
    }

    public function send(TelegramBotUser $telegramBotUser, Fine $fine)
    {
        $message = \View::make('partials.services.bot.fine_notification')->with('fine', $fine)->render();



        $keyboard = $this->fineMessageKeyboardGenerator->generateKeyboard($fine);
        /** @var Message $result */
        $result = $this->bot->sendMessage($telegramBotUser->chat_id, $message, 'HTML', false, null, $keyboard);

        FineTelegramBotUser::where('telegram_bot_user_id',$telegramBotUser->id)->where('fine_id',$fine->id)->update([
            'notify_new'=>1
        ]);

        TelegramBotUserMessage::create([
            'telegram_bot_user_id'=> $telegramBotUser->id,
            'message_type'=>TelegramBotUserMessage::TYPE_FINE,
            'message_id'=>$result->getMessageId(),
            'entity_id'=>$fine->id,
        ]);


    }

}