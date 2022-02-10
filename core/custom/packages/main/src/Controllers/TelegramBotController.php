<?php
namespace EvolutionCMS\Main\Controllers;



use EvolutionCMS\Main\Services\TelegramBot\TelegramBot;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotPlr;
use EvolutionCMS\Main\Services\TelegramBot\TelegramBotUserCarsFineSearcher;

use Illuminate\Http\Request;

class TelegramBotController
{

    /**
     * @var TelegramBot
     */
    private $telegramBot;
    /**
     * @var TelegramBotPlr
     */
    private $telegramBotPlr;

    public function __construct()
    {
        $this->telegramBot = new TelegramBot();
        $this->telegramBotPlr = new TelegramBotPlr();
    }

    public function handleWebhook(Request $request)
    {

        \EvolutionCMS()->getDatabase()->connect();

        try {
            $this->telegramBot->handleWebHook($request);
        }
        catch (\Exception $e){
            \EvolutionCMS()->logEvent(601,3,$e->getMessage(),'TelegramBot');
            throw $e;
        }
    }

    public function handleWebhookPlr(Request $request)
    {

        \EvolutionCMS()->getDatabase()->connect();

        try {
            $this->telegramBotPlr->handleWebHook($request);
        }
        catch (\Exception $e){
            \EvolutionCMS()->logEvent(601,3,$e->getMessage(),'TelegramBot');
            throw $e;
        }
    }

    public function searchFinesForAllCar(Request $request)
    {
        $this->validateAccess();

        (new TelegramBotUserCarsFineSearcher())->searchFinesForAllCar();

    }

    private function validateAccess()
    {
        if (empty(evo()->getLoginUserID('mgr'))) {
            evo()->sendRedirect(13);
        }
    }
}