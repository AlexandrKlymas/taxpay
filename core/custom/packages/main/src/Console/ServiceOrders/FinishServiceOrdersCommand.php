<?php

namespace EvolutionCMS\Main\Console\ServiceOrders;

use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use Illuminate\Console\Command;

class FinishServiceOrdersCommand extends Command
{
    protected $signature = 'service_orders:finish';

    protected $description = 'Execute orders and change status if it executed';
    /**
     * @var ServiceManager
     */
    private $serviceManager;


    public function __construct()
    {
        parent::__construct();
        $this->serviceManager = new ServiceManager();
    }

    public function handle()
    {
        evo()->logEvent(456,1,'Начало выполнения услуг','FinishServiceOrders');
        $this->serviceManager->completedServiceOrders();
    }
}