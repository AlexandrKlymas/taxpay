<?php

namespace EvolutionCMS\Main\Controllers;

use DocumentParser;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Support\Facades\Cache;

class BaseController
{
    public $data = [];
    protected DocumentParser $evo;

    public function __construct()
    {
        $this->evo = EvolutionCMS();

        if ($this->evo->documentIdentifier) {
            ksort($_GET);
            $cacheId = md5(json_encode($_GET));
            if ($this->evo->getConfig('enable_cache')) {
                $this->data = Cache::rememberForever($cacheId, function () {
                    $this->globalElements();
                    $this->render();
                    return $this->data;
                });
            } else {
                $this->globalElements();
                $this->render();
            }
            $this->noCacheRender();
            $this->sendToView();
        }
    }

    public function render()
    {

    }

    public function noCacheRender()
    {

    }

    public function globalElements()
    {
        $this->data['config'] = $this->getConfig();
        $this->data['menu'] = $this->getMenu();

    }

    public function sendToView()
    {
        $this->evo->addDataToView($this->data);
    }

    private function getConfig()
    {
        $config = [];

        $defaultCOnfig = [
            'site_start', 'site_name', 'site_url'
        ];
        foreach ($this->evo->config as $key => $value) {
            if (strpos($key, 'g_') === 0 || in_array($key, $defaultCOnfig)) {
                $config[$key] = $value;
            }
        }

        return $config;
    }

    private function getMenu()
    {
        $menu = SiteContent::where('site_content.hidemenu', '=', '0')
            ->where('site_content.published', 1)
            ->where('t2.published', 1)
            ->where('site_content.deleted', 0)
            ->where('t2.deleted', 0)
            ->where('t2.hidemenu', '=', '0')
            ->getRootTree(2)
            ->orderBy('site_content.menuindex')
            ->orderBy('t2.menuindex')
            ->get()
            ->toTree()
            ->toArray();

        return $menu;
    }
}
