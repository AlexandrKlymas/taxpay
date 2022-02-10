<?php

use EvolutionCMS\Main\Models\DLSiteContent;
use EvolutionCMS\Main\Support\BLangModelHelper;
use EvolutionCMS\Main\Support\Helpers;

$filesystem = new Illuminate\Filesystem\Filesystem;
$dir = EVO_CORE_PATH . 'custom/packages/main/modules/medcenters/views';

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
$data['medCenters'] = \EvolutionCMS\Main\Models\MedicalCenter::all()->toArray();

switch ($action) {
    case "medical_center":

        if (isset($_POST['name'])) {
            \EvolutionCMS\Main\Models\MedicalCenterUser::query()->create(
                [
                    'name' => $_POST['name'],
                    'phone' => preg_replace("/[^0-9]/", '', $_POST['phone']),
                    'status' => 1,
                    'medical_center_id' => $_POST['medical_center_id']
                ]
            );
        }

        if (isset($_POST['new_name'])) {
            $medCenter = \EvolutionCMS\Main\Models\MedicalCenter::query()->find($_POST['id']);
            if (!is_null($medCenter)) {
                $medCenter->name = $_POST['new_name'];
                $medCenter->save();
            }
            exit();
        }
        if (isset($_POST['delete_center_id'])) {
            \EvolutionCMS\Main\Models\MedicalCenterUser::query()->where(
                'medical_center_id',
                $_POST['delete_center_id']
            )->update(['status' => 0, 'medical_center_id' => 0]);
            $medCenter = \EvolutionCMS\Main\Models\MedicalCenter::query()->where('id', $_POST['delete_center_id'])->delete();

            exit();
        }
        if (isset($_POST['delete_user_id'])) {

            $medCenter = \EvolutionCMS\Main\Models\MedicalCenterUser::query()->where('id', $_POST['delete_user_id'])->delete();

            exit();
        }
        if (isset($_POST['userid'])) {
            $user = \EvolutionCMS\Main\Models\MedicalCenterUser::query()->find($_POST['userid']);

            $user->status = $_POST['status'];
            $user->save();
            exit();
        }
        $data['users'] = \EvolutionCMS\Main\Models\MedicalCenterUser::query()
            ->where(
                'medical_center_id',
                $_GET['medical_center_id']
            )->get()->toArray();

        $data['medicalCenter'] = \EvolutionCMS\Main\Models\MedicalCenter::query()->find(
            $_GET['medical_center_id']
        )->toArray();
        break;
    case "user_without_centers":
        if (isset($_POST['medcenter'])) {
            $user = \EvolutionCMS\Main\Models\MedicalCenterUser::query()->find($_POST['userid']);
            $user->medical_center_id = $_POST['medcenter'];
            $user->status = 1;
            $user->save();
        }
        $data['usesWithoutCenters'] = \EvolutionCMS\Main\Models\MedicalCenterUser::query()
            ->where(
                'medical_center_id',
                '=',
                0
            )->get()->toArray();

        break;

    default:
        if (isset($_POST['name'])) {
            \EvolutionCMS\Main\Models\MedicalCenter::query()->create(['name' => $_POST['name']]);
        }

        break;
}

echo \Illuminate\Support\Facades\View::make('main', $data);
