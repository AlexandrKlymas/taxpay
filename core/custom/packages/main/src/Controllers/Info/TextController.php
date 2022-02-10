<?php
namespace EvolutionCMS\Main\Controllers\Info;

use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\GovPay\Lists\MVS\PoliceProtection\Test;
use EvolutionCMS\Main\Support\Helpers;
use Illuminate\Support\Facades\Session;

class TextController extends BaseController
{
    public function render()
    {

        $content = json_decode($this->evo->documentObject['content_builder'][1],true);
        $this->data['content'] = Helpers::multiFields($content);
    }
}