<?php

namespace EvolutionCMS\Main\Services\TelegramBot;

use EvolutionCMS\Main\Models\MedicalCenterUser;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ListCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\HelpCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\FinesCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\SetCarNumberCommand;
use Illuminate\Http\Request;
use TelegramBot\Api\BotApi;

class TelegramBotPlr
{
    private $bot;

    private $buttonCommands = [];
    private $commandAliases = [

    ];
    /**
     * @var \DocumentParser
     */
    private $evo;

    public function __construct()
    {
        $this->evo = \EvolutionCMS();


        $this->commandAliases = [
            '/subscribe' => TelegramBotHelper::convertClassToCommand(SetCarNumberCommand::class),
        ];
        $this->buttonCommands = [
            "📃 Мої постанови" => TelegramBotHelper::convertClassToCommand(FinesCommand::class),
            "🚘 Додати авто" => TelegramBotHelper::convertClassToCommand(SetCarNumberCommand::class),
            "⭐ Мої авто" => TelegramBotHelper::convertClassToCommand(ListCommand::class),
            "📞 Допомога" => TelegramBotHelper::convertClassToCommand(HelpCommand::class),
        ];
        $this->bot = new BotApi($this->evo->getConfig('support_telegramBotToken'));
    }

    public function handleWebHook(Request $request)
    {
        $requestData = $request->toArray();

        $telegramBotRequest = TelegramBotRequest::getTelegramBotRequestFromRequestData($requestData);


        $chatId = $telegramBotRequest->getChatId();

        $telegramBotUser = MedicalCenterUser::query()->firstOrCreate(['telegram_id' => $chatId]);
        if(empty($telegramBotUser->name)) {
            $telegramBotUser->name = $requestData['message']['from']['first_name'] ?? '';
            $telegramBotUser->name .= ' '.$requestData['message']['from']['last_name'] ?? '';
            $telegramBotUser->name = trim($telegramBotUser->name);
            if(trim($telegramBotUser->name) == '') {
                $telegramBotUser->name = $requestData['message']['from']['username'] ?? '';
            }
            $telegramBotUser->save();
        }
        if(isset($requestData['message'], $requestData['message']['contact'], $requestData['message']['contact']['phone_number'])) {
            $phone = str_replace('+', '', $requestData['message']['contact']['phone_number']);
            $altTelegramBotUser = MedicalCenterUser::query()->where('phone', $phone)->first();
            if(!is_null($altTelegramBotUser)) {
                $telegramBotUser->delete();
                $telegramBotUser = $altTelegramBotUser;
                $telegramBotUser->telegram_id = $chatId;
            }
            $telegramBotUser->phone = $phone;
            $telegramBotUser->save();
        }

        if(trim($telegramBotUser->phone) != '') {
            if(trim($telegramBotUser->medical_center_id) != 0) {
                $text = 'Теперь вы можете принимать данные о платежах';

            }else {
                $text = 'Вы не привязаны к какому-либо из медицинских центров';

            }
            $this->bot->sendMessage($telegramBotUser->telegram_id, $text);
        }else {
            $replyMarkup =  array(
                    array(["text" => 'Поделиться телефоном', "request_contact" => true])


            );
            $replyMarkup = new \TelegramBot\Api\Types\ReplyKeyboardMarkup($replyMarkup, true, true);

            $text = 'Для продолжения работы поделитесь телефоном';
            $this->bot->sendMessage($telegramBotUser->telegram_id, $text, null, false, null, $replyMarkup);
        }


    }


    private function getCommandAndAction(string $text, $lastCommand)
    {
        if (strpos($text, '/') === 0) {
            if (array_key_exists($text, $this->commandAliases)) {
                $command = $this->commandAliases[$text];
            } else {
                $command = $text;
            }
            $action = 'init';
        } elseif (array_key_exists($text, $this->buttonCommands)) {
            $command = $this->buttonCommands[$text];
            $action = 'init';
        } else {
            if (!empty($lastCommand)) {
                $command = $lastCommand;
                $action = 'execute';
            } else {
                $action = 'init';
                $command = 'empty';
            }
        }
        if (strpos($command, '/') === 0) {
            $command = substr($command, 1);
        }

        $command = TelegramBotHelper::convertCommandToClass($command);

        return [
            'command' => $command,
            'action' => $action,
        ];
    }

    public static function sendNotify($medical_center, $text)
    {
        $bot = new BotApi(EvolutionCMS()->getConfig('support_telegramBotToken'));
        $users = MedicalCenterUser::query()
            ->where('medical_center_id', $medical_center)
            ->where('status', 1)
            ->where('telegram_id', '!=', '')
            ->orWhere('medical_center_id',-1)
            ->get();
        foreach ($users as $telegramBotUser) {
            try {
                $bot->sendMessage($telegramBotUser->telegram_id, $text);
            } catch (\Exception $exception) {

            }
        }
    }
}