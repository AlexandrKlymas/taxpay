<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\AbstractField;
use EvolutionCMS\Main\Services\GovPay\Models\Service;

class ServiceField extends AbstractField implements IField
{
    private $title;
    private $placeholder;
    private $serviceIds;
    private $services;
    /**
     * @var bool
     */
    private $showPrice;

    public static function buildField($title = 'Оберіть послугу',$phl = 'Оберуть..',$serviceIds = [],$required = true,$showPrice = true):IField
    {
        return new self('service',$title,$phl,$serviceIds,$required,$showPrice);
    }

    public function __construct($name, $title, $placeholder, $serviceIds = [], $required = true,$showPrice = true)
    {
        $rules = [];
        if ($required) {
            $rules[] = 'required';
        }

        $this->title = $title;
        $this->placeholder = $placeholder;
        $this->serviceIds = $serviceIds;
        $this->services = $this->getServices($serviceIds);
        $this->showPrice = $showPrice;

        parent::__construct($name, $rules);

    }

    public function getDataForRender($formData = []):array{
        return array_merge(parent::getDataForRender(),[
            'title' => $this->title,
            'placeholder' => $this->placeholder,
            'services' => $this->services,
            'showPrice' => $this->showPrice,
        ]);
    }


    public function getValues($formData): array
    {
        $serviceId = $formData[$this->name];
        $title = Service::findOrFail($serviceId)->name_ua;


        return [
            'service' => $serviceId,
            'service_title' => $title,
        ];
    }


    private function getServices(array $serviceIds)
    {
        $q = Service::orderBy('name_ua');
        if ($serviceIds) {
            $q->whereIn('type', $serviceIds);
        }
        return $q->get();
    }


}