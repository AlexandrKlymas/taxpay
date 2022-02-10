<?php

use EvolutionCMS\Main\Support\Helpers;
use Illuminate\Http\Request;

ini_set('memory_limit', '512M');


$evo  = \EvolutionCMS();

//$controllersNamespace = '\EvolutionCMS\Main\Modules\Orders\Controllers\\';
$controllersNamespace = '\EvolutionCMS\Main\Modules\OrdersArchive\Controllers\\';

$moduleUrl = 'index.php?a=112&id='.$_GET['id'].'&';
$action = isset($_GET['action']) ? $_GET['action'] : 'main:index';

$controllerCallable = Helpers::getCallableFromAction($action,$controllersNamespace);
$request = Request::createFromGlobals();

try {
    $response = call_user_func_array($controllerCallable,[$request]);
}
catch (Exception $e){
    $response = [
        'status'=>'error',
        'message'=>$e->getMessage()
    ];
}



if(is_array($response)){
    header('Content-type: text/json');
    echo json_encode($response);
}
else{
    echo $response;
}
