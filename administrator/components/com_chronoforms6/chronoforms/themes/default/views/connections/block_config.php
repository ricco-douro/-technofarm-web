<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php if(!empty($views)): ?>
	<?php foreach($views as $view_n => $view): ?>
		<?php $this->view('views.connections.views_config', ['section_name' => $name, 'name' => $view['name'], 'type' => $view['type'], 'count' => $view_n, 'view' => $view, 'views' => $views]); ?>
	<?php endforeach; ?>
<?php endif; ?>
<?php if(!empty($functions)): ?>
	<?php foreach($functions as $function_n => $function): ?>
		<?php $this->view('views.connections.functions_config', ['event_name' => $name, 'name' => $function['name'], 'type' => $function['type'], 'count' => $function_n, 'function' => $function, 'functions' => $functions]); ?>
	<?php endforeach; ?>
<?php endif; ?>