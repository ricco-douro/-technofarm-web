<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui fluid container small clonable_container" data-group="specials" data-counter="<?php echo !empty($item['specials']) ? max(array_keys($item['specials'])) : 0; ?>">
<div class="field">
	<button type="button" data-group="specials" data-grouptype="rule" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Special field'); ?></button>
</div>
<?php
	if(empty($item['specials'])){
		$item['specials'] = [1];
	}else{
		$item['specials'] = [1] + $item['specials'];
	}
	
?>
<?php foreach($item['specials'] as $kc => $item_special): ?>
	<div class="field clonable" data-group="specials" data-grouptype="rule" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="equal width fields">
			<div class="six wide field">
				<input type="text" placeholder="<?php el('table field name'); ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][specials][<?php echo $kc; ?>][name]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][specials][specials-N-][name]"}'>
			</div>
			
			<div class="four wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][specials][<?php echo $kc; ?>][action]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][specials][specials-N-][action]"}'>
					<option value="increment"><?php el('Increment'); ?></option>
					<option value="decrement"><?php el('Decrement'); ?></option>
					<option value="json"><?php el('JSON encode'); ?></option>
				</select>
			</div>
			<div class="four wide field">
				<input type="text" placeholder="<?php el('value'); ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][specials][<?php echo $kc; ?>][value]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][specials][specials-N-][value]"}'>
			</div>
			<div class="two wide field">
				<button type="button" data-group="specials" class="ui button icon compact blue tiny add_clone"><i class="plus icon"></i></button>
				<button type="button" data-group="specials" class="ui button icon compact red tiny <?php if($kc == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
			</div>
		</div>
	</div>
<?php endforeach; ?>

</div>