<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$connection = $this->get('__connection');
	ob_start();
?>
<?php
	$settings = [];
	$settings['closable'] = (bool)$view['closable'];
	$settings['inverted'] = (bool)$view['inverted'];
	$settings['detachable'] = isset($view['detachable']) ? (bool)$view['detachable'] : false;
	//$settings['observeChanges'] = true;
	$modal_def = '$(".ui.modal.'.$view['name'].'.'.$connection['alias'].'").modal('.json_encode($settings).').modal("show");';
?>

	jQuery(document).ready(function($){
		<?php if(!empty($view['pageload'])): ?>
			<?php echo $modal_def; ?>
		<?php endif; ?>
		<?php if(!empty($view['delay'])): ?>
			setTimeout(function(){<?php echo $modal_def; ?>}, <?php echo $view['delay']; ?>);
		<?php endif; ?>
		<?php if(!empty($view['scroll'])): ?>
			$(window).scroll(function(){
				if(window.pageYOffset > <?php echo $view['scroll']; ?>){
					<?php echo $modal_def; ?>
				}
			});
		<?php endif; ?>
		<?php if(!empty($view['trigger'])): ?>
			$('<?php echo $view['trigger']; ?>').on('click', function(){
				<?php echo $modal_def; ?>
			});
		<?php endif; ?>
	});
<?php
	$jscode = ob_get_clean();
	\GApp::document()->addJsCode($jscode);
?>
<?php
	if(!empty($view['replacement'])){
		echo '<div class="ui form">'.$this->Parser->parse($view['replacement'], true).'</div>';
	}
	
	$scrolling = '';
	if(!empty($view['scrolling'])){
		$scrolling = 'scrolling';
	}
?>
<div class="ui <?php echo !empty($view['size']) ? $view['size'] : ''; ?> <?php echo !empty($view['basic']) ? 'basic' : ''; ?> modal <?php echo $view['name']; ?> <?php echo $connection['alias']; ?>">
	<?php if(!empty($view['close_icon'])): ?>
	<i class="close icon red"></i>
	<?php endif; ?>
	<?php if(!empty($view['header'])): ?>
	<div class="header"><?php echo $view['header']; ?></div>
	<?php endif; ?>
	<div class="<?php echo $scrolling?> content" style="width:auto;">
		<?php
			if(!empty($view['content'])){
				echo $view['content'];
			}else{
				echo $this->Parser->section($view['name'].'/body');
			}
		?>
	</div>
</div>