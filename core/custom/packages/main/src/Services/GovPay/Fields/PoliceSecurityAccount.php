<?php


namespace EvolutionCMS\Services\Fields;


use EvolutionCMS\Services\Contracts\IField;

class PoliceSecurityAccount implements IField
{

    public function __construct($required, $config = [])
    {
    }

    public function getViewFile(): string
    {
        return 'partials.services.fields.police_security_account';
    }

    public function getDataForRender($formData = []): array
    {
        return  [];
    }

    public function getValidationRules($formData): array
    {
        return  [];
    }

    public function getDataForPreview($formData): array
    {
        return  [];
    }

    public function getDataForSave($formData): array
    {
        return  [];
    }
}