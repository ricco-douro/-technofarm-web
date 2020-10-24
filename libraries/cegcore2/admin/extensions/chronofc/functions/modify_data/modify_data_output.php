<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	
	if(!empty($function['data_provider'])){
		$data = $this->Parser->parse($function['data_provider'], true);
	}else{
		$data = [];
	}
	
	if(!empty($function['var_name'])){
		$var_name = $function['var_name'];
	}else{
		$var_name = $function['name'];
	}
	
	if(!empty($function['data_override'])){
		list($new_data) = $this->Parser->multiline($function['data_override']);
		
		if(is_array($new_data)){
			if(count($new_data) == 1 AND count($new_data[0]) == 1){
				$data = $new_data[0]['name'];
			}else{
				foreach($new_data as $new_data_line){
					if(isset($new_data_line['value'])){
						$new_data_value = $this->Parser->parse($new_data_line['value'], true);
						$data[$new_data_line['name']] = $new_data_value;
					}else{
						$data[] = $this->Parser->parse($new_data_line['name'], true);
					}
				}
			}
		}
	}
	
	if($function['var_type'] == 'data'){
		if(is_array($data)){
			foreach($data as $k => $v){
				if($var_name == '*'){
					$this->data($k, $v, true);
				}else{
					$this->data($var_name.'.'.$k, $v, true);
				}
			}
		}else{
			$this->data($var_name, $data, true);
		}
	}else if($function['var_type'] == 'session'){
		if(is_array($data)){
			foreach($data as $k => $v){
				if($var_name == '*'){
					\GApp::session()->set($k, $v);
				}else{
					\GApp::session()->set($var_name.'.'.$k, $v);
				}
			}
		}else{
			\GApp::session()->set($var_name, $data);
		}
	}else{
		if(is_array($data)){
			foreach($data as $k => $v){
				if($var_name == '*'){
					$this->set($k, $v);
				}else{
					$this->set($var_name.'.'.$k, $v);
				}
			}
		}else{
			$this->set($var_name, $data);
		}
	}