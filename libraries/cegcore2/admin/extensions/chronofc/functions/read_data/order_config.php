<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui fluid container small clonable_container" data-group="orders" data-counter="<?php echo !empty($item['orders']) ? max(array_keys($item['orders'])) : 0; ?>">
<div class="field">
	<button type="button" data-group="orders" data-grouptype="rule" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Order field'); ?></button>
</div>
<?php
	if(empty($item['orders'])){
		$item['orders'] = [1];
	}else{
		$item['orders'] = [1] + $item['orders'];
	}
	
?>
<?php foreach($item['orders'] as $kc => $item_order): ?>
	<div class="field clonable" data-group="orders" data-grouptype="rule" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="equal width fields">
			<div class="ten wide field">
				<input type="text" placeholder="<?php el('table field name'); ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][orders][<?php echo $kc; ?>][name]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][orders][orders-N-][name]"}'>
			</div>
			
			<div class="six wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][orders][<?php echo $kc; ?>][action]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][orders][orders-N-][action]"}'>
					<option value="asc"><?php el('Ascending'); ?></option>
					<option value="desc"><?php el('Descending'); ?></option>
				</select>
			</div>
			
			<div class="two wide field">
				<button type="button" data-group="orders" class="ui button icon compact blue tiny add_clone"><i class="plus icon"></i></button>
				<button type="button" data-group="orders" class="ui button icon compact red tiny <?php if($kc == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
			</div>
		</div>
	</div>
<?php endforeach; ?>

</div>