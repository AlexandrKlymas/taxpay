<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Messages;


use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use TelegramBot\Api\BotApi;

class FinePaidMessage
{
    /**
     * @var \DocumentParser
     */
    private $evo;
    /**
     * @var BotApi
     */
    private $bot;

    public function __construct()
    {
        $this->evo = evolutionCMS();
        $this->bot = new BotApi($this->evo->getConfig('main_telegramBotToken'));
    }

    public function send(string $chatId)
    {
        $message = \View::make('partials.services.bot.file_paid_notification')->render();
        $this->bot->sendMessage($chatId, $message, 'HTML', false);
    }

}