<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class TotalCaptionField extends AbstractField implements IField
{
    protected string $title;
    protected float $value;

    public static function build($title = 'До сплати', float $value = 0.00): TotalCaptionField
    {
        $field = new self($title);
        $field->setValue($value);

        return $field;
    }

    public function __construct(string $title)
    {
        $this->title = $title;
        parent::__construct('', []);
    }

    public function getDataForRender($formData = []): array
    {
        return array_merge(parent::getDataForRender($formData), [
            'title' => $this->title,
            'value' => $this->value,
        ]);
    }

    public function getViewFile(): string
    {
        return 'partials.services.fields.base.total_caption';
    }

    public function setValue(float $value)
    {
        $this->value = $value;
    }
}