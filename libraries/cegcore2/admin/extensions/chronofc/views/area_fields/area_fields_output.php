<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(empty($view['number'])){
		/*
		$count = count($this->Parser->children($view['name'].'/body', 'views'));
		if($count > 0){
			if($count == 1){
				$view['number'] = 'one';
			}else if($count == 2){
				$view['number'] = 'two';
			}else if($count == 3){
				$view['number'] = 'three';
			}else if($count == 4){
				$view['number'] = 'four';
			}else if($count == 5){
				$view['number'] = 'five';
			}else if($count == 6){
				$view['number'] = 'six';
			}else if($count == 7){
				$view['number'] = 'seven';
			}
		}
		*/
		$view['number'] = 'equal width';
	}
	echo '<div class="'.(!empty($view['inline']) ? 'inline ' : '').''.$view['number'].' fields" id="'.$view['id'].'">';
	echo $this->Parser->section($view['name'].'/body');
	echo '</div>';