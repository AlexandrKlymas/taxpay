<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class RadioField extends AbstractField implements IField
{

    private $title;
    private $options;
    /**
     * @var null
     */
    private $checked;

    public function __construct($name, $title,$options, $checked = null, $required = true, $rules = [])
    {

        $this->title = $title;
        $this->options = $options;

        if($required){
            $rules[] = 'required';
        }
        parent::__construct($name,$rules);

        $this->checked = $checked;
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
            'checked' => $this->checked,
            'options' => $this->options,
        ]);
    }


    public function getViewFile(): string
    {
        return 'partials.services.fields.base.radio';
    }
}