<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class SumField extends AbstractField implements IField
{

    private string $title;
    private string $placeholder;
    private bool $hidden = false;
    private bool $disabled = false;
    private string $value='';
    private string $lang = '';
    private string $currency = 'грн';

    public static function build($title = 'Сума сплати',$placeholder= '0.00',$required = true)
    {
        return new self('sum',$title,$placeholder,$required,[
            'required',
            'min:1',
            'regex:~^(\d{1,}\.?\d?\d?)$~'
        ]);
    }

    public function __construct($name, $title, $placeholder, $required = true, $rules = [])
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
            'placeholder' => $this->placeholder,
            'hidden'=>$this->hidden,
            'value'=>$this->value,
            'disabled'=>$this->disabled,
            'lang'=>$this->lang,
            'currency'=>$this->currency,
        ]);
    }


    public function getViewFile(): string
    {
        return 'partials.services.fields.base.sum';
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

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): SumField
    {
        $this->lang = $lang;

        return $this;
    }

    public function getCurrency():string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency):SumField
    {
        $this->currency = $currency;

        return $this;
    }
}