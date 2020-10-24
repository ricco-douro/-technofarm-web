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
		<input type="hidden" value="area_grid" name="Connection[views][<?php echo $n; ?>][type]">
		
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
		
		<div class="field">
			<label><?php el('Designer Label'); ?></label>
			<input type="text" value="" name="Connection[views][<?php echo $n; ?>][label]">
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Class'); ?></label>
				<input type="text" value="ui grid" name="Connection[views][<?php echo $n; ?>][class]">
				<small><?php el('The number written here is the maximum number of columns per row.'); ?></small>
			</div>
			<!--
			<div class="field">
				<label><?php el('Grid Columns'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][columns]" class="ui fluid dropdown">
					<option value=""></option>
					<option value="two column"><?php el('Two'); ?></option>
					<option value="three column"><?php el('Three'); ?></option>
					<option value="four column"><?php el('Four'); ?></option>
					<option value="five column"><?php el('Five'); ?></option>
					<option value="six column"><?php el('Six'); ?></option>
					<option value="seven column"><?php el('Seven'); ?></option>
				</select>
				<small><?php el('The default number of grid ccolumns.'); ?></small>
			</div>
			-->
			<div class="field">
				<label><?php el('Stackable'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][stackable]" class="ui fluid dropdown">
					<option value=""><?php el('No'); ?></option>
					<option value="stackable"><?php el('Yes'); ?></option>
				</select>
				<small><?php el('Should the grid columns stack over each other on smaller screens'); ?></small>
			</div>
		</div>
		
		<div class="equal width fields">
			<div class="field">
				<label><?php el('Dividers'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][divided]" class="ui fluid dropdown">
					<option value=""><?php el('None'); ?></option>
					<option value="divided"><?php el('Columns dividers'); ?></option>
					<option value="vertically divided"><?php el('Rows dividers'); ?></option>
				</select>
			</div>
			<div class="field">
				<label><?php el('Celled'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][celled]" class="ui fluid dropdown">
					<option value=""><?php el('None'); ?></option>
					<option value="celled"><?php el('Full cells'); ?></option>
					<option value="internally celled"><?php el('Internally celled'); ?></option>
				</select>
			</div>
			<div class="field">
				<label><?php el('Padding'); ?></label>
				<select name="Connection[views][<?php echo $n; ?>][padded]" class="ui fluid dropdown">
					<option value=""><?php el('None'); ?></option>
					<option value="vertically padded"><?php el('Vertically padded'); ?></option>
					<option value="horizontally padded"><?php el('Horizontally padded'); ?></option>
					<option value="relaxed"><?php el('Relaxed'); ?></option>
				</select>
			</div>
		</div>
		<?php
			if(!empty($view['sections']) AND empty($view['rows'])){
				$view['rows'][0]['columns'] = [];
				$sections = array_filter(array_map('trim', explode("\n", $view['sections'])));
				$view['sections'] = [];
				foreach($sections as $sk => $section){
					$pts = explode(':', $section);
					$view['rows'][0]['columns'][$sk]['class'] = !empty($pts[1]) ? $pts[1] : '';
					$view['sections']['0_'.$sk]['name'] = $pts[0];
				}
				$this->data['Connection']['views'][$n] = $view;
			}
		?>
		<?php if(!empty($view['sections']) AND empty($view['rows'])): ?>
			<div class="field">
				<label><?php el('Columns list'); ?></label>
				<textarea name="Connection[views][<?php echo $n; ?>][sections]" class="sections_list"><?php echo "column1\ncolumn2"; ?></textarea>
			</div>
		<?php else: ?>
			<?php $this->view(dirname(__FILE__).DS.'rows_config.php', ['item' => $view, 'type' => 'views', 'n' => $n]); ?>
			<input type="hidden" name="Connection[views][<?php echo $n; ?>][_version]" value="2" />
		<?php endif; ?>
		
		<button type="button" class="ui button icon labeled orange fluid refresh_dragged" data-block="view" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=refresh_element&tvout=view'); ?>"><i class="icon refresh"></i><?php el('Update columns'); ?></button>
		
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