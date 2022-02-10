<?php
namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SelectField;
use EvolutionCMS\Main\Services\GovPay\Models\Service;
use EvolutionCMS\Main\Services\GovPay\Models\TerritorialServiceCenter;
use Illuminate\Http\Request;

class TerritorialServiceCenterField extends SelectField implements IField
{
    public static function build($title = 'ТСЦ вашої обасті',$phl = 'Оберіть..')
    {
        return new self('territorial_service_center', $title, $phl, []);
    }


    public static function getServices(Request $request)
    {

        return TerritorialServiceCenter::select(['id', 'name_ua as name'])
            ->where('region_id', $request->get('region'))
            ->where('name_ua','not like','РСЦ%')
            ->get()
            ->toArray();
    }

    public function getValues($formData): array
    {
        $value = $formData[$this->name];
        $title = TerritorialServiceCenter::findOrFail($value)->name_ua;


        return [
            $this->name => $value,
            $this->name.'_title' => $title,
        ];
    }

}