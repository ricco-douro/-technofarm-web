<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(empty($section_name) OR empty($view['_section']) OR $section_name == $view['_section']):
	$views_path = \G2\Globals::ext_path('chronofc', 'admin').'views'.DS.$type.DS.$type.'_config.php';
	$ini_path = \G2\Globals::ext_path('chronofc', 'admin').'views'.DS.$type.DS.$type.'.ini';
	$info = parse_ini_file($ini_path);
?>
<div class="ui segment blue dragged">
	
	
	<div class="dragged_actions">
		<i class="icon setting blue link edit_dragged" data-hint="<?php el('Edit'); ?>"></i>
		<i class="icon sort orange link sort_dragged" data-hint="<?php el('Sort'); ?>"></i>
		<i class="icon copy green link copy_dragged" data-hint="<?php el('Copy'); ?>" data-block="view" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=copy_element&tvout=view'); ?>"></i>
		<?php /* ?>
		<i class="icon save black link save_link G2-static" data-task="popup:#save-view-<?php echo $count; ?>" data-hint="<?php el('Save'); ?>"></i>
		<div class="ui popup top left transition hidden G2-static-popup" id="save-view-<?php echo $count; ?>" style="min-width:300px;">
			<div class="ui form">
				<div class="field required">
					<label><?php el('Block title'); ?></label>
					<input type="text" name="title" value="<?php echo isset($block_title) ? $block_title : (!empty($view) ? $name : $name.$count); ?>">
				</div>
				<div class="field">
					<label><?php el('Block ID (Optional)'); ?> <i class="icon info circular orange inverted small" data-hint="<?php el('If the ID matches another block id then the existing block will be updated.'); ?>"></i></label>
					<input type="text" name="block_id" value="<?php echo isset($block_id) ? $block_id : ''; ?>">
				</div>
				<div class="field">
					<div class="ui button black compact icon fluid G2-dynamic save_block" data-dtask="send/closest:.dragged" data-result="after/closest:.dragged" data-complete-message="<?php el("Block saved successfully."); ?>" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=save_block&tvout=view&type=views'); ?>"><?php el('Save block'); ?></div>
				</div>
			</div>
		</div>
		<?php */ ?>
		<i class="icon delete inverted"></i>
		<i class="icon delete red link delete_dragged" data-hint="<?php el('Delete'); ?>"></i>
	</div>
	
	<div class="ui label view_title"><?php echo $info['title']; ?></div>
	<div class="ui label black"><?php echo !empty($view) ? $name : $name.$count; ?></div>
	<?php if(!empty($view['params']['name'])): ?>
	<div class="ui label green"><?php echo $view['params']['name']; ?></div>
	<?php endif; ?>
	<?php if(!empty($view['label'])): ?>
	<div class="ui label blue basic" data-hint="<?php echo $view['label']; ?>"><?php echo substr($view['label'], 0, 40).(strlen($view['label']) > 30 ? '...' : ''); ?></div>
	<?php endif; ?>
	
	
	<div class="config_area transition hidden">
		<input type="hidden" value="" name="Connection[views][<?php echo $count; ?>][_section]" class="dragged_parent">
		<?php
			
			if(empty($this->data['Connection']['views'][$count])){
				$this->data['Connection']['views'][$count] = ['name' => $name.$count];
			}
			
			$this->view($views_path, ['n' => $count, 'view' => !empty($view) ? $view : []]);
		?>
	</div>
	<?php $view_name = !empty($view) ? $view['name'] : $type.$count; ?>
	<?php
		if(!empty($view['sections'])){
			if(is_array($view['sections'])){
				$vwsections = [];
				foreach($view['sections'] as $k => $section){
					$vwsections[$section['name']] = 'orange';
				}
			}else{
				$vwsections = array_fill_keys(array_map('trim', explode("\n", $view['sections'])), array_values($info['sections'])[0]);
			}
		}else if(!empty($info['sections'])){
			$vwsections = $info['sections'];
		}else{
			$vwsections = [];
		}
		if(!empty($info['sections2'])){
			$vwsections = array_merge($vwsections, $info['sections2']);
		}
	?>
	<?php if(!empty($vwsections)): ?>
		<div class="ui  fluid">
		<?php foreach($vwsections as $ename => $ecolor): ?>
			<?php $ename = explode(':', $ename)[0]; ?>
			<?php $fncount = 0; ?>
			<?php $icon = !empty($this->data('Connection.views.'.$count.'.'.$ename.'.minimized')) ? 'right' : 'down'; ?>
			<?php $active1 = !empty($this->data('Connection.views.'.$count.'.'.$ename.'.minimized')) ? '' : 'pointing below'; ?>
			<?php $active2 = !empty($this->data('Connection.views.'.$count.'.'.$ename.'.minimized')) ? 'transition hidden' : 'transition visible'; ?>
			<?php if(!empty($views)): ?>
				<?php foreach($views as $view_n => $view): ?>
					<?php if($view['_section'] == $view_name.'/'.$ename)$fncount++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<div class="ui label <?php echo $active1; ?> <?php echo $ecolor; ?> draggable-receiver-title minimize_area" style="cursor:pointer; margin:5px 0 0 0;" data-named="<?php echo $view_name; ?>/<?php echo $ename; ?>">
				<i class="chevron <?php echo $icon; ?> icon"></i>&nbsp;
				<?php echo $ename; ?><div class="detail"><?php echo $fncount; ?></div>
				<input type="hidden" value="0" name="Connection[views][<?php echo $count; ?>][<?php echo $ename; ?>][minimized]" data-minimized="<?php echo $view_name; ?>/<?php echo $ename; ?>">
			</div>
			<div class="<?php echo $active2; ?> ui segment <?php echo $ecolor; ?> view_section draggable-receiver" style="min-height:50px; margin-bottom:2px; margin-top:7px;<?php if(!empty($info['sections_css'][$ename])){echo $info['sections_css'][$ename];} ?>" data-name="<?php echo $view_name; ?>/<?php echo $ename; ?>">
				<?php if(!empty($views)): ?>
					<?php foreach($views as $view_n => $view): ?>
						<?php if(!empty($view['name'])): ?>
							<?php $this->view('views.connections.views_config', ['section_name' => $view_name.'/'.$ename, 'name' => $view['name'], 'type' => $view['type'], 'count' => $view_n, 'view' => $view, 'views' => $views]); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
		</div>
		
	<?php endif; ?>
</div>
<?php endif; ?>