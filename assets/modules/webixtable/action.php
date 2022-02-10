<?php
/*
if(IN_MANAGER_MODE!='true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
*/

define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

include_once(__DIR__ . "/../../../index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}

if(!isset($_SESSION['mgrValidated']) || !$modx->hasPermission('exec_module')){
    die();
}

//парсим свойства модуля на предмет нужных настроек
if (isset($_REQUEST['module_id']) ) {
    if(is_numeric($_REQUEST['module_id'])){
        $prop = $modx->db->getValue($modx->db->query("SELECT properties FROM " . $modx->getFullTableName("site_modules") . " WHERE id=" . (int)$_REQUEST['module_id'] . " LIMIT 0,1"));
        $properties = $modx->parseProperties($prop);
    }
    else{
        $properties = evo()->modulesFromFile[$_REQUEST['module_id']]['properties'] ?? [];

    }

    if ($properties) {

        if (is_array($properties)) {
            extract($properties, EXTR_SKIP);
        }
    }
    else if($modx->modulesFromFile[$_REQUEST['module_id']]){
        $properties = $modx->modulesFromFile[$_REQUEST['module_id']]['properties'];
        extract($properties);
    }
}


$idField = isset($idField) ? trim($idField) : false;

$fields = isset($fields) ? explode(',', str_replace(', ', ',', trim($fields))) : false;
$fields_names = isset($fields_names) ? explode(',', str_replace(', ', ',', trim($fields_names))) : false;
$table = isset($table) ? trim($table) : false;
$display = isset($display) && (int)$display > 0 ? (int)$display : 10;

//начинаем...
$out = '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
switch($action) {
    case 'update':
        $arr = array();
        foreach ($fields as $field) {
            if (isset($_REQUEST[$field])) {
                $arr[$field] = $modx->db->escape($_REQUEST[$field]);
            }
        }
        $opetarion = isset($_REQUEST['webix_operation']) ? $_REQUEST['webix_operation'] : '';
        switch ($opetarion) {
            case 'update':
                if (!empty($arr) && isset($arr[$idField]) && $arr[$idField] != '') {				
					$modx->db->update($arr, $modx->getFullTableName($table), "`" . $idField . "`='" . $arr[$idField] . "'");
                }
                break;
            case 'insert':
                if (!empty($arr) && isset($arr[$idField]) && $arr[$idField] != '') {
                    $modx->db->insert($arr, $modx->getFullTableName($table));
                } else if ($idField == 'id') {
                    $max = $modx->db->getValue($modx->db->query("SELECT MAX(`" . $idField . "`) FROM " . $modx->getFullTableName($table)));
                    $max = $max ? ($max + 1) : 1;
                    $modx->db->insert(array('id' => $max), $modx->getFullTableName($table));
                }
                break;
            case 'delete':
                if (!empty($arr) && isset($arr[$idField]) && $arr[$idField] != '') {
                    $modx->db->delete($modx->getFullTableName($table), "`" . $idField . "`='" . $arr[$idField] . "'");
                }
                break;
        }
        break;
        
    case 'list':


        $DLparams = array(
            'controller' => 'onetable',
            'table' => $table,
            'api' => implode(',', $fields),
            'JSONformat' => 'new',
            'idType' => 'documents',
            'idField' => $idField,
            'ignoreEmpty' => '1',
            'display' => $display,
            'prepare' => function($data, $modx, $_DL, $_extDocLister) {
                foreach ($data as $k => $v) {

                    if (preg_match('/^href_/', $k)) {
                        $v = MODX_SITE_URL . ltrim($v, '/');
                        $data[$k] = '<a href="' . $v . '" target="_blank"><i class="fas fa-download" aria-hidden="true"></i></a>';
                    }
                }
                return $data;
            }
        );
        //имеем запрос с сервера
        if (isset($_REQUEST['continue']) && $_REQUEST['continue'] == 'true') {
            if (isset($_REQUEST['sort'])) {
                $sortBy = implode('', array_keys($_REQUEST['sort']));
                $sortDir = strtoupper(implode('', array_values($_REQUEST['sort'])));
                $orderBy = $sortBy . ' ' . $sortDir;
                $DLparams['orderBy'] = $orderBy;
            }
            if (isset($_REQUEST['start'])) {
                $DLparams['offset'] = (int)$_REQUEST['start'];
            }
            if (isset($_REQUEST['filter'])) {
                $tmp = array();
                foreach ($fields as $field) {
                    if (isset($_REQUEST['filter'][$field]) && !empty($_REQUEST['filter'][$field]) && $_REQUEST['filter'][$field] != "") {
                        $tmp[] = "`" . $field . "` LIKE '%" . $modx->db->escape($_REQUEST['filter'][$field]) . "%'";
                    }
                }
                if (!empty($tmp)) {
                    $DLparams['addWhereList'] = implode(" AND ", $tmp);
                }
            }
        }	
		
		switch($_REQUEST['tab_action']){
			case 'commission':
				$DLparams['addWhereList'] = 'form_id = '.$_REQUEST['document_id'];
				break;
			case 'service':					
				if(empty($_REQUEST['service_id'])){
					$DLparams['addWhereList'] = 'type = '.$modx->runSnippet('DocInfo', array('docid' => $_REQUEST['document_id'], 'field' => 'type_service'));; 
				}else{
					$DLparams['addWhereList'] = 'type = '.$_REQUEST['service_id']; 
				}
				//$_REQUEST['service_id'];
				break;	
			case 'fines':
				//$region_id = ($_REQUEST['region_id'] != '') ? 'AND region_id = '.$_REQUEST['region_id'] : '';
				if($_REQUEST['module_id'] != 12){
					$region_id = $modx->runSnippet('DocInfo', array('docid' => $_REQUEST['document_id'], 'field' => 'id_region'));
				}				
				if(!empty($region_id)){
					$DLparams['addWhereList'] = 'region_id = '.$region_id;
				}				 
				//$_REQUEST['region_id'];
				break;
		}

		
        $tmp = $modx->runSnippet("DocLister", $DLparams);
        
        $tmp2 = json_decode($tmp, TRUE);
        $rows = $tmp2['rows'];
        $total_count = $tmp2['total'];
        $itogo = array("data" => $rows, "pos" => (int)$_REQUEST['start'], "total_count" => $total_count);
        $out .= json_encode($itogo);
        break;
        
    case 'get_next':
        $max = $modx->db->getValue($modx->db->query("SELECT MAX(`" . $idField . "`) FROM " . $modx->getFullTableName($table)));

        $out .= $max ? ($max + 1) : 1;
        break;

    default:
        break;
}

echo $out;
