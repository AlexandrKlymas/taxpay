<?php
namespace EvolutionCMS\Main\Services\TelegramBot;


use GuzzleHttp\Psr7\Query;
use TelegramBot\Api\Types\Update;

class TelegramBotRequest
{
    /**
     * @return mixed
     */
    public function getChatId()
    {
        return $this->chatId;
    }
    private $chatId;

    public function getMessage()
    {
        return $this->message;
    }

    public function getParams()
    {
        return $this->params;
    }
    private $message;
    private $params;

    public function __construct($chatId,$message,$params)
    {
        $this->chatId = $chatId;
        $this->message = $message;
        $this->params = $params;
    }

    public static function getTelegramBotRequestFromRequestData(array $requestData){
        $botRequest = Update::fromResponse($requestData);

        $params = [];

        if($botRequest->getMessage()){
            $chatId = $botRequest->getMessage()->getChat()->getId();
            $message = $botRequest->getMessage()->getText();
        }
        elseif($botRequest->getEditedMessage()){
            $chatId = $botRequest->getEditedMessage()->getChat()->getId();
            $message = $botRequest->getEditedMessage()->getText();
        }
        elseif ($botRequest->getCallbackQuery()){
            $chatId = $botRequest->getCallbackQuery()->getMessage()->getChat()->getId();
            $parsedUrl = parse_url($botRequest->getCallbackQuery()->getData());
            $message = $parsedUrl['path'];
            $params = Query::parse($parsedUrl['query'] ?? '');
        }
        else{
            if(isset($requestData['update_id'])) {
                exit();
            }
                throw new \Exception('Bad request, '.print_r($requestData,true));
        }

        return new self($chatId,$message,$params);
    }
}