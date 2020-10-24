<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$classes = [];
	$classes[] = $view['class'];
	$classes[] = !empty($view['fluid']) ? $view['fluid'] : '';
	$classes[] = !empty($view['alignment']) ? $view['alignment'] : '';
	
	$class = implode(' ', array_filter($classes));
	
	$attrs = [];
	if(!empty($view['attrs'])){
		list($attrs) = $this->Parser->multiline($view['attrs'], 'assoc');
	}
	
	if(!empty($view['reload']['event'])){
		$attrs['data-reloadurl'] = r2($this->Parser->_url().rp('event', $view['reload']).rp('tvout', 'view'));
	}
	
	$Html = new \G2\H\Html();
	$attrs = $Html->_attrs($attrs);
?>
<div class="<?php echo $class; ?>" id="<?php echo $view['id']; ?>" <?php echo $attrs; ?>>
	<?php echo $this->Parser->section($view['name'].'/body'); ?>
</div>