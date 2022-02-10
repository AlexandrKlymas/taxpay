<?php
namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField as IFieldAlias;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\AbstractField;
use EvolutionCMS\Main\Services\GovPay\Models\MontcodeItem;

class SeparatorField  implements IFieldAlias
{

    private $title;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function getDataForRender($formData = []): array
    {
        return  [
            'title' => $this->title
        ];
    }

    public function getViewFile(): string
    {
        return 'partials.services.fields.separator';
    }

    public function getValidationRules(): array
    {
        return [];
    }

    public function getValues($formData): array
    {
        return [];
    }
}