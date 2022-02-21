<?php
namespace EvolutionCMS\Services\Contracts;

interface IAmountCalculator
{
    public function calculate(array $formData):float;
}