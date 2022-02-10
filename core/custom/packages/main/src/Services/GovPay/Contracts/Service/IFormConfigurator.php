<?php
namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;


use EvolutionCMS\Main\Services\GovPay\Contracts\IField;

interface IFormConfigurator
{
    /**
     * @return IField[]
     */
    public function getFormConfig(): array;

    public function renderFormFields():array;

    public function renderDataForPreview($fieldValues):array;
    public function getValidationRules():array;

    public function getFormFieldsValues($formData):array;
}