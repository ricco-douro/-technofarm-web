<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$_output = '';
	
	//check configured elements setting
	if(!empty($function['elements'])){
		$elements = array_filter(array_map('trim', explode("\n", $function['elements'])));
		
		$mode = ($function['mode'] == 'sections') ? 'section' : 'view';
		foreach($elements as $element){
			$_output .= $this->Parser->$mode(trim($element));
		}
	}
	
	
	echo $_output;