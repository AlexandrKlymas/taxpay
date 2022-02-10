<?php


namespace EvolutionCMS\Main\Services\GovPay\Support;


use EvolutionCMS\Main\Support\Helpers;
use Illuminate\Validation\Rule;

class FieldJqueryValidatorRulesRender
{
    public  function getAttributesForJqueryValidate($fields,$validationMessages){

        $attributes = [];


        foreach ($fields as $fieldName => $rules) {
            $fieldValidationRules = [];
            $otherAttributes = [];

            foreach ($rules as $rule) {

                if(empty($rule) || !is_string($rule) ){
                    continue;
                }

                $parsedRule = Helpers::parsedRule($rule);

                $method = 'rule'.ucfirst($parsedRule['name']);

                if(method_exists($this,$method)){

                    $methodCallParams = [
                        $parsedRule['params']
                    ];
                    if(isset($validationMessages[$fieldName.'.'.$parsedRule['name']])){
                        $methodCallParams[] = $validationMessages[$fieldName.'.'.$parsedRule['name']];
                    }


                    $classAndAttributes = $this->{$method}(...$methodCallParams);

                    if(isset($classAndAttributes['rules'])){
                        $fieldValidationRules[] = $classAndAttributes['rules'];
                    }
                    if(isset($classAndAttributes['attributes'])){
                        $otherAttributes[] = $classAndAttributes['attributes'];
                    }
                }
            }
            $attributes[$fieldName] = '';

            if($fieldValidationRules){
                $attributes[$fieldName] = 'data-validation="'.implode(' ',$fieldValidationRules).'"';
            }
            if($fieldValidationRules){
                $attributes[$fieldName] .= ' '.implode(' ',$otherAttributes);
            }
        }

        return $attributes;
    }

    private function ruleRegex($params,$title = 'Невірний формат'){
        return [
            'rules'=>'tochno',
            'attributes'=>'data-validation-pattern="'.$this->clearPattern($params[0]).'" data-validation-errormsg="'.$title.'"'

        ];
    }
    private function ruleEmail($params,$title = 'Сума менше мінімальної'){

        return [
            'rules'=>'email',
//            'attributes'=>'data-validation-minsumm="'.$params[0].'" data-validation-errormsgminsumm="'.$title.'" '
        ];
    }
    private function ruleMin($params,$title = 'Сума менше мінімальної'){

        return [
            'rules'=>'minsumm',
            'attributes'=>'data-validation-minsumm="'.$params[0].'" data-validation-errormsgminsumm="'.$title.'" '
        ];
    }


    private function ruleDate_format($params){
        $map = [
            'd.m.Y' => 'dd.mm.yyyy',
        ];
        $format = array_key_exists($params[0],$map)?$map[$params[0]]:'';
        return [
            'rules'=>'date',
            'attributes'=>'data-validation-format="'.$format.'" '
        ];
    }
    private function ruleNot_regex($params,$title = 'Використано неприпустимі символи'){


        return [
            'rules'=>'checkpattern',
            'attributes'=>'data-validation-pattern="'.$this->clearPattern($params[0]).'" data-validation-errormsg="'.$title.'"'
        ];
    }
    private function ruleRequired($params){
        return [
            'rules'=>'required',
        ];
    }

    private function clearPattern($pattern)
    {

        $firstChar = mb_substr($pattern,0,1);
        $lastSymbol = strripos($pattern,$firstChar);


        $pattern= substr($pattern,1,($lastSymbol-1));

        return $pattern;
    }

}