<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$classes = [];
	$classes[] = $view['class'];
	$classes[] = !empty($view['color']) ? $view['color'] : '';
	$classes[] = !empty($view['style']) ? $view['style'] : '';
	$classes[] = !empty($view['inverted']) ? $view['inverted'] : '';
	$classes[] = !empty($view['compact']) ? $view['compact'] : '';
	$classes[] = !empty($view['circular']) ? $view['circular'] : '';
	$classes[] = !empty($view['basic']) ? $view['basic'] : '';
	$classes[] = !empty($view['padded']) ? $view['padded'] : '';
	$classes[] = !empty($view['attached']) ? $view['attached'] : '';
	$classes[] = !empty($view['alignment']) ? $view['alignment'] : '';
	$classes[] = !empty($view['floating']) ? $view['floating'] : '';
	$classes[] = !empty($view['emphasis']) ? $view['emphasis'] : '';
	
	$class = implode(' ', array_filter($classes));
?>
<div class="<?php echo $class; ?>" id="<?php echo $view['id']; ?>">
	<?php echo $this->Parser->section($view['name'].'/body'); ?>
</div>