<?php
/*
if(IN_MANAGER_MODE!='true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
*/

define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

include(__DIR__ . "/../../../index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}

if(!isset($_SESSION['mgrValidated']) || !$modx->hasPermission('exec_module')){
    die();
}

//парсим свойства модуля на предмет нужных настроек
if (isset($_REQUEST['module_id']) && (int)$_REQUEST['module_id'] > 0) {
    $prop = $modx->db->makeArray($modx->db->query("SELECT properties FROM " . $modx->getFullTableName("site_modules") . " WHERE id=" . (int)$_REQUEST['module_id'] . " LIMIT 0,1"))[0];
    if ($prop) {
        $properties = $modx->parseProperties($prop['properties']);
        if (is_array($properties)) {
            extract($properties, EXTR_SKIP);
        }
    }
}



$idField = isset($idField) ? trim($idField) : false;
$fields = isset($fields) ? explode(',', str_replace(', ', ',', trim($fields))) : false;
$fields_names = isset($fields_names) ? explode(',', str_replace(', ', ',', trim($fields_names))) : false;
$fields_modalform = isset($fields_modalform) ? explode(',', str_replace(', ', ',', trim($fields_modalform))) : $fields;
$fields_modalform_names = isset($fields_modalform_names) ? explode(',', str_replace(', ', ',', trim($fields_modalform_names))) : $fields_names;
$table = isset($table) ? trim($table) : false;
$display = isset($display) && (int)$display > 0 ? (int)$display : 10;
/*$status_array = array('wait' => 'Ожидает оплаты', 'sandbox' => 'Тестовый платеж', 'success' => 'Отправлено', 'error' => 'Забраковано (error)', 'failure' => 'Забраковано (failure)', 'reversed' => 'Платеж возвращен', 'submitted' => 'Подверждено', 'question' => 'Нестандартная ситуация', 'ready' => 'Проведено');*/
//'sandbox' => 'Тестовий платіж',
$status_array = [
    1 => 'Ожидание оплаты',
    2 => 'Оплачено',
    3 => 'Ошибка',
];

//названия форм оплаты
$forms = array();
//$q = $modx->db->query("SELECT id,pagetitle FROM " . $modx->getFullTableName("site_content") . " WHERE template=14");
$q = $modx->db->query("SELECT id,pagetitle FROM " . $modx->getFullTableName("site_content") . " WHERE template = 14 OR template = 13");
while ($row = $modx->db->getRow($q)) {
	$shortName = $modx->runSnippet('DocInfo', array('docid' => $row['id'], 'field' => 'form_name'));
    $forms[$row['id']] = (!empty($shortName)) ? $shortName : $row['pagetitle'];
}
//начинаем...
$out = '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
//$modx->logEvent(1,1,json_encode($_REQUEST), 'webix action');
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
					foreach ($arr as $k => $v) {
						if (preg_match('/^href_/', $k)) {//удаляем преобразованные в ссылки адреса
							unset($arr[$k]);
						}
					}
                    $modx->db->update($arr, $modx->getFullTableName($table), "`" . $idField . "`='" . $arr[$idField] . "'");
                }
                break;
            case 'insert':
                if (!empty($arr) && isset($arr[$idField]) && $arr[$idField] != '') {
                    $modx->db->insert($arr, $modx->getFullTableName($table));
                } else if ($idField == 'id') {
                    $row = $modx->db->getRow(
                        $modx->db->query(
                            "SELECT MAX(`" . $idField . "`) FROM " . $modx->getFullTableName($table)));
                    $max = $row[array_key_first($row)];
//                    $max = $modx->db->getValue("SELECT MAX(`" . $idField . "`) FROM " . $modx->getFullTableName($table));
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
		if (!empty($_REQUEST['create_date_from']) && !empty($_REQUEST['create_date_to'])) {
            $display = 'all';
        }

        $DLparams = array(
            'controller' => 'onetable',
            'table' => $table,
            'api' => empty($fields)?0:implode(',', $fields),
            'JSONformat' => 'new',
            'idType' => 'documents',
            'idField' => $idField,
            'ignoreEmpty' => '1',
            'display' => $display,
			'status_array' => $status_array,
			'forms' => $forms,
            'prepare' => function($data, $modx, $_DL, $_extDocLister) {
                foreach ($data as $k => $v) {
                    if (preg_match('/^href_/', $k)) {
						if ($v && $v != '') {
							$v = MODX_SITE_URL . ltrim($v, '/');
							$data[$k] = '<a href="' . $v . '" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>';
						}
                    }
					$status_array = $_DL->getCFGDef('status_array');
					if ($k == 'status' && isset($status_array[$v])) {
						$data[$k] = $status_array[$v];
					}
					$forms = $_DL->getCFGDef('forms');
					if ($k == 'form_id' && isset($forms[$v])) {
						$data[$k] = $forms[$v];
					}
                }
				if(!empty($data['create_date'])){
					if($data['create_date'] == '0000-00-00 00:00:00'){
						$data['create_date'] = '00-00-0000 00:00:00';
					}else{
						$data['create_date'] = date('d-m-Y H:i:s', strtotime($data['create_date']));
					}
					
				}
				
				if($data['phone'] == ''){					
					$tmp = json_decode($data['liqpay_responce'], TRUE);
					if(!empty($tmp['sender_phone'])){
						$data['phone'] = $tmp['sender_phone'];
					}
				}else{
					$data['phone'] = str_replace(array('+','(',')',' ','-'),array('','','','',''),$data['phone']);
				}
				
				if (!empty($data['user_geo_data'])) {
					$user_geo = json_decode($data['user_geo_data'], true);
					if (!empty($user_geo['city'])) {
						$city = ' ('.$user_geo['city'].')';
					}
					$data['user_geo_data'] = $user_geo['ip'].$city;
				}
				
				/*if(!empty($data['poluch_name'])){
					$data['pay_info'] = $data['poluch_name'];	
				}*/
				
				$data['checkedCol'] = 0;
                return $data;
            }
        );
		$addwehere = array();
        //имеем запрос с сервера
        if ((isset($_REQUEST['continue']) && $_REQUEST['continue'] == 'true') || (isset($_REQUEST['filter']) && !empty($_REQUEST['filter']))) {
            if (isset($_REQUEST['sort'])) {
                $sortBy = implode('', array_keys($_REQUEST['sort']));
                $sortDir = strtoupper(implode('', array_values($_REQUEST['sort'])));
                $orderBy = $sortBy . ' ' . $sortDir;
                $DLparams['orderBy'] = $orderBy;
            }
            if (isset($_REQUEST['start'])) {
                $DLparams['offset'] = (int)$_REQUEST['start'];
            }
            if (isset($_REQUEST['filter']) && !empty($_REQUEST['filter'])) {
                foreach ($fields as $field) {
                    if (isset($_REQUEST['filter'][$field]) && !empty($_REQUEST['filter'][$field]) && $_REQUEST['filter'][$field] != "") {
						$val = $modx->db->escape($_REQUEST['filter'][$field]);
						/*if ($field == 'status' && in_array($val, $status_array)) {
							$val = array_search($val, $status_array);
						}*/
						/*if ($field == 'form_id' && in_array($val, $forms)) {
							$val = array_search($val, $forms);
						}*/
						
						if (in_array($field, array('status', 'form_id'))) {	
							/*if(!empty($val)){								
								$findOldFormId = $modx->runSnippet('DocInfo', array('docid' => $val, 'field' => 'form_id'));
								if(!empty($findOldFormId)){									
									$addwehere[] = "`" . $field . "`IN ('" . $val . "','".$findOldFormId."')";
								}else{
									$addwehere[] = "`" . $field . "`='" . $val . "'";
								}
							}else{
								$addwehere[] = "`" . $field . "`='" . $val . "'";
							}*/
							$addwehere[] = "`" . $field . "`='" . $val . "'";
							
						}  else if ($field == 'create_date' && !empty($field)){
							$create_date = date('Y-m-d', strtotime($val)) . " 00:00:00";
							$addwehere[] = "`create_date` >= '" . $create_date . "'";	
						}else {
							$addwehere[] = "`" . $field . "` LIKE '%" . $val . "%'";
						}
                    }
                }
            }
        }
			if (isset($_REQUEST['create_date_from']) && $_REQUEST['create_date_from'] != '') {
				$create_date_from = date("Y-m-d", strtotime($_REQUEST['create_date_from'])) . " 00:00:00";
				$addwehere[] = "`create_date`>='" . $create_date_from . "' ";
			}
			if (isset($_REQUEST['create_date_to']) && $_REQUEST['create_date_to'] != '') {
				$create_date_to = date("Y-m-d", strtotime($_REQUEST['create_date_to'])) . " 23:59:59";
				$addwehere[] = "`create_date` <= '" . $create_date_to . "' ";
			}
            if (!empty($addwehere)) {
                $DLparams['addWhereList'] = implode(" AND ", $addwehere);
				//$modx->logEvent(1,1,$DLparams['addWhereList'], 'addWhereList');
            }
			
        $tmp = $modx->runSnippet("DocLister", $DLparams);
        
        $tmp2 = json_decode($tmp, TRUE);
        $rows = $tmp2['rows'];
        $total_count = $tmp2['total'];
        $itogo = array("data" => $rows, "pos" => (int)$_REQUEST['start'], "total_count" => $total_count);
        $out .= json_encode($itogo);
        break;
        
    case 'get_next':
        $row = $modx->db->getRow(
            $modx->db->query(
                "SELECT MAX(`" . $idField . "`) FROM " . $modx->getFullTableName($table)));
        $max = $row[array_key_first($row)];
        $out .= $max ? ($max + 1) : 1;
        break;
		
	case 'get_row':
		if (isset($_REQUEST['key']) && $_REQUEST['key'] != '') {
			$key = $modx->db->escape($_REQUEST['key']);
			$q = $modx->db->query("SELECT * FROM " . $modx->getFullTableName($table) . " WHERE `" . $idField . "`='" . $key . "' LIMIT 0,1");
			if ($modx->db->getRecordCount($q) == 1) {
				$row = $modx->db->getRow($q);
				foreach ($row as $k => $v) {
					if (!in_array($k, $fields_modalform)) {
						unset($row[$k]);
					}
				}
				//old forms
				//ищем старые записи и присваиваем им новые ид формы при пересохранении
				$findOldFormId = $modx->db->getValue(
					$modx->db->query("
						SELECT tv.contentid FROM " . $modx->getFullTableName('site_content') . " as c
						LEFT JOIN ".$modx->getFullTableName('site_tmplvar_contentvalues')." as tv
						ON c.id = tv.contentid
						WHERE
						c.parent = 1						
						AND tv.tmplvarid = 16 
						AND tv.value ='" . $row['form_id'] ."' 
						LIMIT 1
						"
					)); 
				if(!empty($findOldFormId)){
					$row['form_id'] = $findOldFormId;
				}				
				unset($row['checkedCol']);
				//unset($row['pay_info']);
				$out .= json_encode($row);
			}
		}
		break;
	case 'update_row':
        $arr = array();
		$resp = 'error';
        foreach ($fields_modalform as $field) {
            if (isset($_REQUEST[$field])) {
                $arr[$field] = $modx->db->escape($_REQUEST[$field]);
            }
        }
		unset($arr['checkedCol']);
		//unset($arr['pay_info']);		
		if (!empty($arr) && isset($arr[$idField]) && $arr[$idField] != '') {
            $up = $modx->db->update($arr, $modx->getFullTableName($table), "`" . $idField . "`='" . $arr[$idField] . "'");
			if ($up) {
				$resp = 'ok';
			}
        }
		$out = $resp;
		break;
	case 'make_pdf':
		if (isset($_REQUEST[$idField]) && (int)$_REQUEST[$idField] > 0) {
			$rowId = (int)$_REQUEST[$idField];
			$href_order_pdf = $modx->runSnippet("makeClientDocs", array('type' => 'pdf', 'rowId' => $rowId));
			$href_order_pdf = $modx->db->escape($href_order_pdf);
			$modx->db->update(array('href_order_pdf' => $href_order_pdf), $modx->getFullTableName("payment_info"), "id=" . $rowId);
			$out = $href_order_pdf;
		}
		break;
	case 'show_liqpay_responce':
		$txt = '';
		if (isset($_REQUEST[$idField]) && (int)$_REQUEST[$idField] > 0) {
			$rowId = (int)$_REQUEST[$idField];
			$txt = '';
			$resp = $modx->db->getValue("SELECT liqpay_responce FROM " . $modx->getFullTableName($table) . " WHERE `" . $idField . "`='" . $rowId . "' LIMIT 0,1");
			if ($resp) {
				$tmp = json_decode($resp, TRUE);
				if ($tmp['status'] == 'failure') {
					switch($tmp['err_code']) {
						case '9863':
							$reason = 'Відмова від банку емітента (клієнту необхідно звернеться в банк)';
							break;
						case '9859':
							$reason = 'Недостатньо коштів';
							break;
						case '90':
							$reason = 'Загальна помилка під час обробки';
							break;
						case '9860':
							$reson = 'Перевищено ліміт';
							break;
						case 'expired_senderapp':
							$reason = 'Cеанс платежу закінчився';
							break;
						case 'expired_password':
							$reason = 'Cеанс платежу закінчився';
							break;
						case 'expired_3ds':
							$reason = 'Cеанс платежу закінчився';
							break;
						case 'expired_p24':
							$reason = 'Cеанс платежу закінчився';
							break;
						case ' expired_cvv':
							$reason = 'Cеанс платежу закінчився';
							break;
						case 'decline':
							$reason = 'Відхилив';
							break;
						case 'err_payment':
							$reason = 'Не вдалося здійснити платіж. Переконайтеся, що параметри введені правильно та повторіть спробу';
							break;
						case '9868':
							$reason = 'Зовнішня помилка';
							break;
						case '9855':
							$reason = 'Невірна транзакція';
							break;
						default:
							$reason = $tmp['err_description'];
							break;
					}
					
					$txt .= 'Причина несплати платежу: <strong>'.$reason.'</strong><br /><br />';
				}
				foreach ($tmp as $k => $v) {
					$txt .= $k . ': ' . $v . '<br>';
				}
			} else {
				//если респонса нет, значит стучимся в колонку user_geo_data
				$respGeoData = $modx->db->getValue("SELECT user_geo_data FROM " . $modx->getFullTableName($table) . " WHERE `" . $idField . "`='" . $rowId . "' LIMIT 0,1");
				if ($respGeoData) {
					$tmp2 = json_decode($respGeoData, TRUE);
					foreach ($tmp2 as $k => $v) {
						$txt .= $k . ': ' . $v . '<br>';
					}
				}	
			}
		}
		$out = $txt;
		break;
    default:
        break;
}

echo $out;
