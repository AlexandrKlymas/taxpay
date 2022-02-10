<?php


namespace EvolutionCMS\Main\Services\FinesSearcher\Jobs;


use EvolutionCMS\EvocmsQueue\AbstractJob;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class FinePaidRequestSenderJob extends AbstractJob
{

    private $serviceOrderId;

    public function __construct($serviceOrderId)
    {
        $this->serviceOrderId = $serviceOrderId;
    }

    public function handle(IFinesApi $finesApi)
    {
        $serviceOrder = ServiceOrder::findOrFail($this->serviceOrderId);

        $mainRecipient = $serviceOrder->mainRecipients->first();


        $docId = $serviceOrder->form_data['fine']['docId'];
        $sumPaid = $serviceOrder->sum;
        $payId = $mainRecipient->check_id;


        try {
            $finesApi->payFine($docId,$sumPaid,$payId);
            $serviceOrder->updateServiceData('payFine',true);
            $serviceOrder->save();
        }
        catch (\Exception $e){
            evo()->logEvent(741,3,$e->getMessage(),'FinesExecutor');
            throw $e;
        }

    }

}