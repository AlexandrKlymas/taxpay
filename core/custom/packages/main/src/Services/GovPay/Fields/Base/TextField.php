<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class TextField extends AbstractField implements IField
{

    private string $title;
    private string $placeholder;
    private bool $hidden = false;
    private bool $disabled = false;
    private string $value='';

    public function __construct($name, $title, $placeholder, $required = true, $rules = [],$validationMessages = [])
    {


        $this->name = $name;
        $this->title = $title;
        $this->placeholder = $placeholder;

        if($required){
            $rules[] = 'required';
        }

        parent::__construct($name,$rules,$validationMessages);
    }

    public function getDataForRender($formData = []): array
    {
        return array_merge(parent::getDataForRender($formData),[
            'title' => $this->title,
            'placeholder' => $this->placeholder,
            'hidden'=>$this->hidden,
            'value'=>$this->value,
            'disabled'=>$this->disabled,
        ]);
    }


    public function getViewFile(): string
    {
        return 'partials.services.fields.base.text';
    }

    public function hide()
    {
        $this->hidden = true;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function disable()
    {
        $this->disabled = true;
    }
}