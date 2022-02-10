<?php


namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\FinesByAct;


use EvolutionCMS\Main\Services\FinesSearcher\FinePaidStatusChanger;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IExecutor;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;

class FinesByActExecutor implements IExecutor
{
    /**
     * @var FinePaidStatusChanger
     */
    private $finePaidStatusChanger;

    public function __construct()
    {
        $this->finePaidStatusChanger = evo()->make(FinePaidStatusChanger::class);
    }

    public function execute(ServiceOrder $serviceOrder)
    {
        $protocolSeries = mb_strtoupper($serviceOrder->form_data['fine_series'],'utf-8');
        $protocolNumber =$serviceOrder->form_data['fine_number'];

        $this->finePaidStatusChanger->admitFineIsPaid($protocolSeries,$protocolNumber);

        $serviceOrder->updateServiceData('execute', true);
        $serviceOrder->save();
    }

    public function isCompleted(ServiceOrder $serviceOrder)
    {
        return $serviceOrder->service_data['execute'] === true;
    }
}