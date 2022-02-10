<?php

namespace EvolutionCMS\Main\Services\GovPay\Managers;


use EvolutionCMS\Main\Services\GovPay\Contracts\IStatus;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusError;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusFailure;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusQuestion;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusReady;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusReversed;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSubmitted;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSuccess;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusWait;

class StatusManager
{
//    const STATUS_WAIT = [
//        'code'=>'wait',
//        'title'=>'Очікує оплати',
//        'class'=>
//    ];
//    const STATUS_SUCCESS = 'success';
//    const STATUS_ERROR = 'error';
//    const STATUS_FAILURE = 'Failure';
//    const STATUS_REVERSED = 'Reversed';
//    const STATUS_SUBMITTED = 'Submitted';
//    const STATUS_QUESTION = 'Question';
//    const STATUS_READY = 'Ready';


    private $statuses = [
        StatusWait::class,
        StatusSuccess::class,
        StatusError::class,
        StatusFailure::class,
        StatusReversed::class,
        StatusSubmitted::class,
        StatusQuestion::class,
        StatusReady::class,
    ];
    private $statusKeyMap = [];

    public function __construct()
    {
        foreach ($this->statuses as $statusClass) {
            $this->statusKeyMap[$statusClass::getKey()] = $statusClass;
        }
    }

    public function getStatuses(){
        $statuses = [];

        foreach ($this->statusKeyMap as $statusCode => $statusClass) {
            $status = $this->getStatus($statusCode);
            $statuses[$statusCode] = $status->getTitle();
        }
        return $statuses;
    }


    public function canChange($statusCode, ServiceOrder $serviceOrder)
    {
        if(!$this->issetStatus($statusCode)){
            evo()->logEvent(1,1,$statusCode,'Непредвиденный статус '.$statusCode);
            return false;
        }
        $status = $this->getStatus($statusCode);
        return $status->canChange($serviceOrder);
    }

    public function change($statusCode,ServiceOrder $serviceOrder, array $additionalData = [])
    {
        if(!$this->canChange($statusCode,$serviceOrder)){
            throw new \Exception('Нельзя изменить статус');
        }
        $status = $this->getStatus($statusCode);

        $serviceOrder->historyUpdate('Статус змінено на ['.$status->getKey().'] '.$status->getTitle());

        return $status->change($serviceOrder,$additionalData);

    }

    public function forceChange($statusCode,ServiceOrder $serviceOrder, array $additionalData = []){
        $status = $this->getStatus($statusCode);
        $serviceOrder->historyUpdate('Статус змінено на ['.$status->getKey().'] '.$status->getTitle());
        return $status->change($serviceOrder,$additionalData);
    }

    private function issetStatus($statusCode): bool
    {
        return !empty($this->statusKeyMap[$statusCode]);
    }

    private function getStatus($statusCode): IStatus
    {
        return new $this->statusKeyMap[$statusCode];
    }

    public function isPaid(ServiceOrder $serviceOrder){
        $status = $this->getStatus($serviceOrder->status);
        return $status->isPaid($serviceOrder);
    }

}