<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseCommissionsManager;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceCommission;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceRecipient;

class MarriageCommissionsManager extends BaseCommissionsManager
{
    public function getCommissions(int $subServiceId=0): array
    {
        $serviceRecipients = ServiceRecipient::where('sub_service_id',$subServiceId)
            ->get()->toArray();

        if(empty($serviceRecipients)){
            return [];
        }

        $commissions = [];
        foreach($serviceRecipients as $serviceRecipient){
            $serviceRecipientCommissions = ServiceCommission::where('service_recipient_id',$serviceRecipient['id'])
                ->get();
            if(!empty($serviceRecipientCommissions)){
                foreach($serviceRecipientCommissions as $serviceRecipientCommission){
                    $commissions[] = [
                        'commissions_recipient_id'=>(int)$serviceRecipientCommission->commissions_recipient_id,
                        'percent'=>floatval($serviceRecipientCommission->percent),
                        'min'=>floatval($serviceRecipientCommission->min),
                        'max'=>floatval($serviceRecipientCommission->max),
                        'fix'=>floatval($serviceRecipientCommission->fix),
                    ];
                }
            }
        }

        return $commissions;
    }
}