<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<style>
	.save_link{display:none !important;}
</style>

<?php $this->view(\G2\Globals::ext_path('chronofc', 'admin').DS.'themes'.DS.'default'.DS.'views'.DS.'designer.php'); ?>

<form action="<?php echo r2('index.php?ext=chronoforms&cont=blocks'); ?>" method="post" name="admin_form" id="admin_form" class="ui form">

	<h2 class="ui header"><?php echo !empty($this->data['Block']['title']) ? $this->data['Block']['title'] : rl('New block'); ?></h2>
	<div class="ui">
		<button type="button" class="ui button compact green icon labeled toolbar-button" name="save" data-fn="saveform" data-url="<?php echo r2('index.php?ext=chronoforms&cont=blocks&act=edit'); ?>">
			<i class="check icon"></i><?php el('Save & close'); ?>
		</button>
		<button type="button" class="ui button compact blue icon labeled toolbar-button" name="apply" data-fn="saveform" data-url="<?php echo r2('index.php?ext=chronoforms&cont=blocks&act=edit'); ?>">
			<i class="check icon"></i><?php el('Save'); ?>
		</button>
		<a class="ui button compact red icon labeled toolbar-button" href="<?php echo r2('index.php?ext=chronoforms&cont=blocks'); ?>">
			<i class="cancel icon"></i><?php el('Close'); ?>
		</a>
		<a class="ui button compact orange icon labeled toolbar-button right floated <?php if(empty($this->data['Block']['id'])): ?>disabled<?php endif; ?>" href="<?php echo r2('index.php?ext=chronoforms&cont=blocks&act=backup&gcb[]='.$this->data['Block']['id']); ?>">
			<i class="download icon"></i><?php el('Backup'); ?>
		</a>
		
	</div>
	
	<div class="ui clearing divider"></div>
	
	<div class="ui top attached tiny steps G2-tabs">
		<a class="step active" data-tab="general">
			<i class="settings icon"></i>
			<div class="content"><div class="title"><?php el('General'); ?></div><div class="description"><?php el('Basic settings'); ?></div></div>
		</a>
		<a class="step" data-tab="editor">
			<i class="boxes icon"></i>
			<div class="content"><div class="title"><?php el('Editor'); ?></div><div class="description"><?php el('Block editor'); ?></div></div>
		</a>
	</div>
	
	<div class="ui bottom attached tab segment active" data-tab="general">
		<input type="hidden" name="Block[id]" value="">
		<input type="hidden" name="Block[type]" value="">
		
		<div class="two fields">
			<div class="field">
				<label><?php el('Title'); ?></label>
				<input type="text" placeholder="<?php el('Title'); ?>" name="Block[title]">
				<small><?php el('The block title as going to appear in the wizard designer.'); ?></small>
			</div>
			<div class="field">
				<label><?php el('Group'); ?></label>
				<input type="text" name="Block[group]">
				<small><?php el('The group to which the block belongs in the wizard, can be left empty.'); ?></small>
			</div>
		</div>
		
		<div class="two fields">
			<div class="field">
				<div class="ui checkbox">
					<input type="hidden" name="Block[published]" data-ghost="1" value="">
					<input type="checkbox" checked="checked" class="hidden" name="Block[published]" value="1">
					<label><?php el('Published'); ?></label>
					<small><?php el('Enable or disable this block.'); ?></small>
				</div>
			</div>
		</div>
		
		<div class="field">
			<label><?php el('Description'); ?></label>
			<textarea placeholder="<?php el('Description'); ?>" name="Block[desc]" id="conndesc" rows="5"></textarea>
			<small><?php el('Block description shown in wizard tooltips.'); ?></small>
		</div>
		
		<div class="eight wide field">
			<label><?php el('Block unique id'); ?></label>
			<input type="text" name="Block[block_id]">
			<small><?php el('Optionally provide a unique id for this block, it will be used to update the block data if a block with the same id was restored.'); ?></small>
		</div>
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="editor">
		<?php if($this->data['Block']['type'] == 'views'): ?>
		<div class="ui grid">
			<input type="hidden" value="<?php echo empty($this->data['Connection']['views']) ? 1 : max(array_keys($this->data['Connection']['views'])) + 1; ?>" id="views-count">
			
			<div class="four wide column scrollableBox">
				<?php
					//get views files
					$views = \G2\L\Folder::getFolders(\G2\Globals::ext_path('chronofc', 'admin').'views'.DS);
					$views_info = [];
					foreach($views as $view){
						$name = basename($view);
						$info_file = $view.DS.$name.'.ini';
						$info = parse_ini_file($info_file);
						if(!empty($info['apps'])){
							if(!in_array('forms', $info['apps'])){
								continue;
							}
						}
						if($info['name'] == 'stored_block'){
							continue;
						}
						$views_info[$name] = $info;
					}
					$types = ['core'];
					$views_info2 = ['core' => $views_info];
					$views_groups = ['core' => array_unique(array('Fields') + \G2\L\Arr::getVal($views_info, '[n].group', []))];
				?>
				<div class="ui secondary pointing menu small G2-tabs">
					<a class="item active" data-tab="viewslist-core"><?php el('Core'); ?></a>
				</div>
				
				<?php foreach($types as $kt => $type): ?>
				<div class="ui bottom attached tab <?php if($kt == 0): ?>active<?php endif; ?>" data-tab="viewslist-<?php echo $type; ?>">
					<div class="ui fluid accordion styled draggable-list">
						<?php foreach($views_groups[$type] as $kvg => $views_group): ?>
						<div class="title ui header small blue <?php if(empty($kvg)): ?> active<?php endif; ?>"><i class="dropdown icon"></i><?php echo $views_group; ?></div>
						<div class="content <?php if(empty($kvg)): ?> active<?php endif; ?>">
							
							<div class="ui grid center aligned small views-list">
								<?php foreach($views_info2[$type] as $vw_name => $vw_info): ?>
									<?php if($vw_info['group'] == $views_group): ?>
									<div class="eight wide large screen sixteen wide small screen column draggable" data-type="view" data-info='<?php echo json_encode($vw_info); ?>' style="padding:5px;" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=block_config&tvout=view'); ?>">
										<div class="ui segment tiny" style="padding:5px 1px;" <?php if(!empty($vw_info['desc'])): ?>data-hint="<?php echo nl2br($vw_info['desc']); ?>"<?php endif; ?>>
											<div class="ui header small">
												<?php if(!empty($vw_info['icon'])): ?>
												<i class="icon <?php echo $vw_info['icon']; ?> fitted"></i>
												<?php endif; ?>
												<?php echo $vw_info['title']; ?>
											</div>
										</div>
									</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endforeach; ?>
				
			</div>
			
			<div class="twelve wide column">
				<div class="ui container fluid section-data areas">
					<?php foreach($this->data['sections'] as $section_n => $section): ?>
						<?php $this->view('views.connections.sections_config', ['name' => $section['name'], 'count' => $section_n, 'block_editor' => true, 'views' => !empty($this->data['Block']['content']) ? $this->data['Block']['content'] : array()]); ?>
					<?php endforeach; ?>
				</div>
			</div>
			
		</div>
		<?php elseif($this->data['Block']['type'] == 'functions'): ?>
			<div class="ui grid">
				<input type="hidden" value="<?php echo empty($this->data['Connection']['functions']) ? 1 : max(array_keys($this->data['Connection']['functions'])) + 1; ?>" id="functions-count" name="functions-count">
				
				<div class="four wide column scrollableBox">
					<?php
						//get views files
						$functions = \G2\L\Folder::getFolders(\G2\Globals::ext_path('chronofc', 'admin').'functions'.DS);
						$functions_info = [];
						foreach($functions as $function){
							$name = basename($function);
							$info_file = $function.DS.$name.'.ini';
							$info = parse_ini_file($info_file);
							if(!empty($info['apps'])){
								if(!in_array('forms', $info['apps'])){
									continue;
								}
							}
							if(!isset($info['color'])){
								$info['color'] = 'blue';
							}
							if(!empty($info['private']) AND !empty($public)){
								continue;
							}
							if(!empty($info['platform']) AND !in_array(\G2\Globals::get('app'), $info['platform'])){
								continue;
							}
							if($info['name'] == 'stored_block'){
								continue;
							}
							$functions_info[$name] = $info;
						}
						$types = ['core'];
						$functions_info2 = ['core' => $functions_info];
						$functions_groups = ['core' => array_unique(array('Basic') + \G2\L\Arr::getVal($functions_info, '[n].group', []))];
						asort($functions_groups['core']);
					?>
					<div class="ui secondary pointing menu small G2-tabs">
						<a class="item active" data-tab="functionslist-core"><?php el('Core'); ?></a>
					</div>
					
					<?php foreach($types as $kt => $type): ?>
					<div class="ui bottom attached tab <?php if($kt == 0): ?>active<?php endif; ?>" data-tab="functionslist-<?php echo $type; ?>">
						<div class="ui fluid accordion styled draggable-list">
							<?php foreach($functions_groups[$type] as $kfg => $functions_group): ?>
							<div class="title ui header small blue <?php if(empty($kfg)): ?> active<?php endif; ?>"><i class="dropdown icon"></i><?php echo $functions_group; ?></div>
							<div class="content<?php if(empty($kfg)): ?> active<?php endif; ?>">
								
								<div class="ui grid center aligned small functions-list">
									<?php foreach($functions_info2[$type] as $fn_name => $fn_info): ?>
										<?php if($fn_info['group'] == $functions_group): ?>
										<div class="eight wide large screen sixteen wide small screen column draggable" data-type="function" data-private="<?php echo !empty($fn_info['private']) ? 1 : 0; ?>" data-info='<?php echo json_encode($fn_info); ?>' style="padding:5px;" data-url="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=block_config&tvout=view'); ?>">
											<div class="ui segment tiny" style="padding:5px 1px;">
												<div class="ui header small">
												<?php if(!empty($fn_info['icon'])): ?>
												<i class="icon <?php echo $fn_info['icon']; ?> fitted"></i>
												<?php endif; ?>
												<?php echo $fn_info['title']; ?>
												</div>
											</div>
										</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
								
							</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endforeach; ?>
					
				</div>
				
				<div class="twelve wide column">
					<div class="ui container fluid event-data areas">
						<?php foreach($this->data['events'] as $event_n => $event): ?>
							<?php $this->view('views.connections.events_config', ['name' => $event['name'], 'count' => $event_n, 'block_editor' => true, 'functions' => !empty($this->data['Block']['content']) ? $this->data['Block']['content'] : array()]); ?>
						<?php endforeach; ?>
					</div>
				</div>
				
			</div>
		<?php endif; ?>
	</div>
	
</form>
