<?php

use EvolutionCMS\Main\Models\DLSiteContent;
use EvolutionCMS\Main\Models\RegistryOffice;
use EvolutionCMS\Main\Models\RegistryOfficeUser;
use EvolutionCMS\Main\Services\GovPay\Models\SubServices;
use EvolutionCMS\Main\Support\BLangModelHelper;


$filesystem = new Illuminate\Filesystem\Filesystem;
$dir = EVO_CORE_PATH . 'custom/packages/main/modules/registry_offices/views';

$viewFinder = new Illuminate\View\FileViewFinder($filesystem, [$dir]);
\Illuminate\Support\Facades\View::setFinder($viewFinder);
$action = '';


$moduleurl = 'index.php?a=112&id=' . $_GET['id'] . '&';
$action = $_GET['action'] ?? 'main';
$tab = $_GET['tab'] ?? 'main';
if ($tab != 'main' && $action == 'main') {
    $action = $tab;
}

$data = [
    'module_url' => $moduleurl,
    'manager_theme' => EvolutionCMS()->config['manager_theme'],
    'action' => $action,
    'tab' => $tab,
];

$data['registryOffices'] = SubServices::where('service_id',176)->get()->keyBy('id')->toArray();

$officesIds = array_column(RegistryOfficeUser::get(['registry_office_id'])->toArray(),'registry_office_id');

foreach($officesIds as $officesId){
    if(empty(SubServices::find($officesId))){
        RegistryOfficeUser::where('registry_office_id',$officesId)
            ->update(['registry_office_id'=>0]);
    }
}

switch ($action) {
    case "registry_office":

        if (isset($_POST['name'])) {
            RegistryOfficeUser::query()->create(
                [
                    'name' => $_POST['name'],
                    'phone' => preg_replace("/[^0-9]/", '', $_POST['phone']),
                    'status' => 1,
                    'registry_office_id' => $_POST['registry_office_id']
                ]
            );
        }

        if (isset($_POST['new_name'])) {
            SubServices::where('id',$_POST['id'])->update([
                'name'=>$_POST['new_name']
            ]);
            exit();
        }
        if (isset($_POST['delete_office_id'])) {
            RegistryOfficeUser::query()->where(
                'registry_office_id',
                $_POST['delete_office_id']
            )->update(['status' => 0, 'registry_office_id' => 0]);

            exit();
        }
        if (isset($_POST['delete_user_id'])) {

            $registryOffice = RegistryOfficeUser::query()->where('id', $_POST['delete_user_id'])->delete();

            exit();
        }
        if (isset($_POST['userid'])) {
            $user = RegistryOfficeUser::query()->find($_POST['userid']);

            $user->status = $_POST['status'];
            $user->save();
            exit();
        }
        $data['users'] = RegistryOfficeUser::query()
            ->where(
                'registry_office_id',
                $_GET['registry_office_id']
            )->get()->toArray();

        $data['registryOffice'] = SubServices::find($_GET['registry_office_id'])->toArray();
        break;
    case "user_without_offices":
        if (isset($_POST['office'])) {
            $up = RegistryOfficeUser::where('id',$_POST['userid'])
                ->update([
                    'registry_office_id'=>$_POST['office'],
                    'status'=>1,
                ]);
        }
        $data['userWithoutOffices'] = RegistryOfficeUser::query()
            ->where(
                'registry_office_id',
                '=',
                0
            )->get()->toArray();

        break;
}

echo \Illuminate\Support\Facades\View::make('main', $data);
