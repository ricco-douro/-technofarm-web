<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($function['content'])){
		$text = $this->Parser->parse($function['content'], true);
		$text = \JHtml::_('content.prepare', $text);
		echo $text;
	}
	
	$text = $this->Parser->event($function['name'].'/output', true);
	
	if(!empty($text)){
		$text = \JHtml::_('content.prepare', $text);
		echo $text;
	}