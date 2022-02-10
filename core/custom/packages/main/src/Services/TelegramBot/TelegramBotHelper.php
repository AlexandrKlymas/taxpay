<?php
namespace EvolutionCMS\Main\Services\TelegramBot;


use EvolutionCMS\Main\Services\TelegramBot\Commands\EmptyCommand;
use EvolutionCMS\Main\Services\TelegramBot\Commands\IExecutableCommand;

class TelegramBotHelper
{
    private static $commandNameSpace = 'EvolutionCMS\Main\Services\TelegramBot\Commands\\';

    public static function runCommand($bot,$telegramBotUser,$telegramBotRequest,$command,$action){

        if(!class_exists($command)){
            $command = EmptyCommand::class;
        }

        $commandHandler = new $command($bot,$telegramBotUser);
        if(!$commandHandler instanceof IExecutableCommand && $action == 'execute'){
            $commandHandler = new EmptyCommand($bot,$telegramBotUser);
            $action = 'init';
        }
        call_user_func_array([$commandHandler, $action], [$telegramBotRequest]);
    }


    public static function convertClassToCommand($class){
        $classWithoutBaseNameSpace = str_replace(self::$commandNameSpace,'',$class);
        $classWithoutBaseNameSpace = substr($classWithoutBaseNameSpace,0,-7);
        $classWithoutBaseNameSpace = lcfirst($classWithoutBaseNameSpace);
        return '/'.$classWithoutBaseNameSpace;
    }
    public static function convertCommandToClass($command){

        return self::$commandNameSpace.ucfirst($command).'Command';

    }
}