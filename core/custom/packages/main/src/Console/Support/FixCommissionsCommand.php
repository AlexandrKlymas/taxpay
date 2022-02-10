<?php

namespace EvolutionCMS\Main\Console\Support;

use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusQuestion;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSuccess;
use Illuminate\Console\Command;



class FixCommissionsCommand extends Command
{
    protected $signature = 'fix:commissions';

    protected $description = 'Fix commissions';

    public function handle(){
//        $dateFrom = '2022-02-01 00:00:00';
//
//        $serviceOrders = ServiceOrder::query()
//            ->where('created_at','>',$dateFrom)
//            ->where('status',StatusQuestion::getKey())
////            ->limit(1)
//            ->get()
//        ;
//
//        foreach($serviceOrders as $serviceOrder){
//            $profit = $serviceOrder->total -
//                ($serviceOrder->liqpay_real_commission + $serviceOrder->sum + $serviceOrder->bank_commission);
//                echo $profit.PHP_EOL;
//                echo $serviceOrder->id.PHP_EOL;
//            $serviceOrder->update([
//                'liqpay_commission_auto_calculated'=>$serviceOrder->liqpay_real_commission,
//                'profit'=>$profit,
//                'status'=>StatusSuccess::getKey(),
//            ]);
//            PaymentRecipient::whereRecipientType('govpay_profit')
//                ->whereServiceOrderId($serviceOrder->id)->update(['amount'=>$profit]);
//
//        }
//
//        echo $serviceOrders->count().PHP_EOL;

    }
}