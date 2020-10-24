<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$classes = [];
	$classes[] = $view['class'];
	$classes[] = $view['color'];
	$classes[] = !empty($view['style']) ? $view['style'] : '';
	$classes[] = !empty($view['size']) ? $view['size'] : '';
	$classes[] = !empty($view['compact']) ? $view['compact'] : '';
	$classes[] = !empty($view['floating']) ? $view['floating'] : '';
	$classes[] = !empty($view['attached']) ? $view['attached'] : '';
	$classes[] = !empty($view['icon']) ? 'icon' : '';
	$class = implode(' ', array_filter($classes));
?>
<div class="<?php echo $class; ?>" id="<?php echo $view['id']; ?>">
	<?php if(!empty($view['icon'])): ?>
	<i class="<?php echo $view['icon']; ?> icon"></i>
	<?php endif; ?>
	<div class="content">
		<?php if(!empty($view['header'])): ?>
		<div class="header"><?php echo $view['header']; ?></div>
		<?php endif; ?>
		<?php echo $this->Parser->section($view['name'].'/body'); ?>
	</div>
</div>