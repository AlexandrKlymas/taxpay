<?php

namespace EvolutionCMS\Main\Services\CarInsurance;

class RenderHelper
{
    public function renderCarInfoForm(array $array)
    {
        return \View::make('partials.form.insurance.osago',[
            'form'=>$array,
        ])->toHtml();
    }

    public function renderLicensePlate(array $array)
    {
        return \View::make('partials.form.insurance.licensePlate',[
            'form'=>$array,
        ])->toHtml();
    }

    public function renderInsuranceForm(array $array)
    {
        return \View::make('partials.form.insurance.insurance',$array)->toHtml();
    }

    public function renderPrograms(array  $array)
    {
        return \View::make('partials.services.car_insurance.osago_programs',$array)->toHtml();
    }

    public function renderContract(array  $array)
    {
        return \View::make('partials.services.car_insurance.contractInfo',$array)->toHtml();
    }

    public function renderPayForm(array  $array)
    {
        return \View::make('partials.form.insurance.payment',$array)->toHtml();
    }

    public function renderPayError(array  $array)
    {
        return \View::make('partials.services.car_insurance.osago_error',$array)->toHtml();
    }
}