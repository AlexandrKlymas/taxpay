<?php

namespace EvolutionCMS\Main\Services\GovPay\Fields;

use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Models\VvpayDetails;


class TwoRelatedSelectFields implements IField
{
    private array $fields=[];
    private array $relations;

    public function __construct(IField $firstSelect,IField $secondSelect, array $relations=[])
    {
        $this->fields[] = $firstSelect;
        $this->fields[] = $secondSelect;
        $this->relations = $relations;
    }

    public function getValues($formData): array
    {
        $formDataForSave = [];
        foreach ($this->fields as $field) {

            $fieldValue = $this->getRelatedValue(
                $field->getValues($formData)
            );

            $formDataForSave = array_merge($formDataForSave,$fieldValue??[]);
        }
        return $formDataForSave;
    }

    public function getDataForRender($formData = []): array
    {
        $renderedFields = [];
        $dataFields = [];

        foreach ($this->fields as $field) {
            $renderedFields[] = \View::make($field->getViewFile(), $field->getDataForRender());
            $dataFields[] = $field->getDataForRender([],false);
        }
//        dd($dataFields);
        return [
            'fields'=>$renderedFields,
            'relations'=>$this->relations,
            'data'=>[
                'fields'=>[
                    $dataFields[0]['fields'][0],
                    $dataFields[1]['fields'][0],
                ],
            ],
        ];
    }

    public function getViewFile(): string
    {
        return 'partials.services.fields.two_related_select';
    }

    public function getValidationRules(): array
    {
        $rules = [];
        foreach ($this->fields as $field) {
            $rules = array_merge($rules, $field->getValidationRules());
        }
        return $rules;
    }

    public function getRelatedValue($fieldValue)
    {
        if(isset($fieldValue['district_title'])){
            $fieldValue['district_title'] = VVPayDetails::findOrFail($fieldValue['district'])->district;
        }
        return $fieldValue;
    }
}