<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<div class="ui fluid container small clonable_container" data-group="events" data-counter="<?php echo !empty($item['events']) ? max(array_keys($item['events'])) : 0; ?>">
<?php
	if(empty($item['events'])){
		$item['events'] = [['groups' => [1]]];
	}else{
		$item['events'] = [['groups' => [1]]] + $item['events'];
	}
	
	if(empty($events_list)){
		$events_list = [
			'==' => rl('equals'),
			'!=' => rl('not equals'),
			'>' => rl('greater than'),
			'<' => rl('less than'),
			'>=' => rl('greater or equal'),
			'<=' => rl('less or equal'),
			'empty' => rl('is empty'),
			'!empty' => rl('is not empty'),
			'null' => rl('is NULL'),
			'!null' => rl('is not NULL'),
			'regex' => rl('matches'),
			'in' => rl('IN'),
			'numeric' => rl('is numeric'),
			'bool' => rl('is boolean'),
			'integer' => rl('is integer'),
			'string' => rl('is string'),
		];
	}
?>
<?php foreach($item['events'] as $kc => $item_event): ?>
<div class="field clonable" data-group="events" data-counter="<?php echo $kc; ?>"  <?php if($kc == 0): ?>data-source="1"<?php endif; ?>>
	<h5 class="ui horizontal divider header">
	  <i class="bars icon"></i>
	  Rule
	</h5>
	<?php foreach($item_event['groups'] as $kg => $event_group): ?>
	<div class="ui fluid container clonable_container" data-group="groups" data-counter="<?php echo !empty($item_event['groups']) ? max(array_keys($item_event['groups'])) : 0; ?>">
		<div class="field clonable" data-group="groups" data-counter="<?php echo $kg; ?>">
			<div class="fields">
				<div class="five wide field">
					<input type="text" placeholder="<?php echo el('Value 1'); ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][events][<?php echo $kc; ?>][groups][<?php echo $kg; ?>][first]" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][events][events-N-][groups][groups-N-][first]"}'>
				</div>
				<div class="four wide field">
					<select name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][events][<?php echo $kc; ?>][groups][<?php echo $kg; ?>][sign]" class="ui fluid dropdown small" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][events][events-N-][groups][groups-N-][sign]"}'>
						<?php foreach($events_list as $sign => $event): ?>
						<option value="<?php echo $sign; ?>"><?php echo $event; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="five wide field">
					<textarea placeholder="<?php echo el('Value 2'); ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][events][<?php echo $kc; ?>][groups][<?php echo $kg; ?>][second]" rows="1" data-autoresize="1" data-origin='{"name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][events][events-N-][groups][groups-N-][second]"}'></textarea>
				</div>
				<div class="two wide field">
					<button type="button" data-group="groups" class="ui button icon compact green tiny add_clone"><?php el('AND'); ?></button>
					<button type="button" data-group="groups" class="ui button icon compact red tiny <?php if($kg == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="equal width fields">
		<div class="field">
			<input type="text" value="condition_<?php echo $kc; ?>" name="Connection[<?php echo $type; ?>][<?php echo $n; ?>][events][<?php echo $kc; ?>][name]" data-origin='{"value":"condition_events-N-","name":"Connection[<?php echo $type; ?>][<?php echo $n; ?>][events][events-N-][name]"}'>
		</div>
		<div class="field">
			<button type="button" data-group="events" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Rule'); ?></button>
			<button type="button" data-group="events" class="ui button icon compact red tiny <?php if($kc == 0): ?>hidden<?php endif; ?> delete_clone"><i class="cancel icon"></i></button>
		</div>
	</div>
</div>
<?php endforeach; ?>
<div class="field">
	<button type="button" data-group="events" class="ui button icon compact labeled blue tiny add_clone"><i class="plus icon"></i><?php el('Rule'); ?></button>
</div>
</div>
<div class="ui divider"></div>