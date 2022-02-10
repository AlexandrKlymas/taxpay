<?php
namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class SubtitleField implements IField
{

    private $title;

    public function __construct($title)
    {

        $this->title = $title;
    }

    public function getViewFile(): string
    {
        return 'partials.services.fields.subtitle';
    }

    public function getDataForRender($formData = []): array
    {
        return [
            'title'=> $this->title
        ];
    }

    public function getValidationRules(): array
    {
        return  [];
    }

    public function getValues($formData): array
    {
        return  [];
    }
}