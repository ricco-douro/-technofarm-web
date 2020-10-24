<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$fn_data = array_merge($view, ['type' => 'switch_events', '_version' => 1, 'events' => $view['sections']]);
	$this->Parser->fn($fn_data);
	$result = $this->get($fn_data['name']);
	
	echo $this->Parser->section($view['name'].'/'.$result);