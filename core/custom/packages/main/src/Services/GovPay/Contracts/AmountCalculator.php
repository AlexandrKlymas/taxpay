<?php
namespace EvolutionCMS\Services\Contracts;

interface AmountCalculator
{
    public function calculate($formData):float;
}