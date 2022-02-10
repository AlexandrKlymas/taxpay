<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands;

use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

interface ICommand
{
    public function __construct(BotApi $botClient,TelegramBotUser $telegramBotUser);

    public function init(TelegramBotRequest $telegramBotRequest);
}