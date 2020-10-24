<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$action = $function['action'];
	
	if(empty($function['db_table'])){
		$this->Parser->debug[$function['name']]['_error'] = rl('Aborting, no database table selected.');
		$this->set($function['name'], false);
		return;
	}
	
	if(!empty($function['data_provider'])){
		$data = (array)$this->Parser->parse($function['data_provider'], true);
	}else{
		$data = [];
	}
	
	$db_options = \G2\Globals::get('custom_db_options', []);
	if(!empty($function['db']['enabled'])){
		$db_options = $function['db'];
	}
	$dbo = \G2\L\Database::getInstance($db_options);
	
	$Model = new \G2\L\Model(['dbo' => $dbo, 'name' => $function['model_name'], 'table' => $function['db_table']]);
	$start_dbo_log = $Model->dbo->log;
	
	if($action == 'save' AND !empty($Model->pkey) AND !empty($data[$Model->pkey])){
		$action = 'update';
		$Model->where($Model->pkey, $data[$Model->pkey]);
	}
	
	$condition_found = false;
	
	if(!empty($function['where'])){
		
		list($where_data, $where) = $this->Parser->multiline($function['where']);
		
		if(is_array($where_data)){
			foreach($where_data as $where_line){
				$filter_value = $this->Parser->parse($where_line['value'], true);
				
				$sign = is_array($filter_value) ? 'in' : '=';
				if(!empty($where_line['namep'])){
					$sign = $where_line['namep'];
				}
				
				if(!empty($where_line['valuep'])){
					$check_sign = $where_line['valuep'];
					
					if($check_sign == '+' AND empty($filter_value)){
						$this->set($function['name'], false);
						$this->Parser->fevents[$function['name']]['fail'] = true;
						$this->Parser->debug[$function['name']]['_error'] = rl('Data save aborted because %s value is missing', [$where_line['name']]);
						return false;
					}
					
					if($check_sign == '-' AND empty($filter_value)){
						continue;
					}
				}
				
				$condition_found = true;
				
				//if((!is_array($filter_value) AND strlen($filter_value)) > 0 OR (is_array($filter_value) AND count($filter_value))){
					$Model->where($where_line['name'], $filter_value, !empty($where_line['namep']) ? $where_line['namep'] : $sign);
				//}
			}
		}
		
		if(is_array($where)){
			$Model->whereGroup($where);
			
			$condition_found = true;
		}
	}
	
	if(!empty($function['conditions'])){
		$condition_found = true;
		foreach($function['conditions'] as $condition){
			if($condition['_type'] == 'rule' AND !empty($condition['name'])){
				$filter_value = $this->Parser->parse($condition['value'], true);
				
				$sign = is_array($filter_value) ? 'in' : '=';
				if(!empty($condition['namep'])){
					$sign = $condition['namep'];
				}
				
				if(!empty($condition['valuep'])){
					$check_sign = $condition['valuep'];
					
					if($check_sign == '+' AND empty($filter_value)){
						$this->set($function['name'], false);
						$this->Parser->fevents[$function['name']]['fail'] = true;
						$this->Parser->debug[$function['name']]['_error'] = rl('Aborted because %s value is missing', [$condition['name']]);
						return false;
					}
					
					if($check_sign == '-' AND empty($filter_value)){
						continue;
					}
				}
				
				$Model->where($condition['name'], $filter_value, $sign);
				
			}else if($condition['_type'] == 'param'){
				$Model->where($condition['name']);
			}
		}
	}
	
	if($action == 'save' AND $condition_found){
		$action = 'update';
	}
	
	$save_settings = [];
	if(!empty($function['fields']['special'])){
		
		list($special_data, $special) = $this->Parser->multiline($function['fields']['special']);
		
		if(is_array($special_data)){
			foreach($special_data as $special_line){
				if(!empty($special_line['name']) AND !empty($special_line['namep'])){
					if($special_line['namep'] == 'json'){
						$save_settings['json'][] = $special_line['name'];
					}else{
						if(!empty($special_line['value'])){
							$save_settings[$special_line['namep']][$special_line['name']] = $special_line['value'];
						}
					}
				}
			}
		}
	}
	
	if(!empty($function['specials'])){
		foreach($function['specials'] as $special){
			if(!empty($special['name']) AND !empty($special['action'])){
				if($special['action'] == 'json'){
					$save_settings['json'][] = $special['name'];
				}else{
					if(!empty($special['value'])){
						$save_settings[$special['action']][$special['name']] = $special['value'];
					}
				}
			}
		}
	}
	
	if($action == 'save'){
		$action = 'insert';
	}
	
	if(strpos($action, 'insert:') !== false){
		if($action == 'insert:update'){
			$save_settings['duplicate_update'] = true;
		}
		if($action == 'insert:ignore'){
			$save_settings['ignore'] = true;
		}
		$action = 'insert';
	}
	/*
	if(!empty($function['autofields'])){
		$connection = $this->Parser->_connection();
		
		$stored = \GApp::session()->get($connection['alias'].'.save', []);
		
		if(!empty($stored)){
			foreach($stored as $view_name => $fields){
				$fname_tag = '';
				foreach($fields as $k => $field){
					if(isset($field['name'])){
						//$fname = rtrim(str_replace(['[]', '[', ']', '(N)'], ['(N)', '.', '', '.[n]'], $field['name']), '.');
						$fname = $this->Parser->dpath($field['name']);
						
						if(is_array($this->data($fname))){
							$fname_tag .= '{data.jsonen:'.$fname.'/""}';
						}else{
							$fname_tag .= '{data:'.$fname.'/""}';
						}
						
						$lname = explode('.', $fname);
						$function['insert_data_override'] = $function['insert_data_override']."\n".array_pop($lname).':'.$fname_tag;
					}
				}
			}
		}
		
		//\GApp::session()->clear($connection['alias'].'.save');
	}
	*/
	
	if(!empty($function['overrides'])){
		$overrides = [];
		
		foreach($function['overrides'] as $override){
			if($override['action'] == 'insert'){
				$overrides['insert'][$override['name']]= $this->Parser->parse($override['value'], true);
			}else if($override['action'] == 'update'){
				$overrides['update'][$override['name']]= $this->Parser->parse($override['value'], true);
			}else{
				$overrides['insert'][$override['name']]= $this->Parser->parse($override['value'], true);
				$overrides['update'][$override['name']]= $this->Parser->parse($override['value'], true);
			}
		}
		
		#$data = array_merge($data, $overrides[$action]);
	}
	
	if($action == 'insert'){
		if(!empty($function['insert_data_override'])){
			list($new_data) = $this->Parser->multiline($function['insert_data_override']);
			
			if(is_array($new_data)){
				foreach($new_data as $new_data_line){
					
					if(!empty($new_data_line['valuep'])){
						$check_sign = $new_data_line['valuep'];
						$field_value = $this->Parser->parse($new_data_line['value'], true);
						
						if($check_sign == '+' AND empty($field_value)){
							$this->set($function['name'], false);
							$this->Parser->fevents[$function['name']]['fail'] = true;
							$this->Parser->debug[$function['name']]['_error'] = rl('Data save aborted because %s value is missing', [$new_data_line['name']]);
							return false;
						}
						
						if($check_sign == '-' AND empty($field_value)){
							if(isset($userData[$new_data_line['name']])){
								unset($userData[$new_data_line['name']]);
							}
							
							$this->Parser->debug[$function['name']]['info'] = rl('The field %s value has been skipped', [$new_data_line['name']]);
							continue;
						}
					}
					
					$new_data_value = $this->Parser->parse($new_data_line['value'], true);
					$data[$new_data_line['name']] = $new_data_value;
				}
			}
		}
	}
	
	if($action == 'update'){
		if(!empty($function['update_data_override'])){
			list($new_data) = $this->Parser->multiline($function['update_data_override']);
			
			if(is_array($new_data)){
				foreach($new_data as $new_data_line){
					
					if(!empty($new_data_line['valuep'])){
						$check_sign = $new_data_line['valuep'];
						$field_value = $this->Parser->parse($new_data_line['value'], true);
						
						if($check_sign == '+' AND empty($field_value)){
							$this->set($function['name'], false);
							$this->Parser->fevents[$function['name']]['fail'] = true;
							$this->Parser->debug[$function['name']]['_error'] = rl('Data save aborted because %s value is missing', [$new_data_line['name']]);
							return false;
						}
						
						if($check_sign == '-' AND empty($field_value)){
							if(isset($userData[$new_data_line['name']])){
								unset($userData[$new_data_line['name']]);
							}
							
							$this->Parser->debug[$function['name']]['info'] = rl('The field %s value has been skipped', [$new_data_line['name']]);
							continue;
						}
					}
					
					$new_data_value = $this->Parser->parse($new_data_line['value'], true);
					$data[$new_data_line['name']] = $new_data_value;
				}
			}
		}
	}
	
	if(!empty($function['autofields'])){
		$connection = $this->Parser->_connection();
		
		$stored = \GApp::session()->get($connection['alias'].'.save', []);
		
		if(!empty($stored)){
			foreach($stored as $view_name => $fields){
				$fname_tag = [];
				foreach($fields as $k => $field){
					if(isset($field['name'])){
						//$fname = rtrim(str_replace(['[]', '[', ']', '(N)'], ['(N)', '.', '', '.[n]'], $field['name']), '.');
						$fname = $this->Parser->dpath($field['name']);
						
						if(is_array($this->data($fname))){
							$fname_tag[] = json_encode($this->data($fname), JSON_UNESCAPED_UNICODE);
						}else{
							$fname_tag[] = $this->data($fname);
						}
						
						$lname = $this->Parser->lname($fname);
						//$function['insert_data_override'] = $function['insert_data_override']."\n".array_pop($lname).':'.$fname_tag;
					}
				}
				
				if(count($fields) > 1){
					$data[$lname] = json_encode($fname_tag, JSON_UNESCAPED_UNICODE);
				}else{
					$data[$lname] = $fname_tag[0];
				}
			}
		}
		
		//\GApp::session()->clear($connection['alias'].'.save');
	}
	
	$this->Parser->debug[$function['name']]['data'] = $data;
	//fix any arrays in the data array before saving
	/*array_walk($data, function(&$item, $key){
		if(is_array($item)){
			$item = json_encode($item);
		}
	});*/
	
	$result = $Model->$action($data, $save_settings);
	
	if($result !== false){
		
		if(!empty($Model->pkey)){
			$data[$Model->pkey] = $Model->id;
		}
		
		$this->set($function['name'], $Model->data);
		$this->Parser->fevents[$function['name']]['success'] = true;
		$this->Parser->debug[$function['name']]['_success'] = rl('Data saved successfully');
		
	}else{
		$this->Parser->debug[$function['name']]['_error'] = rl('Error saving the data.');
		$this->set($function['name'], false);
		$this->Parser->fevents[$function['name']]['fail'] = true;
	}
	$this->Parser->debug[$function['name']]['log'] = array_values(array_diff($Model->dbo->log, $start_dbo_log));