<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui segment tab functions-tab active" data-tab="function-<?php echo $n; ?>">
	
	<div class="ui top attached tabular menu small G2-tabs">
		<a class="item active" data-tab="function-<?php echo $n; ?>-general"><?php el('General'); ?></a>
		<a class="item" data-tab="function-<?php echo $n; ?>-permissions"><?php el('Permissions'); ?></a>
	</div>
	
	<div class="ui bottom attached tab segment active" data-tab="function-<?php echo $n; ?>-general">
		<input type="hidden" value="stored_block" name="Connection[functions][<?php echo $n; ?>][type]">
		
		<div class="two fields advanced_conf">
			<div class="field">
				<label><?php el('Name'); ?></label>
				<input type="text" value="" name="Connection[functions][<?php echo $n; ?>][name]">
			</div>
			<div class="field">
				<label><?php el('Category'); ?></label>
				<input type="text" value="" name="Connection[functions][<?php echo $n; ?>][category]">
			</div>
		</div>
		
		<div class="field forms_conf">
			<label><?php el('Designer label'); ?></label>
			<input type="text" value="" name="Connection[functions][<?php echo $n; ?>][label]">
		</div>
		
		<div class="field">
			<label><?php el('Block title'); ?></label>
			<select name="Connection[functions][<?php echo $n; ?>][id]" class="ui fluid dropdown">
				<option value="">===</option>
				<?php foreach($this->get('blocks') as $block): ?>
					<?php if($block['Block']['type'] == 'functions'): ?>
					<option value="<?php echo $block['Block']['id']; ?>"><?php echo $block['Block']['title']; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</div>
		
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="function-<?php echo $n; ?>-permissions">
		<div class="two fields">
			<div class="field">
				<label><?php el('Owner id value'); ?></label>
				<input type="text" value="" name="Connection[functions][<?php echo $n; ?>][owner_id]">
			</div>
		</div>
		
		<?php $this->view('views.permissions_manager', ['model' => 'Connection[functions]['.$n.']', 'perms' => ['access' => rl('Access')], 'groups' => $this->get('groups')]); ?>
	</div>
	
	<button type="button" class="ui button compact red tiny close_config forms_conf forms_conf"><?php el('Close'); ?></button>
</div>