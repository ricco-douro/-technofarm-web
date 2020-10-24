<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php if(\GApp::extension($ext)->valid() === false): ?>
<div class="ui message red">
	<?php echo $name; ?> on <strong><?php echo \G2\L\Url::domain(false); ?></strong> is <div class="ui label red">Not validated</div>, <strong style="text-decoration:underline;"><?php echo $msg; ?></strong>.
	<a class="ui button green small compact right labeled icon" href="<?php echo r2('index.php?ext='.$ext.'&act=validateinstall'); ?>"><?php el('Validate Now'); ?><i class="icon right chevron"></i></a>
</div>
<?php endif; ?>