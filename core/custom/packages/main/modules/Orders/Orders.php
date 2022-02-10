<?php
ini_set('memory_limit', '512M');


$evo  = \EvolutionCMS();

$controllersNamespace = '\EvolutionCMS\Main\Modules\Orders\Controllers\\';

$moduleUrl = 'index.php?a=112&id='.$_GET['id'].'&';
$action = isset($_GET['action']) ? $_GET['action'] : 'main:index';


$controllerCallable = \EvolutionCMS\Main\Support\Helpers::getCallableFromAction($action,$controllersNamespace);
$request = \Illuminate\Http\Request::createFromGlobals();
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
