<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax;

use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusError;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusFailure;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusReady;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSuccess;

class SudyTaxCallbackCustomServiceCommand extends \Illuminate\Console\Command
{
    protected $signature = 'sudytax:customservice';

    protected $description = 'Custom servicing command';


    public function handle()
    {
        $service_id = 162;
        $dateFrom = '2022-01-01 00:00:00';
        $statuses = [
            'errors'=>[StatusError::getKey(),StatusFailure::getKey()],
            'success'=>[StatusReady::getKey(),StatusSuccess::getKey()],
        ];

        $serviceOrders = ServiceOrder::query()
            ->where(function($query) use ($dateFrom,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->where('created_at','>',$dateFrom);
                $query->where('status',StatusError::getKey());
            })
            ->orWhere(function($query) use ($dateFrom,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->where('created_at','>',$dateFrom);
                $query->where('status',StatusFailure::getKey());
            })
            ->orWhere(function($query) use ($dateFrom,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->where('created_at','>',$dateFrom);
                $query->where('status',StatusReady::getKey());
            })
            ->orWhere(function($query) use ($dateFrom,$statuses,$service_id){
                $query->where('service_id',$service_id);
                $query->where('created_at','>',$dateFrom);
                $query->where('status',StatusSuccess::getKey());
            })
            ->get()
        ;

        $service = new SudyTaxCallbackService(162);

        echo $serviceOrders->count().PHP_EOL;
        echo 'start'.PHP_EOL;
        foreach($serviceOrders as $serviceOrder){
            if(in_array($serviceOrder->status,$statuses['success'])){
                $service->sendSuccessNotify($serviceOrder);
                echo '✓'.$serviceOrder->id.PHP_EOL;
                sleep(1);
            }
            if(in_array($serviceOrder->status, $statuses['errors'])){
                $service->sendErrorNotify($serviceOrder);
                echo '❌'.$serviceOrder->id.PHP_EOL;
                sleep(1);
            }
        }
        echo 'complete'.PHP_EOL;
    }
}