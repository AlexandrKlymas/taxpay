<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Console;

use Illuminate\Console\Command;

class SendFinesNotificationCommand extends Command
{
    protected $signature = 'bot:notification-send';
    protected $description = 'Send notification about new fines';


    public function handle()
    {
        (new \EvolutionCMS\Main\Services\TelegramBot\TelegramBotUserFinesNotificationSender())->sendNotification();
    }
}