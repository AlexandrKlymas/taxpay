<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICommissionsManager;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Models\Commission;

class BaseCommissionsManager implements ICommissionsManager
{
    protected int $serviceId;
    protected IServiceFactory $serviceFactory;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->serviceId = $this->serviceFactory->getServiceId();
    }

    public function getCommissions(int $subServiceId = 0): array
    {
        $commission['total'] = [];
        $commission['pension_fund'] = [];

        $serviceCommission = Commission::where('form_id', $this->serviceId)
            ->limit(1)->first();

        if ($serviceCommission) {
            $commission['total'] = [
                "percent" => $serviceCommission->percent,
                "min" => $serviceCommission->min_summ,
                "max" => $serviceCommission->max_summ,
                "fix" => $serviceCommission->fix_summ,
            ];
        }

        return $commission;
    }
}