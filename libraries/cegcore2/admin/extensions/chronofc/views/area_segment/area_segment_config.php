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
		<input type="hidden" value="area_segment" name="Connection[views][<?php echo $n; ?>][type]">
		
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
				<input type="text" value="area_segment_<?php echo $n; ?>" name="Connection[views][<?php echo $n; ?>][id]">
			</div>
			
			<div class="ten wide field">
				<label><?php el('Class'); ?></label>
				<input type="text" value="ui segment" name="Connection[views][<?php echo $n; ?>][class]">
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Style'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][style]" class="ui fluid dropdown">
					<option value=""></option>
					<option value="raised"><?php el('Raised'); ?></option>
					<option value="stacked"><?php el('Stacked'); ?></option>
					<option value="piled"><?php el('Piled'); ?></option>
					<option value="vertical"><?php el('Vertical'); ?></option>
				</select>
				<small><?php el('The style affects element appearance.'); ?></small>
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
					<small><?php el('Compact style segment takes the width of its contents.'); ?></small>
				</div>
			</div>
			
			<div class="field">
				<div class="ui checkbox toggle">
					<input type="hidden" name="Connection[views][<?php echo $n; ?>][inverted]" data-ghost="1" value="">
					<input type="checkbox" class="hidden" name="Connection[views][<?php echo $n; ?>][inverted]" value="inverted">
					<label><?php el('Inverted'); ?></label>
					<small><?php el('Inverts the block colors'); ?></small>
				</div>
			</div>
			
			<div class="field">
				<div class="ui checkbox toggle">
					<input type="hidden" name="Connection[views][<?php echo $n; ?>][circular]" data-ghost="1" value="">
					<input type="checkbox" class="hidden" name="Connection[views][<?php echo $n; ?>][circular]" value="circular">
					<label><?php el('Circular'); ?></label>
					<small><?php el('Circular segment'); ?></small>
				</div>
			</div>
			
			<div class="field">
				<div class="ui checkbox toggle">
					<input type="hidden" name="Connection[views][<?php echo $n; ?>][basic]" data-ghost="1" value="">
					<input type="checkbox" class="hidden" name="Connection[views][<?php echo $n; ?>][basic]" value="basic">
					<label><?php el('Basic'); ?></label>
					<small><?php el('Borderless'); ?></small>
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
				<label><?php el('Padding'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][padded]" class="ui fluid dropdown">
					<option value=""><?php el('Normal'); ?></option>
					<option value="padded"><?php el('Padded'); ?></option>
					<option value="very padded"><?php el('Very padded'); ?></option>
				</select>
				<small><?php el('Affects the segment padding'); ?></small>
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
			<div class="field">
				<label><?php el('Floating'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][floating]" class="ui fluid dropdown">
					<option value=""><?php el('None'); ?></option>
					<option value="right floated"><?php el('Right floated'); ?></option>
					<option value="left floated"><?php el('Left floated'); ?></option>
				</select>
				<small><?php el('Segment floating'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Emphasis'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][emphasis]" class="ui fluid dropdown">
					<option value=""><?php el('None'); ?></option>
					<option value="secondary"><?php el('Secondary'); ?></option>
					<option value="tertiary"><?php el('Tertiary'); ?></option>
				</select>
				<small><?php el('Emphasis style'); ?></small>
			</div>
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