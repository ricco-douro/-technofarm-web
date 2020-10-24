<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$classes = [];
	$classes[] = $view['class'];
	$classes[] = !empty($view['columns']) ? $view['columns'] : '';
	$classes[] = !empty($view['stackable']) ? $view['stackable'] : '';
	$classes[] = !empty($view['padded']) ? $view['padded'] : '';
	$classes[] = !empty($view['celled']) ? $view['celled'] : '';
	$classes[] = !empty($view['divided']) ? $view['divided'] : '';
	$grid_class = implode(' ', array_filter($classes));
	
	echo '<div class="'.$grid_class.'">';
	if(!empty($view['sections']) AND is_string($view['sections'])){
		list($sections2) = $this->Parser->multiline($view['sections']);
		foreach($sections2 as $section){
			$sections[] = ['name' => $section['name'], 'class' => !empty($section['value']) ? $section['value'] : ''];
		}
		
		echo '<div class="row">';
		foreach($sections as $section){
			
			echo '<div class="column '.$section['name'].' '.$section['class'].'">';
			echo $this->Parser->section($view['name'].'/'.$section['name']);
			echo '</div>';
		}
		echo '</div>';
	}else if(!empty($view['sections']) AND is_array($view['sections'])){
		
		foreach($view['rows'] as $rk => $row){
			$classes = [];
			$classes[] = $row['column_count'];
			$classes[] = $row['class'];
			$classes[] = $row['stretched'];
			$classes[] = $row['centered'];
			$classes[] = $row['valign'];
			$row_class = implode(' ', array_filter($classes));
			
			echo '<div class="row '.$row_class.'">';
			if(!empty($row['columns'])){
				foreach($row['columns'] as $ck => $column){
					$section_name = $view['sections'][$rk.'_'.$ck]['name'];
					$classes = [];
					$classes[] = $section_name;
					$classes[] = $column['width'];
					$classes[] = $column['class'];
					$classes[] = $column['floating'];
					$classes[] = $column['halign'];
					$column_class = implode(' ', array_filter($classes));
					
					echo '<div class="column '.$column_class.'">';
					echo $this->Parser->section($view['name'].'/'.$section_name);
					echo '</div>';
				}
			}
			echo '</div>';
		}
	}
	
	echo '</div>';