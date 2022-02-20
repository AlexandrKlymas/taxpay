<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseCommissionsManager;
use EvolutionCMS\Main\Services\GovPay\Models\Commission;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceCommission;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceRecipient;

class MarriageCommissionsManager extends BaseCommissionsManager
{
    public function getCommissions(int $subServiceId=0): array
    {
        $commission['total'] = [];
        $commission['pension_fund'] = [];

        $serviceRecipients = ServiceRecipient::where('sub_service_id',$subServiceId)
            ->get()->toArray();

        if(empty($serviceRecipients)){
            return $commission;
        }

        foreach($serviceRecipients as $k=>$serviceRecipient){
            $serviceRecipientCommissions = ServiceCommission::where('service_recipient_id',$serviceRecipient['id'])
                ->get()->toArray();
            if(!empty($serviceRecipientCommissions)){
                $serviceRecipients[$k]['commissions'][] = $serviceRecipientCommissions;
            }
        }

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