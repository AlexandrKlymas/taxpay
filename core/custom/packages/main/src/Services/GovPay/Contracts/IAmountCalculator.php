<?php
namespace EvolutionCMS\Services\Contracts;

interface IAmountCalculator
{
    public function calculate($formData):float;
}