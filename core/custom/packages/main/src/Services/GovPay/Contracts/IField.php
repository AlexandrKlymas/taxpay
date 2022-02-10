<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts;

interface IField
{
    public function getViewFile(): string;

    public function getDataForRender($formData=[]): array;

    public function getValidationRules(): array;

    public function getValues($formData): array;


}