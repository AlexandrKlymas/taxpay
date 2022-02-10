<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields\Base;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField as IFieldAlias;
use EvolutionCMS\Main\Services\GovPay\Support\FieldHelpers;
use EvolutionCMS\Main\Services\GovPay\Support\FieldJqueryValidatorRulesRender;
use EvolutionCMS\Main\Support\Helpers;

class AbstractField
{
    protected $name;
    protected $rules = [];
    protected $fieldJqueryValidatorRulesRender;
    private $validationMessages = [];


    public function __construct($name,$rules)
    {
        $this->name = $name;
        $this->fieldJqueryValidatorRulesRender = new FieldJqueryValidatorRulesRender();
        $this->setRules($rules);
    }

    public function getDataForRender($formData = []): array
    {
        $jQueryValidateAttributes = $this->fieldJqueryValidatorRulesRender->getAttributesForJqueryValidate($this->getValidationRules(),$this->getValidationMessages());

        return [
            'name' => $this->name,
            'validationAttributes' => $jQueryValidateAttributes
        ];
    }

    public function getValidationRules(): array
    {
        return [
            $this->name => $this->rules
        ];
    }

    public function getViewFile(): string
    {
        $classNameParts = explode('\\', get_class($this));
        $className = substr(end($classNameParts), 0, -5);



        $parts = array_map(function ($part) {
            return strtolower($part);
        }, array_filter(preg_split('/(?=[A-Z])/', $className)));

        return 'partials.services.fields.' . implode('_', $parts);
    }


    public function getValues($formData): array
    {
        return [
            $this->name => $formData[$this->name] ?? ''
        ];
    }

    private function setRules($rules){
        foreach ($rules as $key => $value) {

            if(is_numeric($key)){
                $this->rules[] = $value;
            }
            else{
                $ruleName = Helpers::parsedRule($key)['name'];

                $this->rules[] = $key;
                $this->validationMessages[$this->name.'.'.$ruleName] = $value;
            }
        }

    }

    private function getValidationMessages()
    {
        return $this->validationMessages;
    }

}