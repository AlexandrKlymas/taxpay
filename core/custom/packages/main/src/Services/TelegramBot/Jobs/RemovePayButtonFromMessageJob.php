<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Jobs;


use EvolutionCMS\EvocmsQueue\AbstractJob;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use EvolutionCMS\Main\Services\TelegramBot\Messages\FinePaidMessage;
use EvolutionCMS\Main\Services\TelegramBot\Messages\MessageKeyboardGenerator\FineMessageKeyboardGenerator;
use Illuminate\Queue\InteractsWithQueue;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\HttpException;

class RemovePayButtonFromMessageJob extends AbstractJob
{
    use InteractsWithQueue;

    private $chatId;

    private $fineId;

    private $messageId;

    public function __construct($fineId, $chatId, $messageId)
    {


        $this->fineId = $fineId;
        $this->chatId = $chatId;
        $this->messageId = $messageId;
    }

    public function handle(FineMessageKeyboardGenerator $fineMessageKeyboardGenerator,BotApi $bot)
    {

        $fine = Fine::findOrFail($this->fineId);

        $keyboard = $fineMessageKeyboardGenerator->generateKeyboard($fine);

        try {

            $bot->editMessageReplyMarkup($this->chatId, $this->messageId, $keyboard);
        } catch (HttpException $e) {
            if (strpos($e->getMessage(), 'message is not modified') === false) {
                throw new \Exception();
            }
        }

    }
}