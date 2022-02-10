<?php
if(!isset($_SESSION['mgrValidated']) || !$modx->hasPermission('exec_module')){
    die();
}
$module_url = MODX_SITE_URL . 'assets/modules/webixtable/';
$idField = trim($idField);
$display = (int)trim($display) > 0 ? (int)trim($display) : 10;
$fields = explode(',', str_replace(', ', ',', trim($fields)));
$fields_names = explode(',', str_replace(', ', ',', trim($fields_names)));
$fields_for_popup_editor = explode(',', str_replace(', ', ',', trim($fields_for_popup_editor)));
$tpl = isset($tpl) && file_exists(MODX_BASE_PATH . '/assets/modules/webixtable/tpl/' . trim($tpl) . '.tpl') ? trim($tpl) : 'main';



$columns = array();
foreach ($fields as $k => $field) {
    switch (true) {
        case in_array($field, $fields_for_popup_editor):
            $editor = 'popup';
            break;
        case ($field == 'date' || preg_match('/^date_/', $field)):
            $editor = 'date';
            break;
        default:
            $editor = 'text';
            break;
    }
    $tmp = array('id' => $field, 'header' => array($fields_names[$k], array("content" => "serverFilter")), 'sort' => 'server', 'editor' => $editor, 'adjust' => true);
    if ($idField == $field) {
        unset($tmp['editor']);
    }
    $columns[] = $tmp;
}
$cols = json_encode($columns);
$module_id = $_GET['id'];

$plh = array(
        'module_id' => $module_id,
        'module_url' => $module_url,
        'idField' => $idField,
        'display' => $display,
        'cols' => $cols,
        'name' => $name,
		'tab_action' => $_REQUEST['tabaction'],
		'document_id' => $_REQUEST['documentid'],
		'service_id' => $_REQUEST['service'],
	    'region_id' => $_REQUEST['region'],
);

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);

$tpl = file_get_contents($module_url . 'tpl/' . $tpl . '.tpl', false, stream_context_create($arrContextOptions));
$output .= $modx->parseText($tpl, $plh);
echo $output;
