<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFeeCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;

class BaseFeeCalculator implements IFeeCalculator
{
    protected float $fix;
    protected float $percent;
    protected float $min;
    protected float $max;
    protected int $serviceId;
    protected IServiceFactory $serviceFactory;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
        $this->serviceId = $this->serviceFactory->getServiceId();
        $this->setCommission();
    }

    public function setCommission(float $percent = 0.00, float $min = 0.00, float $max = 0.00, float $fix = 0.00): BaseFeeCalculator
    {
        $this->fix = $fix;
        $this->percent = $percent;
        $this->min = $min;
        $this->max = $max;

        return $this;
    }

    public function calculate(float $sum): float
    {
        $commission = 0.00;
        if ($this->fix !== 0.00) {
            $commission = $this->fix;
        } else if ($this->percent !== 0.00) {
            $commission = $sum * ($this->percent) / 100;
            if ($this->min !== 0.00 && $this->min > $commission) {
                $commission = $this->min;
            }
            if ($this->max !== 0.00 && $this->max < $commission) {
                $commission = $this->max;
            }
        }

        return round($commission,2);
    }
}