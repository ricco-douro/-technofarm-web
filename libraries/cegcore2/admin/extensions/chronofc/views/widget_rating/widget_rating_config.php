<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui segment tab views-tab active" data-tab="view-<?php echo $n; ?>">
	
	<div class="ui top attached tabular menu small G2-tabs">
		<a class="item active" data-tab="view-<?php echo $n; ?>-general"><?php el('General'); ?></a>
		<a class="item" data-tab="view-<?php echo $n; ?>-permissions"><?php el('Permissions'); ?></a>
	</div>
	
	<div class="ui bottom attached tab segment active" data-tab="view-<?php echo $n; ?>-general">
		<input type="hidden" value="widget_rating" name="Connection[views][<?php echo $n; ?>][type]">
		
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
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Label'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][label]">
				<small><?php el('A label for the widget, leave empty to exclude the label.'); ?></small>
			</div>
			
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Icon'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][icon]" class="ui fluid dropdown">
					<option value="star"><?php el('Star'); ?></option>
					<option value="heart"><?php el('Heart'); ?></option>
				</select>
				<small><?php el('The rating icon shape.'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Rating value'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][value]">
				<small><?php el('The active rating value.'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Max rating'); ?></label>
				<input type="text" value="5" name="Connection[views][<?php echo $n; ?>][max]">
				<small><?php el('The maximum value for the rating.'); ?></small>
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Field name'); ?></label>
				<input type="text" value="rating<?php echo $n; ?>" name="Connection[views][<?php echo $n; ?>][params][name]">
				<small><?php el('The name of the hidden field used to store the calculated value.'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Field ID'); ?></label>
				<input type="text" value="rating<?php echo $n; ?>" name="Connection[views][<?php echo $n; ?>][params][id]">
				<small><?php el('The id of the hidden field used to store the calculated value.'); ?></small>
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Editable'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][interactive]" class="ui fluid dropdown">
					<option value="1"><?php el('Yes'); ?></option>
					<option value="0"><?php el('No'); ?></option>
				</select>
				<small><?php el('The rating can be changed by user'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Clearable'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][clearable]" class="ui fluid dropdown">
					<option value="1"><?php el('Yes'); ?></option>
					<option value="0"><?php el('No'); ?></option>
				</select>
				<small><?php el('The rating can be cleared by the user'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Size'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][size]" class="ui fluid dropdown">
					<option value=""><?php el('Default'); ?></option>
					<option value="mini"><?php el('Mini'); ?></option>
					<option value="tiny"><?php el('Tiny'); ?></option>
					<option value="small"><?php el('Small'); ?></option>
					<option value="large"><?php el('Large'); ?></option>
					<option value="huge"><?php el('Huge'); ?></option>
					<option value="massive"><?php el('Massive'); ?></option>
				</select>
				<small><?php el('The widget size.'); ?></small>
			</div>
		</div>
		
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="view-<?php echo $n; ?>-permissions">
		<div class="two fields">
			<div class="field">
				<label><?php el('Owner id value'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][owner_id]">
			</div>
		</div>
		
		<?php $this->view('views.permissions_manager', ['model' => 'Connection[views]['.$n.']', 'perms' => ['access' => rl('Access')], 'groups' => $this->get('groups')]); ?>
	</div>
	
	<button type="button" class="ui button compact red tiny close_config forms_conf"><?php el('Close'); ?></button>
</div>