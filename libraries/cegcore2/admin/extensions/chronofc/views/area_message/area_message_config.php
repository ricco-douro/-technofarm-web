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
		<input type="hidden" value="area_message" name="Connection[views][<?php echo $n; ?>][type]">
		
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
		
		<div class="equal size fields">
			<div class="six wide field easy_disabled">
				<label><?php el('ID'); ?></label>
				<input type="text" value="area_message_<?php echo $n; ?>" name="Connection[views][<?php echo $n; ?>][id]">
			</div>
			
			<div class="ten wide field easy_disabled">
				<label><?php el('Class'); ?></label>
				<input type="text" value="ui message" name="Connection[views][<?php echo $n; ?>][class]">
			</div>
		</div>
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Style'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][style]" class="ui fluid dropdown">
					<option value=""></option>
					<option value="success"><?php el('Confirmation'); ?></option>
					<option value="error"><?php el('Error'); ?></option>
					<option value="info"><?php el('Information'); ?></option>
					<option value="warning"><?php el('Warning'); ?></option>
				</select>
				<small><?php el('The style affects element appearance.'); ?></small>
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
				<small><?php el('The message type affects the message style.'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Color'); ?></label>
				<div class="ui fluid selection dropdown">
					<input type="hidden" name="Connection[views][<?php echo $n; ?>][color]" value="" />
					<i class="dropdown icon"></i>
					<div class="default text"><?php el('Default'); ?></div>
					<div class="menu">
						<div class="item" data-value=""><div class="ui empty fluid label"></div></div>
						<div class="item" data-value="red"><div class="ui red empty fluid label"></div></div>
						<div class="item" data-value="orange"><div class="ui orange empty fluid label"></div></div>
						<div class="item" data-value="yellow"><div class="ui yellow empty fluid label"></div></div>
						<div class="item" data-value="olive"><div class="ui olive empty fluid label"></div></div>
						<div class="item" data-value="green"><div class="ui green empty fluid label"></div></div>
						<div class="item" data-value="teal"><div class="ui teal empty fluid label"></div></div>
						<div class="item" data-value="blue"><div class="ui blue empty fluid label"></div></div>
						<div class="item" data-value="violet"><div class="ui violet empty fluid label"></div></div>
						<div class="item" data-value="purple"><div class="ui purple empty fluid label"></div></div>
						<div class="item" data-value="pink"><div class="ui pink empty fluid label"></div></div>
						<div class="item" data-value="brown"><div class="ui brown empty fluid label"></div></div>
						<div class="item" data-value="grey"><div class="ui grey empty fluid label"></div></div>
						<div class="item" data-value="black"><div class="ui black empty fluid label"></div></div>
					</div>
				</div>
			</div>
		</div>
		<div class="equal width fields">
			<div class="field">
				<div class="ui checkbox toggle">
					<input type="hidden" name="Connection[views][<?php echo $n; ?>][compact]" data-ghost="1" value="">
					<input type="checkbox" class="hidden" name="Connection[views][<?php echo $n; ?>][compact]" value="compact">
					<label><?php el('Compact'); ?></label>
					<small><?php el('Compact style message takes the width of its contents.'); ?></small>
				</div>
			</div>
			<div class="field">
				<div class="ui checkbox toggle">
					<input type="hidden" name="Connection[views][<?php echo $n; ?>][floating]" data-ghost="1" value="">
					<input type="checkbox" class="hidden" name="Connection[views][<?php echo $n; ?>][floating]" value="floating">
					<label><?php el('Floating'); ?></label>
					<small><?php el('Floating style message'); ?></small>
				</div>
			</div>
		</div>
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Attached style'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][attached]" class="ui fluid dropdown">
					<option value=""><?php el('Normal'); ?></option>
					<option value="top attached"><?php el('Top attached'); ?></option>
					<option value="attached"><?php el('Middle attached'); ?></option>
					<option value="bottom attached"><?php el('Bottom attached'); ?></option>
				</select>
				<small><?php el('Affects the outer appearance of the message box'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Icon'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][icon]">
				<small><?php el('Icon class to use if required.'); ?></small>
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Header'); ?></label>
			<input type="text" value="" name="Connection[views][<?php echo $n; ?>][header]">
			<small><?php el('Header for the message box'); ?></small>
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