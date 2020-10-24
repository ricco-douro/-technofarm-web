<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$data = $this->Parser->parse($function['data_provider'], true);
	
	$switch2_test_option = function($option, $data){
		$option = is_string($option) ? $option : json_encode($option);
		$data = is_string($data) ? $data : json_encode($data);
		
		if((string)$option == $data){
			return true;
		}else if((string)$option == '*'){
			return true;
		//}else if(strpos($option, '*') !== false){
		}else if(strpos($option, '/') === 0 AND strrpos($option, '/') === strlen($option) - 1){
			return preg_match($option, $data);
			/*
			$ops = explode('*', $option);
			if(count($ops) == 2){
				$result1 = $result2 = true;
				
				if(!empty($ops[0])){
					$result1 = false;
					if(strpos($data, $ops[0]) === 0){
						$result1 = true;
					}
				}
				
				if(!empty($ops[1])){
					$result2 = false;
					if(strrpos($data, $ops[1]) === (strlen($data) - strlen($ops[1]))){
						$result2 = true;
					}
				}
				
				return $result1 AND $result2;
			}
			*/
		}else{
			return false;
		}
	};
	
	$events = [];
	$fevents = [];
	$_result = null;
	
	
	if(!empty($function['_version'])){
		list($events) = $this->Parser->multiline($function['events'], true, false);
		foreach($events as $event){
			if(!empty($event['value'])){
				$fevents[$event['value']] = $event['name'];
			}else{
				$fevents[$event['name']] = false;
			}
		}
	}else{
		$events = explode(',', $function['events']);
		foreach($events as $event){
			$fevents[$event] = false;
		}
	}
	
	
	foreach($fevents as $target_value => $op){
		$test_result = $switch2_test_option($target_value, $data);
		
		if($test_result){
			if($op){
				$_result = $op;
				$this->Parser->fevents[$function['name']][$op] = true;
			}else{
				$_result = $target_value;
				$this->Parser->fevents[$function['name']][$target_value] = true;
			}
			break;
		}
		/*
		if($even == '*'){
			$this->Parser->fevents[$function['name']][$even] = true;
			break;
		}else{
			//eval('$result = (bool)($data '.$op.' $even);');
			$result = (bool)($data == $even);
			if($result){
				$this->Parser->fevents[$function['name']][$even] = true;
				break;
			}
		}
		*/
	}
	
	//$this->Parser->fevents[$function['name']][$data] = true;
	$this->set($function['name'], $_result);