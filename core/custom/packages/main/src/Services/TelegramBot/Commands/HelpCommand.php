<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Commands;

use EvolutionCMS\Main\Services\TelegramBot\TelegramBotRequest;
use Illuminate\Support\Facades\View;

class HelpCommand extends BaseCommand implements ICommand
{
    public function init(TelegramBotRequest $telegramBotRequest)
    {
        $this->botClient->sendMessage($this->telegramBotUser->chat_id,View::make('partials.bot.commands.help')->render());
    }
}