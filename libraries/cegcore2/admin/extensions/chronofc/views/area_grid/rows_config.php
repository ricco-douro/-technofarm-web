<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui fluid container small clonable_container" data-group="rows" data-counter="<?php echo !empty($item['rows']) ? max(array_keys($item['rows'])) : 1; ?>">
<?php
	if(empty($item['rows'])){
		$item['rows'] = [1 => ['columns' => [1 => 1]]];
	}
	$row_columns = [
		'equal width' => rl('Equal'),
		'two column' => rl('Two'),
		'three column' => rl('Three'),
		'four column' => rl('Four'),
		'five column' => rl('Five'),
		'six column' => rl('Six'),
		'seven column' => rl('Seven'),
	];
	$column_width = [
		'' => rl('Auto'),
		'two wide' => rl('Two'),
		'three wide' => rl('Three'),
		'four wide' => rl('Four'),
		'five wide' => rl('Five'),
		'six wide' => rl('Six'),
		'seven wide' => rl('Seven'),
		'eight wide' => rl('Eight'),
		'nine wide' => rl('Nine'),
		'ten wide' => rl('Ten'),
		'eleven wide' => rl('Eleven'),
		'twelve wide' => rl('Twelve'),
		'thrteen wide' => rl('Thrteen'),
		'fourteen wide' => rl('Fourteen'),
	];
	
?>
<?php foreach($item['rows'] as $kc => $item_row): ?>
<div class="field clonable" data-group="rows" data-counter="<?php echo $kc; ?>">
	<h5 class="ui horizontal divider header">
	  <i class="bars icon"></i>
	  Row
	</h5>
	<?php foreach($item_row['columns'] as $kg => $row_column): ?>
	<div class="ui fluid container clonable_container" data-group="columns" data-counter="<?php echo !empty($item_row['columns']) ? max(array_keys($item_row['columns'])) : 1; ?>">
		<div class="field clonable" data-group="columns" data-counter="<?php echo $kg; ?>">
			<div class="fields">
				<div class="three wide field">
					<input type="text" value="row<?php echo $kc; ?>_column<?php echo $kg; ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][sections][<?php echo $kc.'_'.$kg; ?>][name]" data-origin='{"value":"rowrows-N-_columncolumns-N-","name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][sections][rows-N-_columns-N-][name]"}'>
					<small><?php el('Unique name'); ?></small>
				</div>
				<div class="three wide field">
					<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][columns][<?php echo $kg; ?>][width]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][columns][columns-N-][width]"}'>
						<?php foreach($column_width as $cwk => $cwv): ?>
						<option value="<?php echo $cwk; ?>"><?php echo $cwv; ?></option>
						<?php endforeach; ?>
					</select>
					<small><?php el('Column width'); ?></small>
				</div>
				<div class="three wide field">
					<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][columns][<?php echo $kg; ?>][class]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][columns][columns-N-][class]"}'>
					<small><?php el('Class'); ?></small>
				</div>
				<div class="two wide field">
					<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][columns][<?php echo $kg; ?>][halign]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][columns][columns-N-][halign]"}'>
						<option value=""><?php el('Left'); ?></option>
						<option value="right aligned"><?php el('Right'); ?></option>
						<option value="center aligned"><?php el('Center'); ?></option>
					</select>
					<small><?php el('Text alignment'); ?></small>
				</div>
				<div class="two wide field">
					<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][columns][<?php echo $kg; ?>][floating]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][columns][columns-N-][floating]"}'>
						<option value=""></option>
						<option value="right floated"><?php el('Right'); ?></option>
						<option value="left floated"><?php el('Left'); ?></option>
					</select>
					<small><?php el('Floating'); ?></small>
				</div>
				<div class="three wide field">
					<button type="button" data-group="columns" class="ui button icon compact labeled green mini add_clone"><i class="icon add"></i><?php el('Column'); ?></button>
					<button type="button" data-group="columns" class="ui button icon compact red mini <?php if($kg < 2): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="equal width fields">
		<div class="two wide field">
			<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][column_count]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][column_count]"}'>
				<?php foreach($row_columns as $rck => $rcv): ?>
				<option value="<?php echo $rck; ?>"><?php echo $rcv; ?></option>
				<?php endforeach; ?>
			</select>
			<small><?php el('Column count'); ?></small>
		</div>
		<div class="three wide field">
			<input type="text" value="" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][class]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][class]"}'>
			<small><?php el('Class'); ?></small>
		</div>
		<div class="two wide field">
			<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][valign]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][valign]"}'>
				<option value="top aligned"><?php el('Top'); ?></option>
				<option value="middle aligned"><?php el('Middle'); ?></option>
				<option value="bottom aligned"><?php el('Bottom'); ?></option>
			</select>
			<small><?php el('Vertical align'); ?></small>
		</div>
		<div class="two wide field">
			<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][centered]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][centered]"}'>
				<option value=""><?php el('No'); ?></option>
				<option value="centered"><?php el('Yes'); ?></option>
			</select>
			<small><?php el('Centered'); ?></small>
		</div>
		<div class="two wide field">
			<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][<?php echo $kc; ?>][stretched]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][rows][rows-N-][stretched]"}'>
				<option value=""><?php el('No'); ?></option>
				<option value="stretched"><?php el('Yes'); ?></option>
			</select>
			<small><?php el('Stretched content'); ?></small>
		</div>
		<div class="three wide field">
			<button type="button" data-group="rows" class="ui button icon compact labeled blue mini add_clone"><i class="icon add"></i><?php el('Row'); ?></button>
			<button type="button" data-group="rows" class="ui button icon compact red mini <?php if($kc < 2): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
		</div>
	</div>
</div>
<?php endforeach; ?>
</div>
<div class="ui divider"></div>