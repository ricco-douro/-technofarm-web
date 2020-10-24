<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php $this->view('views.common.validationalert', ['ext' => 'chronoforms', 'name' => 'ChronoForms', 'msg' => 'Forms will display a credits link and a maximum of 15 fields per form on FRONTEND.']); ?>
<?php
	$this->view('views.common.admin_menu', ['etitle' => 'ChronoForms6', 'menuitems' => [
		['cont' => 'connections', 'title' => rl('Forms'), 'icon' => 'edit outline'],
		['cont' => 'blocks', 'title' => rl('Blocks'), 'icon' => 'boxes'],
		['act' => 'install_feature', 'title' => rl('Install feature'), 'hidden' => true],
		['act' => 'info', 'title' => rl('Shortcodes')],
	]]);
?>

<div class="ui segment fluid container">
	{VIEW}
</div>