<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class DisabledTextField extends AbstractField implements IField
{

    private $title;
    private $placeholder;
    private $value;
    private $id;

    public function __construct($name, $title, $placeholder, $value, $id, $required = false, $rules = [],$validationMessages = [])
    {
        $this->name = $name;
        $this->title = $title;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->id = $id;

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
            'value'=>$this->value,
            'id'=>$this->id,
        ]);
    }


    public function getViewFile(): string
    {
        return 'partials.services.fields.base.disabled_text';
    }
}