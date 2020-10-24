<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui segment tab views-tab active" data-tab="view-<?php echo $n; ?>">
	
	<div class="ui top attached tabular menu small G2-tabs">
		<a class="item active" data-tab="view-<?php echo $n; ?>-general"><?php el('General'); ?></a>
		<a class="item" data-tab="view-<?php echo $n; ?>-advanced"><?php el('Advanced'); ?></a>
		<a class="item" data-tab="view-<?php echo $n; ?>-permissions"><?php el('Permissions'); ?></a>
	</div>
	
	<div class="ui bottom attached tab segment active" data-tab="view-<?php echo $n; ?>-general">
		<input type="hidden" value="area_container" name="Connection[views][<?php echo $n; ?>][type]">
		
		<div class="two fields advanced_conf">
			<div class="field">
				<label><?php el('Name'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][name]">
			</div>
			<div class="field">
				<label><?php el('Category'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][category]">
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Designer Label'); ?></label>
			<input type="text" value="" name="Connection[views][<?php echo $n; ?>][label]">
		</div>
		
		<div class="two fields">
			<div class="six wide field">
				<label><?php el('ID'); ?></label>
				<input type="text" value="area_container_<?php echo $n; ?>" name="Connection[views][<?php echo $n; ?>][id]">
			</div>
			
			<div class="ten wide field">
				<label><?php el('Class'); ?></label>
				<input type="text" value="ui container" name="Connection[views][<?php echo $n; ?>][class]">
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<div class="ui checkbox toggle">
					<input type="hidden" name="Connection[views][<?php echo $n; ?>][fluid]" data-ghost="1" value="">
					<input type="checkbox" checked class="hidden" name="Connection[views][<?php echo $n; ?>][fluid]" value="fluid">
					<label><?php el('Fluid'); ?></label>
					<small><?php el('Fluid container will take the full width'); ?></small>
				</div>
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Text alignment'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][alignment]" class="ui fluid dropdown">
					<option value=""><?php el('None'); ?></option>
					<option value="right aligned"><?php el('Right aligned'); ?></option>
					<option value="center aligned"><?php el('Center aligned'); ?></option>
					<option value="left aligned"><?php el('Left aligned'); ?></option>
				</select>
				<small><?php el('Content alignment inside the segment'); ?></small>
			</div>
		</div>
		
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="view-<?php echo $n; ?>-advanced">
		
		<div class="two fields">
			<div class="field">
				<label><?php el('Reload event'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][reload][event]">
				<small><?php el('The form event name used to reload this element when a reload event is triggered for it'); ?></small>
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Extra attributes'); ?></label>
			<textarea name="Connection[views][<?php echo $n; ?>][attrs]" rows="3"></textarea>
		</div>
		
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="view-<?php echo $n; ?>-permissions">
		<div class="two fields">
			<div class="field">
				<label><?php el('Owner id value'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][owner_id]">
				<small><?php el('The value of the owner id with which the owner permission will be checked.'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Toggle switch'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][toggler]">
				<small><?php el('If provided and is an empty value then the view will not be rendered.'); ?></small>
			</div>
		</div>
		
		<?php $this->view('views.permissions_manager', ['model' => 'Connection[views]['.$n.']', 'perms' => ['access' => rl('Access')], 'groups' => $this->get('groups')]); ?>
	</div>
	
	<button type="button" class="ui button compact red tiny close_config forms_conf"><?php el('Close'); ?></button>
</div>