<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(!empty($view['data_provider'])){
		$items = $this->Parser->parse($view['data_provider'], true);
		if(!is_array($items)){
			$items = [];
		}
	}else{
		$items = [0];
	}
	
	echo '<div class="'.$view['class'].' partitioned" data-sequential="'.$view['sequential'].'">';
	if(!empty($view['sections'])){
		list($sections) = $this->Parser->multiline($view['sections'], true, false);
		
		$disabled_class = !empty($view['sequential']) ? 'disabled' : '';
		
		$classes = [];
		$view['attached'] = isset($view['attached']) ? $view['attached'] : 'top attached';
		$classes[] = !empty($view['attached']) ? $view['attached'] : 'fluid';
		$classes[] = !empty($view['size']) ? $view['size'] : '';
		$classes[] = !empty($view['color']) ? $view['color'] : '';
		
		$view['style'] = ($view['style'] != 'tabs') ? $view['style'] : 'tabular menu';
		$classes[] = !empty($view['style']) ? $view['style'] : '';
		
		$class = implode(' ', array_filter($classes));
		
		$item_class = 'item';
		if(strpos($view['style'], 'step') !== false){
			$item_class = 'step';
		}
		
		if($view['style'] == 'sequence'){
			
		}else{
			if(strpos($view['style'], 'vertical') !== false){
				echo '<div class="ui grid">';
				echo '<div class="four wide column">';
			}
			
			echo '<div class="ui '.$class.' G2-tabs">';
			foreach($items as $key => $item){
				$this->set($view['name'].'.key', $key);
				$this->set($view['name'].'.row', $item);
				
				foreach($sections as $k => $section){
					$header = (!empty($section['value']) ? $this->Parser->parse($section['value'], true) : $section['name']);
				
					echo '<a class="'.$item_class.' '.(($k == 0 AND $key == 0) ? 'active' : $disabled_class).'" data-tab="tabs-'.$view['name'].'-'.$section['name'].'-'.$key.'">'.$header.'</a>';
				}
			}
			echo '</div>';
			
			if(strpos($view['style'], 'vertical') !== false){
				echo '</div>';
			}
		}
		
		$attached_class = (!empty($view['attached']) AND ($view['style'] != 'sequence') AND strpos($view['style'], 'vertical') === false) ? 'bottom attached' : '';
		
		if(strpos($view['style'], 'vertical') !== false){
			echo '<div class="twelve wide stretched column">';
		}
		foreach($items as $key => $item){
			$this->set($view['name'].'.key', $key);
			$this->set($view['name'].'.row', $item);
			foreach($sections as $k => $section){
				//echo '<div class="column '.$section['name'].(!empty($section['value']) ? ' '.$section['value'] : '').'">';
				echo '<div class="ui tab segment '.$attached_class.' '.(($k == 0 AND $key == 0) ? 'active' : '').'" data-tab="tabs-'.$view['name'].'-'.$section['name'].'-'.$key.'">';
				echo $this->Parser->section($view['name'].'/'.$section['name']);
				echo '</div>';
			}
		}
		if(strpos($view['style'], 'vertical') !== false){
			echo '</div>';
			echo '</div>';
		}
		
		echo $this->Parser->section($view['name'].'/footer');
		
		echo '<div class="ui divider hidden"></div>';
	}
	echo '</div>';