<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands;

use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

interface IExecutableCommand
{
    public function execute(TelegramBotRequest $telegramBotRequest);
}