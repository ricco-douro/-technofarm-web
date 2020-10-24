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
		<input type="hidden" value="area_partitions" name="Connection[views][<?php echo $n; ?>][type]">
		
		<div class="two fields advanced_conf">
			<div class="field">
				<label><?php el('Name'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][name]" class="view_name">
			</div>
			<div class="field">
				<label><?php el('Category'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][category]">
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Designer Label'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][label]">
			</div>
			<div class="field">
				<label><?php el('Data provider'); ?></label>
				<input type="text" value="" name="Connection[views][<?php echo $n; ?>][data_provider]">
				<small><?php el('Optional data provider'); ?></small>
			</div>
			
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Class'); ?></label>
				<input type="text" value="ui container fluid" name="Connection[views][<?php echo $n; ?>][class]">
			</div>
			
			<div class="field">
				<label><?php el('Style'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][style]" class="ui fluid dropdown">
					<option value="tabular menu"><?php el('Tabular menu'); ?></option>
					<option value="vertical tabular menu"><?php el('Vertical Tabular menu'); ?></option>
					<option value="menu"><?php el('Primary Menu'); ?></option>
					<option value="secondary menu"><?php el('Secondary Menu'); ?></option>
					<option value="pointing menu"><?php el('Pointing Menu'); ?></option>
					<option value="secondary pointing menu"><?php el('Secondary Pointing Menu'); ?></option>
					<option value="text menu"><?php el('Text menu'); ?></option>
					<option value="steps"><?php el('Steps'); ?></option>
					<option value="vertical steps"><?php el('Vertical Steps'); ?></option>
					<option value="sequence"><?php el('Sequence'); ?></option>
				</select>
			</div>
			
			<div class="field">
				<label><?php el('Sequential'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][sequential]" class="ui fluid dropdown">
					<option value="0"><?php el('No'); ?></option>
					<option value="1"><?php el('Yes'); ?></option>
				</select>
				<small class="field-desc"><?php el('If enabled, partitions will be disabled until the previous ones have been completed.', [], 'sequential partition desc'); ?></small>
			</div>
		</div>
		
		<div class="equal width fields">
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
				<small><?php el('The menu size.'); ?></small>
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
			
			<div class="field">
				<label><?php el('Attached style'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][attached]" class="ui fluid dropdown">
					<option value="top attached"><?php el('Top attached'); ?></option>
					<option value=""><?php el('Not attached'); ?></option>
				</select>
				<small><?php el('Affects how the navigation list appears'); ?></small>
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Forward button selector'); ?></label>
				<input type="text" value=".forward" readonly name="Connection[views][<?php echo $n; ?>][forward_selector]">
				<small><?php el('A button used to show the next area contents'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Backward button selector'); ?></label>
				<input type="text" value=".backward" readonly name="Connection[views][<?php echo $n; ?>][backward_selector]">
				<small><?php el('A button used to show the previous area contents'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Finish button selector'); ?></label>
				<input type="text" value=".finish" readonly name="Connection[views][<?php echo $n; ?>][finish_selector]">
				<small><?php el('A button used to finish the sequence and submit the form, it will stay hidden till the last step.'); ?></small>
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Partitions list'); ?></label>
			<textarea name="Connection[views][<?php echo $n; ?>][sections]" class="sections_list"><?php echo "part1:Part #1\npart2:Part #2"; ?></textarea>
		</div>
		
		<button type="button" class="ui button small refresh_dragged" data-block="view" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=refresh_element&tvout=view'); ?>"><?php el('Update partitions'); ?></button>
		
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