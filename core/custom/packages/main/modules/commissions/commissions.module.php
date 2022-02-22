<?php

use EvolutionCMS\Main\Models\DLSiteContent;
use EvolutionCMS\Main\Services\GovPay\Lists\ServicesAlias;
use EvolutionCMS\Main\Services\GovPay\Models\CommissionsRecipients;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceCommission;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\SubServices;
use EvolutionCMS\Main\Support\BLangModelHelper;
use EvolutionCMS\Models\SiteContent;


$filesystem = new Illuminate\Filesystem\Filesystem;
$dir = EVO_CORE_PATH . 'custom/packages/main/modules/commissions/views';
$modulePath = EVO_CORE_PATH . 'custom/packages/main/modules/commissions/';

$viewFinder = new Illuminate\View\FileViewFinder($filesystem, [$dir]);
\Illuminate\Support\Facades\View::setFinder($viewFinder);
$action = '';

$moduleUrl = 'index.php?a=112&id=' . $_GET['id'] . '&';
$action = $_GET['action'] ?? 'services';

$data = [
    'module_url' => $moduleUrl,
    'manager_theme' => EvolutionCMS()->config['manager_theme'],
    'action' => $action,
    'module_path'=>$modulePath,
    'recipient_types'=>[
        PaymentRecipient::RECIPIENT_GOVPAY_PROFIT,
        PaymentRecipient::RECIPIENT_TK_COMMISSION,
    ]
];

$data['breadcrumbs'] = [];

switch ($action) {
    case 'services':
        $services = SiteContent::whereIn('id',array_keys((new ServicesAlias())
            ->getServicesList()))->get(['id','pagetitle'])->keyBy('id')->toArray();
        foreach($services as $k=>$service){
            $services[$k]['sub_services'] = SubServices::where('service_id',$service['id'])
                ->get()->toArray();
        }
        $data['services'] = $services;
        $data['breadcrumbs'][] = ['url'=>$moduleUrl.'action='.$action,'title'=>'Сервіси'];
        $data['commissions_recipients'] = CommissionsRecipients::all()->keyBy('id')->toArray();
        break;

    case 'commissions_recipients':
        $data['commissions_recipients'] = CommissionsRecipients::all()->keyBy('id')->toArray();
        $data['breadcrumbs'][] = ['url'=>$moduleUrl.'action=services','title'=>'Сервіси'];
        $data['breadcrumbs'][] = ['url'=>'','title'=>'Отримувачі комісій'];
        break;
    case 'delete_commissions_recipients':
        CommissionsRecipients::find($_POST['id'])->delete();
        exit();
    case 'service':
        $data['service'] = SiteContent::where('id',$_GET['service_id'])->first(['id','pagetitle'])->toArray();
        $data['sub_services'] = SubServices::where('service_id',$_GET['service_id'])
            ->get()->keyBy('id')->toArray();
        $data['breadcrumbs'][] = ['url'=>$moduleUrl.'action=services','title'=>'Сервіси'];
        $data['breadcrumbs'][] = ['url'=>'','title'=>$data['service']['pagetitle']];
        $data['service_recipients'] = ServiceRecipient::where('service_id',$_GET['service_id'])
            ->whereNull('sub_service_id')
            ->get()->keyBy('id')->toArray();
        break;
    case 'sub_service':
        $data['service'] = SiteContent::where('id',$_GET['service_id'])->first(['id','pagetitle'])->toArray();
        $data['sub_service'] = SubServices::find($_GET['sub_service_id'])->toArray();
        $data['breadcrumbs'][] = ['url'=>$moduleUrl.'action=services','title'=>'Сервіси'];
        $data['breadcrumbs'][] = ['url'=>$moduleUrl.'action=service'.'&service_id='.$data['service']['id'],'title'=>$data['service']['pagetitle']];
        $data['breadcrumbs'][] = ['url'=>'','title'=>$data['sub_service']['name']];
        $data['sub_service_recipients'] = ServiceRecipient::where('sub_service_id',$_GET['sub_service_id'])
            ->get()->keyBy('id')->toArray();
        break;
    case 'add_sub_service':
        SubServices::create(['service_id'=>$_POST['service_id'],'name'=>$_POST['name']]);
        exit();
    case 'edit_sub_service':
        SubServices::find($_POST['id'])->update(['name'=>$_POST['name']]);
        exit();
    case 'delete_sub_service':
        SubServices::find($_POST['id'])->delete();
        exit();
    case 'add_service_recipient':
        $request = [
            'service_id'=>$_POST['service_id'],
            'recipient_name'=>$_POST['recipient_name'],
            'edrpou'=>$_POST['edrpou'],
            'mfo'=>$_POST['mfo'],
            'iban'=>$_POST['iban'],
            'purpose_template'=>$_POST['purpose_template'],
            'sum'=>(float)$_POST['sum'],
        ];
        if(!empty($_POST['sub_service_id'])){
            $request['sub_service_id'] = $_POST['sub_service_id'];
        }
        ServiceRecipient::create($request);
        exit();

    case 'edit_service_recipient':
        CommissionsRecipients::where('id',$_POST['id'])
            ->update([
                'recipient_name'=>$_POST['recipient_name'],
                'edrpou'=>$_POST['edrpou'],
                'mfo'=>$_POST['mfo'],
                'iban'=>$_POST['iban'],
                'purpose_template'=>$_POST['purpose_template'],
                'recipient_type'=>$_POST['recipient_type'],
            ]);
        exit();

    case 'edit_service_recipient_edit':
        ServiceRecipient::where('id',$_POST['id'])
            ->update([
                'recipient_name'=>$_POST['recipient_name'],
                'edrpou'=>$_POST['edrpou'],
                'mfo'=>$_POST['mfo'],
                'iban'=>$_POST['iban'],
                'purpose_template'=>$_POST['purpose_template'],
                'sum'=>$_POST['sum'],
            ]);
        exit();

    case 'delete_service_recipient':
        ServiceRecipient::find($_POST['id'])->delete();
        ServiceCommission::where('service_recipient_id',$_POST['id'])->delete();
        exit();

    case 'add_service_recipient_commission':
        ServiceCommission::create([
            'service_recipient_id'=>$_POST['service_recipient_id'],
            'commissions_recipient_id'=>$_POST['commissions_recipient_id'],
            'percent'=>$_POST['percent'],
            'min'=>$_POST['min'],
            'max'=>$_POST['max'],
            'fix'=>$_POST['fix'],
        ]);
        exit();
    case 'edit_service_recipient_commission':
        ServiceCommission::where('id',$_POST['id'])->update([
            'percent'=>$_POST['percent'],
            'min'=>$_POST['min'],
            'max'=>$_POST['max'],
            'fix'=>$_POST['fix'],
        ]);
        exit();
    case 'delete_service_recipient_commission':
        ServiceCommission::where('id',$_POST['id'])->delete();
        exit();

    case 'service_recipient':
    case 'sub_service_recipient':
        $data['service_recipient'] = ServiceRecipient::find($_GET['service_recipient_id'])->toArray();
        $data['service'] = SiteContent::where('id',$data['service_recipient']['service_id'])->first(['id','pagetitle'])->toArray();
        $data['breadcrumbs'][] = ['url'=>$moduleUrl.'action=services','title'=>'Сервіси'];
        $data['breadcrumbs'][] = ['url'=>$moduleUrl.'action=service'.'&service_id='.$data['service']['id'],'title'=>$data['service']['pagetitle']];

        if(!empty($data['service_recipient']['sub_service_id'])){
            $data['sub_service'] = SubServices::find($data['service_recipient']['sub_service_id'])->toArray();
            $data['breadcrumbs'][] = ['url'=>$moduleUrl.'action=sub_service'.'&service_id='.$data['service']['id'].'&sub_service_id='.$data['sub_service']['id'],'title'=>$data['sub_service']['name']];
        }
        $data['breadcrumbs'][] = ['url'=>'','title'=>$data['service_recipient']['recipient_name']];
        $data['service_commissions'] = ServiceCommission::where('service_recipient_id',$data['service_recipient']['id'])
            ->get()->toArray();
        $data['commissions_recipients'] = CommissionsRecipients::all()->keyBy('id')->toArray();
        break;

    case 'add_service_commission_recipient':
        CommissionsRecipients::create([
            'recipient_name'=>$_POST['recipient_name'],
            'edrpou'=>$_POST['edrpou'],
            'mfo'=>$_POST['mfo'],
            'iban'=>$_POST['iban'],
            'purpose_template'=>$_POST['purpose_template'],
            'recipient_type'=>$_POST['recipient_type'],
        ]);
        exit();

    default:

        break;
}

echo \Illuminate\Support\Facades\View::make($action, $data);
