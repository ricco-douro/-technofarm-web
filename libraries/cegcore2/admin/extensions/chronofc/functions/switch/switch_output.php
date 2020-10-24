<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$data = $this->Parser->parse($function['data_provider'], true);
	
	$result = false;
	
	if(!empty($function['values'])){
		
		$switch_test_option = function($option, $data){
			$option = is_string($option) ? $option : json_encode($option);
			$data = is_string($data) ? $data : json_encode($data);
			
			if($option == $data){
				return true;
			}else if($option == '*'){
				return true;
			}else if(strpos($option, '/') === 0 AND strrpos($option, '/') === strlen($option) - 1){
				return preg_match($option, $data);
			}
		};
		
		list($values_data) = $this->Parser->multiline($function['values'], true, false);
		
		foreach($values_data as $value_data){
			if(strlen($value_data['name']) AND strlen($value_data['value'])){
				
				$target_value = $this->Parser->value($value_data['name']);
				
				$test_result = null;
				
				if(!empty($function['array']) AND is_array($data)){
					//$test_result = in_array($target_value, $data);
					foreach($data as $value){
						$test_result = $switch_test_option($target_value, $value);
						
						if($test_result){
							break;
						}
					}
					
					if($test_result){
						$result[$value_data['value']] = $this->Parser->parse($value_data['value'], !empty($function['return']));
					}
				}else{
					//$test_result = (($data == $target_value) OR ($target_value === '*'));
					
					$test_result = $switch_test_option($target_value, $data);
					
					if($test_result){
						$result = $this->Parser->parse($value_data['value'], !empty($function['return']));
						break;
					}
				}
				
			}
		}
	}
	
	$this->set($function['name'], $result);
	$this->Parser->debug[$function['name']]['finished'] = true;