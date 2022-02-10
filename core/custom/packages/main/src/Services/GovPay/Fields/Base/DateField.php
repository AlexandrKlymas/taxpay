<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class DateField extends AbstractField implements IField
{

    private $title;
    private $placeholder;

    public function __construct($name, $title, $placeholder,$required = true, $rules = ['date_format:d.m.Y'])
    {
        $this->title = $title;
        $this->placeholder = $placeholder;


        if($required){
            $rules[] = 'required';
        }

        parent::__construct($name,$rules);
    }


    public function getDataForRender($formData = []): array
    {
        return array_merge(parent::getDataForRender($formData),[
            'title' => $this->title,
            'placeholder' => $this->placeholder
        ]);
    }

    public function getViewFile(): string
    {
        return 'partials.services.fields.base.date';
    }
}