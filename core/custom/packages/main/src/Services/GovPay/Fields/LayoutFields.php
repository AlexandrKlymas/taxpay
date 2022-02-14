<?php
namespace EvolutionCMS\Main\Services\GovPay\Fields;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

class LayoutFields implements IField
{
    /**
     * @var $fields IField[]
     */
    private $fields;


    public function __construct($fields = [])
    {
        $this->fields = $fields;
    }

    public function getViewFile(): string
    {
        return 'partials.services.fields.layout';
    }

    public function getDataForRender($formData = [], bool $view = true): array
    {
        $renderedFields = [];
        foreach ($this->fields as $field) {
            if($view){
                $renderedFields[] = \View::make($field->getViewFile(), $field->getDataForRender());
            }else{
                $renderedFields[] = $field->getDataForRender();
            }
        }
        return [
            'fields'=>$renderedFields
        ];
    }

    public function getValidationRules(): array
    {
        $rules = [];
        foreach ($this->fields as $field) {
            $rules = array_merge($rules, $field->getValidationRules());
        }
        return $rules;
    }

    public function getValues($formData): array
    {
        $formDataForSave = [];
        foreach ($this->fields as $field) {
            $formDataForSave = array_merge($formDataForSave,$field->getValues($formData));
        }
        return $formDataForSave;
    }
}