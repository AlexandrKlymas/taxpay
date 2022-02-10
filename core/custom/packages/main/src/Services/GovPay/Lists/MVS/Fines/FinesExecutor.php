<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\Fines;


use EvolutionCMS\Main\Services\FinesSearcher\FinePaidStatusChanger;
use EvolutionCMS\Main\Services\FinesSearcher\Jobs\FinePaidRequestSenderJob;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IExecutor;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\TelegramBot\Jobs\FinePaidNotificationJob;
use EvolutionCMS\Main\Services\TelegramBot\Jobs\RemovePayButtonFromMessageJob;
use EvolutionCMS\Main\Services\TelegramBot\Models\FineTelegramBotUser;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserMessage;
use Illuminate\Support\Facades\Queue;

class FinesExecutor implements IExecutor
{


    /**
     * @var FinePaidStatusChanger|mixed
     */
    private $finePaidStatusChanger;

    public function __construct()
    {
        $this->finePaidStatusChanger = evo()->make(FinePaidStatusChanger::class);

    }

    public function execute(ServiceOrder $serviceOrder)
    {

        $fineId = $serviceOrder->form_data['fine_id'];

        $protocolSeries =$serviceOrder->form_data['fine']['sprotocol'];
        $protocolNumber =$serviceOrder->form_data['fine']['nprotocol'];

        $this->finePaidStatusChanger->admitFineIsPaid($protocolSeries,$protocolNumber);

        Queue::push(new FinePaidRequestSenderJob($serviceOrder->id));


        /** @var FineTelegramBotUser[] $userWhichSubscribeOnFine */
        $fineTelegramBotUsers = FineTelegramBotUser::where('fine_id', $fineId)->get();


        foreach ($fineTelegramBotUsers as $fineTelegramBotUser) {
            Queue::push((new FinePaidNotificationJob($fineTelegramBotUser->telegramBotUser->chat_id)));
        }

        /** @var TelegramBotUserMessage[] $fineMessages */
        $fineMessages = TelegramBotUserMessage::where('message_type', TelegramBotUserMessage::TYPE_FINE)->where('entity_id', $fineId)->get();



        foreach ($fineMessages as $fineMessage) {
            Queue::push(new RemovePayButtonFromMessageJob($fineMessage->entity_id, $fineMessage->telegramBotUser->chat_id, $fineMessage->message_id));
        }

    }

    public function isCompleted(ServiceOrder $serviceOrder)
    {
        if($serviceOrder->service_data['payFine'] === true){
            return  true;
        }
        return false;
    }
}