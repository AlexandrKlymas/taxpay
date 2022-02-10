<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Jobs;


use EvolutionCMS\EvocmsQueue\AbstractJob;
use EvolutionCMS\Main\Services\TelegramBot\Messages\FinePaidMessage;
use Illuminate\Queue\InteractsWithQueue;

class FinePaidNotificationJob extends AbstractJob
{

    use InteractsWithQueue;

    private $chatId;

    public function __construct($chatId)
    {
        $this->chatId = $chatId;
    }

    public function handle()
    {
        (new FinePaidMessage())->send($this->chatId);
    }
}