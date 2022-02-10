<?php

namespace EvolutionCMS\Main\Console\Support;

use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Support\StrHelper;
use Illuminate\Console\Command;

class ServiceCommand extends Command
{
    protected $signature = 'service:run';

    protected $description = 'For service things';

    public function handle(){
        $this->checkPurpose();
    }

    private function checkPurpose(array $doneIds = [])
    {
        $query = PaymentRecipient::where('status','finished');

        if(!empty($doneIds)){
            $query->where('id','>',end($doneIds));
        }

        $recipients = $query->limit(100)->get()->toArray();

        $doneIds = [];

        if(empty($recipients)){
            echo 'done'.PHP_EOL;
            die();
        }

        foreach($recipients as $recipient){
            $replacedPurpose = StrHelper::replaceSymbols($recipient['purpose']);
            $replacedPurpose = StrHelper::removeSpacers($replacedPurpose);
            $replacedPurpose = StrHelper::comaSpacer($replacedPurpose);
            $purpose = StrHelper::purposeClearing($replacedPurpose);

            if(!empty($recipient['purpose']) && $replacedPurpose != $purpose){
                echo 'error id='.$recipient['service_order_id'].PHP_EOL;
                echo $recipient['purpose'].PHP_EOL;
                echo $replacedPurpose.PHP_EOL;
                echo $purpose.PHP_EOL;
                die();
            }

            echo $recipient['id'].PHP_EOL;

            $doneIds[] = $recipient['id'];

            unset($recipient);
            unset($recipients);
            unset($query);
            unset($purpose);
            unset($replacedPurpose);
        }

        $this->checkPurpose($doneIds);
    }
}