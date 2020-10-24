<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui fluid container small clonable_container" data-group="overrides" data-counter="<?php echo !empty($item['overrides']) ? max(array_keys($item['overrides'])) : 0; ?>">
<div class="field">
	<button type="button" data-group="overrides" data-grouptype="rule" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Data override'); ?></button>
</div>
<?php
	if(empty($item['overrides'])){
		$item['overrides'] = [1];
	}else{
		$item['overrides'] = [1] + $item['overrides'];
	}
	
?>
<?php foreach($item['overrides'] as $kc => $item_override): ?>
	<div class="field clonable" data-group="overrides" data-grouptype="rule" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="equal width fields">
			<div class="five wide field">
				<input type="text" placeholder="<?php el('table field name'); ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][overrides][<?php echo $kc; ?>][name]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][overrides][overrides-N-][name]"}'>
			</div>
			
			<div class="five wide field">
				<input type="text" placeholder="<?php el('value'); ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][overrides][<?php echo $kc; ?>][value]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][overrides][overrides-N-][value]"}'>
			</div>
			<div class="four wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][overrides][<?php echo $kc; ?>][action]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][overrides][overrides-N-][action]"}'>
					<option value="insert"><?php el('on insert'); ?></option>
					<option value="update"><?php el('on update'); ?></option>
					<option value="insert_update"><?php el('on insert & update'); ?></option>
				</select>
			</div>
			<div class="two wide field">
				<button type="button" data-group="overrides" class="ui button icon compact blue tiny add_clone"><i class="plus icon"></i></button>
				<button type="button" data-group="overrides" class="ui button icon compact red tiny <?php if($kc == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
			</div>
		</div>
	</div>
<?php endforeach; ?>

</div>