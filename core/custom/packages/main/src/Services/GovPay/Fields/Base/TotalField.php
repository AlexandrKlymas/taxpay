<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class TotalField extends AbstractField implements IField
{
    private $title;
    private $placeholder;

    public static function build($title = 'До сплати',$placeholder= '0.00',$required = true)
    {
        return new self('total',$title,$placeholder,$required,[
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
            'placeholder' => $this->placeholder
        ]);
    }


    public function getViewFile(): string
    {
        return 'partials.services.fields.base.total';
    }
}