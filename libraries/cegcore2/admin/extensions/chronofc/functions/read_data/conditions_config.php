<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui fluid container small clonable_container" data-group="conditions" data-counter="<?php echo !empty($item['conditions']) ? max(array_keys($item['conditions'])) : 0; ?>">
<div class="field">
	<button type="button" data-group="conditions" data-grouptype="rule" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Condition'); ?></button>
	<button type="button" data-group="conditions" data-grouptype="param" class="ui button icon compact labeled green tiny add_clone"><i class="plus icon"></i><?php el('Operator'); ?></button>
</div>
<?php
	if(empty($item['conditions'])){
		$item['conditions'] = [1];
	}else{
		$item['conditions'] = [1] + $item['conditions'];
	}
	
	if(empty($conditions_list)){
		$conditions_list = [
			
		];
	}
?>
<?php foreach($item['conditions'] as $kc => $item_condition): ?>
	<?php if($kc == 0 OR $item_condition['_type'] == 'rule'): ?>
	<div class="field clonable" data-group="conditions" data-grouptype="rule" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="equal width fields">
			<input type="hidden" value="rule" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][_type]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][_type]"}'>
			
			<div class="five wide field">
				<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][name]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][name]"}'>
				<small><?php el('Table field name'); ?></small>
			</div>
			<div class="three wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][namep]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][namep]"}'>
					<option value="=">=</option>
					<option value="!=">!=</option>
					<option value=">">></option>
					<option value=">=">>=</option>
					<option value="<"><</option>
					<option value="<="><=</option>
					<option value="LIKE">LIKE</option>
					<option value="IN">IN</option>
					<option value="IS">IS</option>
					<option value="IS NOT">IS NOT</option>
				</select>
				<small><?php el('Condition'); ?></small>
			</div>
			<div class="four wide field">
				<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][value]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][value]"}'>
				<small><?php el('Value'); ?></small>
			</div>
			<div class="two wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][valuep]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][valuep]"}'>
					<option value=""></option>
					<option value="-"><?php el('Ignore'); ?></option>
					<option value="+"><?php el('Abort'); ?></option>
				</select>
				<small><?php el('Null value'); ?></small>
			</div>
			<div class="two wide field">
				<button type="button" data-group="conditions" class="ui button icon compact blue tiny add_clone"><i class="plus icon"></i></button>
				<button type="button" data-group="conditions" class="ui button icon compact red tiny <?php if($kc == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<?php if($kc == 0 OR $item_condition['_type'] == 'param'): ?>
	<div class="field clonable" data-group="conditions" data-grouptype="param" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
		<div class="fields">
			<input type="hidden" value="param" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][_type]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][_type]"}'>
			<div class="three wide field">
				<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][<?php echo $kc; ?>][name]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][conditions][conditions-N-][name]"}'>
					<option value="AND">AND</option>
					<option value="OR">OR</option>
					<option value="(">(</option>
					<option value=")">)</option>
				</select>
			</div>
			<div class="field">
				<button type="button" data-group="conditions" class="ui button icon compact blue tiny add_clone"><i class="plus icon"></i></button>
				<button type="button" data-group="conditions" class="ui button icon compact red tiny <?php if($kc == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
			</div>
		</div>
	</div>
	<?php endif; ?>
<?php endforeach; ?>

</div>