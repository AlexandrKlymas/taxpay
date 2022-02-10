<?php
namespace EvolutionCMS\Main\Services\TelegramBot;
use EvolutionCMS\Main\Services\TelegramBot\Commands\ListCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\HelpCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\FinesCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\Subscribe\SetCarNumberCommand;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use Illuminate\Http\Request;
use TelegramBot\Api\BotApi;

class TelegramBot
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
            '/subscribe'=> TelegramBotHelper::convertClassToCommand(SetCarNumberCommand::class),
        ];
        $this->buttonCommands = [
            "📃 Мої постанови" => TelegramBotHelper::convertClassToCommand(FinesCommand::class),
            "🚘 Додати авто" => TelegramBotHelper::convertClassToCommand(SetCarNumberCommand::class),
            "⭐ Мої авто" => TelegramBotHelper::convertClassToCommand(ListCommand::class),
            "📞 Допомога" => TelegramBotHelper::convertClassToCommand(HelpCommand::class),
        ];
        $this->bot = new BotApi($this->evo->getConfig('main_telegramBotToken'));

    }

    public function handleWebHook(Request $request){

        $requestData = $request->toArray();

        try{
            $telegramBotRequest = TelegramBotRequest::getTelegramBotRequestFromRequestData($requestData);
        }catch(\Exception $e){
            evo()->logEvent(1,1,json_encode($requestData),'Telegram handleWebHook Catch');
            exit();
        }



        $chatId = $telegramBotRequest->getChatId();
        $telegramBotUser = TelegramBotUser::whereChatId($chatId)->firstOrCreate([
            'chat_id'=> $chatId
        ]);
        if(is_null($telegramBotRequest->getMessage())) {
            evo()->logEvent(1,1,json_encode($requestData),'Telegram handleWebHook Catch3');
            exit();
        }
        try{
            $commandAndAction = $this->getCommandAndAction($telegramBotRequest->getMessage(),$telegramBotUser->command);
        }catch(\Exception $e){
            evo()->logEvent(1,1,json_encode($requestData),'Telegram handleWebHook Catch2');
            exit();
        }




       $telegramBotUser->setCommand(null);
       TelegramBotHelper::runCommand($this->bot,$telegramBotUser,$telegramBotRequest,$commandAndAction['command'],$commandAndAction['action']);

    }


    private function getCommandAndAction(string $text,$lastCommand)
    {


        if(strpos($text,'/')===0){
            if(array_key_exists($text,$this->commandAliases)){
                $command = $this->commandAliases[$text];
            }
            else{
                $command = $text;
            }
            $action = 'init';
        }
        elseif (array_key_exists($text,$this->buttonCommands)){
            $command = $this->buttonCommands[$text];
            $action = 'init';
        }
        else if(!empty($lastCommand)){

            $command = $lastCommand;
            $action = 'execute';
        }
        else{
            $action = 'init';
            $command = 'empty';

        }
        if(strpos($command,'/')===0){
            $command = substr($command,1);
        }

        $command = TelegramBotHelper::convertCommandToClass($command);
        return [
            'command'=>$command,
            'action'=>$action,
        ];
    }
}