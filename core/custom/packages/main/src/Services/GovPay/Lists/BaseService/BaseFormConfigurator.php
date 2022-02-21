<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;

abstract class BaseFormConfigurator implements IFormConfigurator
{
    protected array $serviceConfig = [];

    public function __construct(array $serviceConfig)
    {
        $this->serviceConfig = $serviceConfig;

    }

    /**
     * @return IField[]
     */
    abstract public function getFormConfig(): array;

    public function renderFormFields(array $formConfig = []): array
    {
        $renderedFields = [];
        foreach ($this->getFormConfig() as $field) {
            $renderedFields[] = \View::make($field->getViewFile(), $field->getDataForRender());
        }

        return $renderedFields;
    }

    public function getValidationRules():array
    {
        $rules = [];
        foreach ($this->getFormConfig() as $field) {
            $rules = array_merge($rules, $field->getValidationRules());
        }
        return $rules;
    }

    public function getFormFieldsValues($formData):array
    {
        $formDataForSave = [];
        foreach ($this->getFormConfig() as $field) {
            $formDataForSave = array_merge($formDataForSave,$field->getValues($formData));
        }
        return $formDataForSave;
    }
}