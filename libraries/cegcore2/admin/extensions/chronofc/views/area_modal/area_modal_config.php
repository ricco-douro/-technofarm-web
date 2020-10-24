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
		<input type="hidden" value="area_modal" name="Connection[views][<?php echo $n; ?>][type]">
		
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
		
		<div class="two fields advanced_conf forms_conf">
			<div class="six wide field">
				<label><?php el('ID'); ?></label>
				<input type="text" value="area_modal_<?php echo $n; ?>" name="Connection[views][<?php echo $n; ?>][id]">
			</div>
			
			<div class="ten wide field">
				<label><?php el('Class'); ?></label>
				<input type="text" value="ui modal" name="Connection[views][<?php echo $n; ?>][class]">
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Popup header'); ?></label>
			<input type="text" value="" name="Connection[views][<?php echo $n; ?>][header]">
			<small><?php el('The header text of the modal or leave empty.'); ?></small>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Show on page load'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][pageload]" class="ui fluid dropdown">
					<option value=""><?php el('No'); ?></option>
					<option value="1" selected="selected"><?php el('Yes'); ?></option>
				</select>
				<small><?php el('Display the form popup when the page has finished loading.'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Display after x miliseconds'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][delay]">
				<small><?php el('Display the form popup after x miliseconds of page load.'); ?></small>
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Display on click of'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][trigger]">
				<small><?php el('The selector of the element to trigger the form popup, use #element_id or .element_class'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Display after scroll space'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][scroll]">
				<small><?php el('Display the form popup after the page has been scrolled x px.'); ?></small>
			</div>
		</div>
		<!--
		<div class="field">
			<label><?php el('Replacement views'); ?></label>
			<textarea name="Connection[views][<?php echo $n; ?>][replacement]" rows="5"></textarea>
			<small><?php el('Enter any code to be displayed instead of the form, the code may contain the trigger element.'); ?></small>
		</div>
		-->
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Modal size'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][size]" class="ui fluid dropdown">
					<option value="fullscreen"><?php el('Full screen'); ?></option>
					<option value="small"><?php el('Small'); ?></option>
					<option value="tiny"><?php el('Smaller'); ?></option>
					<option value="mini"><?php el('Smallest'); ?></option>
				</select>
				<small><?php el('The width of the popup modal.'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Basic layout'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][basic]" class="ui fluid dropdown">
					<option value=""><?php el('No'); ?></option>
					<option value="1"><?php el('Ye'); ?></option>
				</select>
				<small><?php el('A basic layout has no popup frame.'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Light background'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][inverted]" class="ui fluid dropdown">
					<option value="1"><?php el('Yes'); ?></option>
					<option value=""><?php el('No'); ?></option>
				</select>
				<small><?php el('The popup background will be white.'); ?></small>
			</div>
		</div>
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Detachable'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][detachable]" class="ui fluid dropdown">
					<option value="0"><?php el('No'); ?></option>
					<option value="1"><?php el('Yes'); ?></option>
				</select>
				<small><?php el('Should the modal dom element get moved outside body ?'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Scrollable content'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][scrolling]" class="ui fluid dropdown">
					<option value="0"><?php el('No'); ?></option>
					<option value="1"><?php el('Yes'); ?></option>
				</select>
				<small><?php el('The modal content area is scrollable ?'); ?></small>
			</div>
		</div>
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Closable'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][closable]" class="ui fluid dropdown">
					<option value="1"><?php el('Yes'); ?></option>
					<option value=""><?php el('No'); ?></option>
				</select>
				<small><?php el('Will close when the background is clicked.'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Close icon'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][close_icon]" class="ui fluid dropdown">
					<option value="1"><?php el('Yes'); ?></option>
					<option value=""><?php el('No'); ?></option>
				</select>
				<small><?php el('Display a close button ?'); ?></small>
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