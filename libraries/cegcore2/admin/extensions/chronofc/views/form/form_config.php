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
		<input type="hidden" value="form" name="Connection[views][<?php echo $n; ?>][type]">
		
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
		
		<div class="two fields">
			<div class="field">
				<label><?php el('Data provider'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][data_provider]">
			</div>
			<div class="field">
				<label><?php el('Submit Event'); ?></label>
				<input type="text" value="submit" name="Connection[views][<?php echo $n; ?>][event]">
				<small class="field-desc"><?php el('The event to which the form data will be sent.'); ?></small>
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Action URL and/or parameters'); ?></label>
			<input type="text" value="" name="Connection[views][<?php echo $n; ?>][parameters]">
			<small class="field-desc"><?php el('The url to which the form data will be sent, use this only if you have a custom form.'); ?></small>
		</div>
		
		<div class="three fields">
			
			<div class="field">
				<label><?php el('AJAX submit'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][dynamic]" class="ui fluid dropdown">
					<option value=""><?php el('No'); ?></option>
					<option value="1"><?php el('Yes'); ?></option>
				</select>
			</div>
			
			<div class="field">
				<label><?php el('Invisible form'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][invisible]" class="ui fluid dropdown">
					<option value=""><?php el('No'); ?></option>
					<option value="1"><?php el('Yes'); ?></option>
				</select>
				<small class="field-desc"><?php el('When enabled, the form tag will not be available until the page is loaded.'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('KeepAlive'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][keepalive]" class="ui fluid dropdown">
					<option value=""><?php el('No'); ?></option>
					<option value="1"><?php el('Yes'); ?></option>
				</select>
				<small class="field-desc"><?php el('When enabled, the user session will not expire when the form is opened.'); ?></small>
			</div>
		</div>
		
		<div class="two fields">
			<div class="field">
				<label><?php el('Validation messages'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][validation][type]" class="ui fluid dropdown">
					<option value="inline"><?php el('Inline tooltips'); ?></option>
					<option value="inlinetext"><?php el('Inline error messages'); ?></option>
					<option value="message"><?php el('Errors list below form'); ?></option>
				</select>
			</div>
			
			<div class="field">
				<label><?php el('Submit animation'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][submit_animation]" class="ui fluid dropdown">
					<option value="1"><?php el('Yes'); ?></option>
					<option value=""><?php el('No'); ?></option>
				</select>
				<small><?php el('When enabled, the form will display a loading icon when its submitting the data to server.'); ?></small>
			</div>
			
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Class'); ?></label>
				<input type="text" value="ui form" name="Connection[views][<?php echo $n; ?>][class]">
				<small class="field-desc"><?php el('A class to apply to your form, changing this may affect your form appearance.'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Form ID'); ?></label>
				<input type="text" value="" placeholder="<?php el('Auto'); ?>" name="Connection[views][<?php echo $n; ?>][formid]">
			</div>
		</div>
		
		<div class="field advanced_conf">
			<label><?php el('Content'); ?></label>
			<textarea placeholder="<?php el('HTML or PHP Code with tags'); ?>" name="Connection[views][<?php echo $n; ?>][content]" rows="10"></textarea>
			<small><?php el('Your form contents, usually contains a call of one of more fields views.'); ?></small>
		</div>
		
		<div class="field">
			<label><?php el('Size'); ?></label>
			<select name="Connection[views][<?php echo $n; ?>][size]" class="ui fluid dropdown">
				<option value=""><?php el('Default'); ?></option>
				<option value="mini"><?php el('Mini'); ?></option>
				<option value="tiny"><?php el('Tiny'); ?></option>
				<option value="small"><?php el('Small'); ?></option>
				<option value="large"><?php el('Large'); ?></option>
				<option value="big"><?php el('Big'); ?></option>
				<option value="huge"><?php el('Huge'); ?></option>
				<option value="massive"><?php el('Massive'); ?></option>
			</select>
			<small class="field-desc"><?php el('Select the size class of the form element.'); ?></small>
		</div>
		<div class="field">
			<label><?php el('Form tag attributes'); ?></label>
			<textarea rows="3" name="Connection[views][<?php echo $n; ?>][attrs]" placeholder="<?php el('Multiline list of attributes'); ?>"></textarea>
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
	
</div>