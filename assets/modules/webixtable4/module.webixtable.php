<?php
if(!isset($_SESSION['mgrValidated']) || !$modx->hasPermission('exec_module')){
    die();
}

$module_url = MODX_SITE_URL . 'assets/modules/webixtable4/';
$idField = trim($idField);
$display = (int)trim($display) > 0 ? (int)trim($display) : 10;

if(empty($output))$output = '';

if (!empty($_REQUEST['create_date_from']) && !empty($_REQUEST['create_date_to'])) {
	//при фильтрации по дате показываем 500 документы на одной странице
	$display = 500;
}	
$fields = explode(',', str_replace(', ', ',', trim($fields)));
$fields_names = explode(',', str_replace(', ', ',', trim($fields_names)));
if (isset($fields_modalform) && isset($fields_modalform_names)) {
    $fields_modalform = explode(',', str_replace(', ', ',', trim($fields_modalform)));
    $fields_modalform_names = explode(',', str_replace(', ', ',', trim($fields_modalform_names)));
} else {
    $fields_modalform = array();
}
if (count($fields_modalform) == 0) {
    $fields_modalform = $fields;
    $fields_modalform_names = $fields_names;
}

$fields_for_popup_editor = explode(',', str_replace(', ', ',', trim($fields_for_popup_editor)));
$fields_for_selector_filter = explode(',', str_replace(', ', ',', trim($fields_for_selector_filter)));
$fields_readonly = explode(',', str_replace(', ', ',', trim($fields_readonly??'')));
$fields_readonly[] = $idField;
$tpl = isset($tpl) && file_exists(MODX_BASE_PATH . '/assets/modules/webixtable4/tpl/' . trim($tpl) . '.tpl') ? trim($tpl) : 'payment';
$inline_edit = isset($inline_edit) && $inline_edit == '1' ? 'true' : 'false';
$modal_edit_btn = isset($modal_edit) && $modal_edit == '1' ? '{ view:"button", type:"iconButton", icon:"pencil",  label:"<i>Правка</i>", width:110, click:"edit_row" },' : '';
/*$status_array = array('wait' => 'Ожидает оплаты', 'sandbox' => 'Тестовый платеж', 'success' => 'Отправлено', 'error' => 'Забраковано (error)', 'failure' => 'Забраковано (failure)', 'reversed' => 'Платеж возвращен', 'submitted' => 'Подверждено', 'question' => 'Нестандартная ситуация', 'ready' => 'Проведено');*/
//'sandbox' => 'Тестовий платіж', 
//$status_array = array('wait' => 'Очікує оплати', 'success' => 'Сплачено', 'error' => 'Забраковано (error)',
//    'failure' => 'Забраковано (failure)', 'reversed' => 'Платіж повернуто', 'submitted' => 'Підтверджено',
//    'question' => 'Нестандартная ситуація', 'ready' => 'Проведено');
$status_array = [
    1 => 'Ожидание оплаты',
    2 => 'Оплачено',
    3 => 'Ошибка',
];
$select = array();
$select[0] = json_decode(json_encode(array('id' => '', 'value' => '')), FALSE);
$i = 1;
foreach ($status_array as $k => $v) {
	$select[$i] = json_decode(json_encode(array('id' => $k, 'value' => $v)), FALSE);
	$i++;
}

$select_forms = array();
//$q = $modx->db->query("SELECT id,pagetitle FROM " . $modx->getFullTableName("site_content") . " WHERE template=14");
$q = $modx->db->query("SELECT id,pagetitle FROM " . $modx->getFullTableName("site_content")
    . " WHERE parent=1 AND template IN (13,14) AND id<>65 AND published=1");
$select_forms[0] = json_decode(json_encode(array('id' => '', 'value' => '')), FALSE);
$i = 1;
while ($row = $modx->db->getRow($q)) {
	$shortName = $modx->runSnippet('DocInfo', array('docid' => (int)$row['id'], 'field' => 'form_name'));
	$select_forms[$i] = json_decode(json_encode(array('id' => (int)$row['id'], 'value' => $shortName)), FALSE);
	//$select_forms[$i] = json_decode(json_encode(array('id' => (int)$row['id'], 'value' => $row['pagetitle'])), FALSE);
	$i++;
}


$columns = array();
$formview = array();
foreach ($fields as $k => $field) {
	$searchformview = array();
    switch (true) {
        case in_array($field, $fields_for_popup_editor):
            $editor = 'popup';
            break;
        case ($field == 'date' || preg_match('/^date_/', $field) || preg_match('/(.*)_date$/', $field)):
            $editor = 'date';
            break;
		case ($field == 'status'):
			break;
		case ($field == 'form_id'):
			break;
		case ($field == 'checkedCol'):
			$editor = 'checkbox';    
            break;	
        default:
            $editor = 'text';
            break;
    }
	
    $tmp = array('id' => $field, 'header' => array($fields_names[$k], array("content" => "serverFilter")), 'sort' => 'server', 'editor' => $editor, 'adjust' => true);
	if (in_array($field, $fields_for_selector_filter)) {
		if ($field == 'form_id') {
			$tmp['header'] = array($fields_names[$k], array("content" => "serverSelectFilter", "options" => $select_forms));
		} else if ($field == 'status') {
			$tmp['header'] = array($fields_names[$k], array("content" => "serverSelectFilter", "options" => $select));
		}
		else {
			$tmp['header'] = array($fields_names[$k], array("content" => "serverSelectFilter"));
		}
	}
	if($field == 'checkedCol'){
		$tmp['header'] = array($fields_names[$k], array("content" => "masterCheckbox"));
		$tmp['sort'] = false;
        $tmp['checkValue'] = 1;
        $tmp['uncheckValue'] = 0;
        $tmp['template'] = "{common.checkbox()}";
    }
    if (in_array($field, $fields_readonly)) {
        unset($tmp['editor']);
		$formview['readonly'] = true;
    }
    $columns[] = $tmp;
}
foreach ($fields_modalform as $k => $field) {
    switch (true) {
        case in_array($field, $fields_for_popup_editor):
			$formview = array('view' => 'textarea', 'label' => $fields_modalform_names[$k], 'name' => $field, 'height' => 100);
            break;
        case ($field == 'date' || preg_match('/^date_/', $field) || preg_match('/(.*)_date$/', $field)):
			$formview = array('view' => 'datepicker', 'label' => $fields_modalform_names[$k], 'name' => $field, 'timepicker' => true);
            break;
		case ($field == 'status'):
			$formview = array('view' => 'select', 'options' => $select, 'label' => $fields_modalform_names[$k], 'name' => $field);
			break;
		case ($field == 'form_id'):
			$formview = array('view' => 'select', 'options' => $select_forms, 'label' => $fields_modalform_names[$k], 'name' => $field);
			break;
        default:
			$formview = array('view' => 'text', 'label' => $fields_modalform_names[$k], 'name' => $field);
            break;
    }
    if (in_array($field, $fields_readonly)) {
		$formview['readonly'] = true;
    }
	$form_fields[] = $formview;
}
$search_form_fields = array();
$search_fields = array(/*'status' => 'select', */'create_date' => 'period');
foreach ($search_fields as $key => $type) {
	$k = array_search($key, $fields);
	switch($type) {
		case 'select':
			$search_form_fields[] = array('view' => 'select', 'options' => $select, 'label' => $fields_names[$k], 'name' => $k);
			break;
		case 'period':
			$search_form_fields[] = array('view' => 'datepicker', 'label' => $fields_names[$k] . ' c ', 'name' => $key . '_from', 'labelWidth' => 110, 'stringResult' => true, 'format' => "%Y-%m-%d", 'width' => 300);
			$search_form_fields[] = array('view' => 'datepicker', 'label' => $fields_names[$k] . ' по ', 'name' => $key . '_to', 'labelWidth' => 110, 'stringResult' => true, 'format' => "%Y-%m-%d", 'width' => 300);
			break;
	}
}

$cols = json_encode($columns);
$formfields = json_encode($form_fields);
$search_formfields = json_encode($search_form_fields);
$module_id = (int)$_GET['id'];

$plh = array(
        'module_id' => $module_id,
        'module_url' => $module_url,
		'site_url' => MODX_SITE_URL,
        'idField' => $idField,
        'display' => $display,
        'cols' => $cols,
		'formfields' => substr($formfields, 1, -1),
		'search_formfields' => substr($search_formfields, 1, -1),
        'name' => $name,
		'inline_edit' => $inline_edit,
		'modal_edit_btn' => $modal_edit_btn
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
