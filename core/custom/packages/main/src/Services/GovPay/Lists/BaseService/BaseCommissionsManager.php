<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\ICommissionsManager;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use EvolutionCMS\Main\Services\GovPay\Models\Commission;

class BaseCommissionsManager implements ICommissionsManager
{
    protected int $serviceId;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceId = $serviceFactory->getServiceId();
    }

    public function getCommissions(): array
    {
        $commission = [
            'total' => [

            ],
            'pension_fund' => [

            ]
        ];

        $serviceCommission = Commission::where('form_id', $this->serviceId)
            ->limit(1)->first();

        if ($serviceCommission) {
            $commission['total'] = [
                "fix_summ" => $serviceCommission->fix_summ,
                "percent" => $serviceCommission->percent,
                "min_summ" => $serviceCommission->min_summ,
                "max_summ" => $serviceCommission->max_summ,
            ];
        }

        return $commission;
    }
}