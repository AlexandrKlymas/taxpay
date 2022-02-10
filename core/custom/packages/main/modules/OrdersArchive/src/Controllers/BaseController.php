<?php
namespace EvolutionCMS\Main\Modules\OrdersArchive\Controllers;


class BaseController
{

    protected $viewData;
    protected $evo;


    public function __construct()
    {
        $this->evo = \EvolutionCMS();

        $this->viewData = [
            'managerTheme' => $this->evo->getConfig('manager_theme'),
            'moduleUrl' => 'index.php?a=112&id=' . $_GET['id'] . '&',
            'moduleName' => 'Платежы'
        ];
    }
}