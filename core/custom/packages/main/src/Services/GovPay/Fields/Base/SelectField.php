<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class SelectField extends AbstractField implements IField
{

    private $title;
    private $placeholder;
    private $options;
    private $selected;

    public function __construct($name, $title, $placeholder,$options , $required = true, $rules = [])
    {

        $this->title = $title;
        $this->placeholder = $placeholder;
        $this->options = $options;

        if($required){
            $rules[] = 'required';
        }
        parent::__construct($name,$rules);

    }

    public function getValues($formData): array
    {
        $name = $this->name;
        $value = $formData[$this->name];

        return [
            $name => $value ?? '',
            $name.'_title' => $this->options[$value] ?? ''
        ];
    }



    public function getDataForRender($formData = []): array
    {
        return array_merge(parent::getDataForRender($formData),[
            'title' => $this->title,
            'placeholder' => $this->placeholder,
            'options' => $this->options,
            'selected'=>$this->selected
        ]);
    }


    public function getViewFile(): string
    {
        return 'partials.services.fields.base.select';
    }

    public function setSelected($value)
    {
        $this->selected = $value;
    }
}