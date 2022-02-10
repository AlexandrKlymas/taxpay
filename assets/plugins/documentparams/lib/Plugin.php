<?php namespace DocumentParams;
include_once (MODX_BASE_PATH . 'assets/lib/SimpleTab/plugin.class.php');
global $modx_lang_attribute;

class Plugin extends \SimpleTab\Plugin {
    public $table = 'site_content';
    public $pluginName = 'DocumentParams';
    public $documentId;
    public $tabName;
    public $moduleId;
    public $tabAction;
    public $serviceId;
    public $regionId;
    public $tpl = '';
    public $email = '';
    public $tabContent = '';
    public $jsListDefault = 'assets/plugins/documentparams/js/script.json';
    public $emptyTpl = 'assets/plugins/documentparams/tpl/empty.tpl';

    public function checkTable()
    {
        return true;
    }

    public function addDocumentPramas($id, $tabName, $moduleId, $tabAction, $serviceId = '', $regionId = '', $email = '')
    {
        if(!empty($id)){
            $this->documentId = $id;
            $this->tabName = $tabName;
            $this->moduleId = $moduleId;
            $this->tabAction = $tabAction;
            $this->serviceId = $serviceId;
            $this->regionId = $regionId;
            $this->email = $email;

            switch ($id) {
                case 70:
                    $this->tabContent = '<a class="btn btn-info" href="index.php?a=3&r=1&id=2"><span class="fa fa-cogs"></span> Перейти в настройки</a>';
                    $this->tpl = 'assets/plugins/documentparams/tpl/documentinput.tpl';
                    break;
                default:
                    $this->tabContent = '<a class="btn btn-info" href="index.php?a=112&id='.$this->moduleId.'"><span class="fa fa-cogs"></span> Перейти в модуль </a>';
                    $this->tpl = 'assets/plugins/documentparams/tpl/documentparams.tpl';
                    break;
            }
        }

    }

    public function tabNameById()
    {

    }

    public  function getTplPlaceholders()
    {
        $ph = array(
            'tabName' => $this->tabName,
            'tabContent' => $this->tabContent,
            'moduleId' => $this->moduleId,
            'tabAction' => $this->tabAction,
            'documentId' => $this->documentId,
            'serviceId' => $this->serviceId,
            'regionId' => $this->regionId,
            'email' => $this->email
        );
        return array_merge($this->params,$ph);
    }
}