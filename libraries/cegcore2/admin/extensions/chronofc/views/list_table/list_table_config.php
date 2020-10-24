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
		<input type="hidden" value="list_table" name="Connection[views][<?php echo $n; ?>][type]">
		
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
				<small><?php el('The data set used to populat the table list.'); ?></small>
			</div>
			
			<div class="field">
				<label><?php el('Class'); ?></label>
				<input type="text" value="ui selectable table" name="Connection[views][<?php echo $n; ?>][class]">
				<small><?php el('The class attribute, changing this will affect how your table looks.'); ?></small>
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Columns list'); ?></label>
			<textarea name="Connection[views][<?php echo $n; ?>][sections]" rows="10"><?php echo "cell1:Header 1\ncell2:Header 2"; ?></textarea>
			<small><?php el('A list of the table columns fields names and headers, example: Model.field_name:Header'); ?></small>
		</div>
		
		<button type="button" class="ui button small refresh_dragged" data-block="view" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=refresh_element&tvout=view'); ?>"><?php el('Update table cells'); ?></button>
		
		<div class="field">
			<label><?php el('Columns classes'); ?></label>
			<textarea name="Connection[views][<?php echo $n; ?>][classes]" rows="5"></textarea>
			<small><?php el('A list of the table columns fields names and the column class, example: Model.field_name:class_name'); ?></small>
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