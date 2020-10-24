<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$items = $this->Parser->parse($view['data_provider'], true);
	if(!is_array($items)){
		$items = [];
	}
	
	$columns = [];
	$headers = [];
	if(!empty($view['sections'])){
		
		list($columns_data) = $this->Parser->multiline($view['sections'], true, false);
		
		if(is_array($columns_data)){
			foreach($columns_data as $columns_line){
				$columns[] = $column_name = $columns_line['name'];
				
				$headers[$column_name] = '';
				if(!empty($columns_line['value'])){
					$headers[$column_name] = $this->Parser->parse($columns_line['value'], true);
				}
			}
		}
		
		$columns_classes = [];
		if(!empty($view['classes'])){
			$columns_classes_data = explode("\n", $view['classes']);
			$columns_classes_data = array_map('trim', $columns_classes_data);
			
			foreach($columns_classes_data as $columns_classes_line){
				$columns_classes_line_data = explode(':', $columns_classes_line, 2);
				$columns_classes[array_shift($columns_classes_line_data)] = array_shift($columns_classes_line_data);
			}
		}
		
		$columns_views = [];
		foreach($columns as $column){
			$columns_views[$column] = $this->Parser->section($view['name'].'/'.$column);
		}
	}
	
	$form_id = \G2\L\Str::slug($view['name']);
?>

	<table class="<?php if(isset($view['class'])): ?><?php echo $view['class']; ?><?php else: ?>ui selectable table<?php endif; ?>">
		<thead>
			<tr>
				<?php foreach($headers as $column_name => $header): ?>
					<?php
						if(!empty($columns_classes[$column_name])){
							$header_class = $columns_classes[$column_name];
						}else{
							$header_class = 'collapsing';
						}
					?>
					<th class="<?php echo $header_class; ?>"><?php echo $header; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($items as $key => $item): ?>
			<?php $this->set($view['name'].'.key', $key); ?>
			<?php $this->set($view['name'].'.row', $item); ?>
			<tr>
				<?php foreach($columns as $column): ?>
					<?php $this->set($view['name'].'.value', \G2\L\Arr::getVal($item, $column, '')); ?>
					<?php
						if(!empty($columns_classes[$column])){
							$cell_class = $columns_classes[$column];
						}else{
							$cell_class = 'collapsing';
						}
					?>
					<?php $column_view = $this->Parser->section($view['name'].'/'.$column); ?>
					<?php if(!empty($column_view)): ?>
						<td class="<?php echo $cell_class; ?>">
							<?php echo $column_view; ?>
						</td>
					<?php else: ?>
						<td class="<?php echo $cell_class; ?>"><?php echo \G2\L\Arr::getVal($item, $column, ''); ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
		
	</table>
