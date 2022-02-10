<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class TotalCaptionField extends AbstractField implements IField
{
    private $title;
    private $placeholder;

    public static function build($title = 'До сплати')
    {
        return new self($title);
    }

    public function __construct($title)
    {
        $this->title = $title;
        parent::__construct('',[]);
    }

    public function getDataForRender($formData = []): array
    {
        return array_merge(parent::getDataForRender($formData),[
            'title' => $this->title,
        ]);
    }


    public function getViewFile(): string
    {
        return 'partials.services.fields.base.total_caption';
    }
}