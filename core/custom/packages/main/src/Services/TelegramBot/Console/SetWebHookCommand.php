<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Console;


use EvolutionCMS\Main\Services\TelegramBot\TelegramBotUserCarsFineSearcher;
use Illuminate\Console\Command;
use TelegramBot\Api\BotApi;

class SetWebHookCommand extends Command
{
    protected $signature = 'bot:set-web-hook';

    protected $description = 'Set web hook for bot';


    public function handle()

    {
        $telegram = new BotApi(evo()->getConfig('main_telegramBotToken'));
//
        $result = $telegram->setWebhook(
            evo()->getConfig('site_url').'telegram-bot-webhook'
        );
        sleep(1);
        $telegramPlr = new BotApi(evo()->getConfig('support_telegramBotToken'));
//
        $result = $telegramPlr->setWebhook(
            evo()->getConfig('site_url').'telegram-bot-webhook-plr'
        );

        echo 'ok';
    }
}