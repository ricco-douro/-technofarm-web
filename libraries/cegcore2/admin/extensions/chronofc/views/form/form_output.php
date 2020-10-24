<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$params = [];
	
	if(!empty($view['dynamic']) AND empty($view['parameters'])){
		//$view['parameters'] = '{url:}';
	}
	
	if(strpos($view['parameters'], 'http') === 0 OR strpos($view['parameters'], '{url:') === 0){
		$url = $this->Parser->parse($view['parameters'], true);
	}else{
		$url = $this->Parser->url('_self');
		
		$view['parameters'] = $this->Parser->parse($view['parameters'], true);
		parse_str($view['parameters'], $params);
	}
	
	if(!empty($view['event'])){
		$params['event'] = $this->Parser->parse($view['event'], true);
	}
	
	if(!empty($view['dynamic'])){
		$params['tvout'] = 'view';
	}
	
	$url = \G2\L\Url::build($url, $params);
	
	if(!empty($view['formid'])){
		$form_id = \G2\L\Str::slug($view['formid']);
	}else{
		$form_id = \G2\L\Str::slug($view['name']);
	}
	
	$tag = 'form';
	
	if(empty($view['content'])){
		$view['content'] = $this->Parser->section($view['name'].'/body');
	}
?>
<?php
	ob_start();
?>

	jQuery(document).ready(function($){
		$.G2.forms.invisible();
		
		$('body').on('contentChange.form', 'form', function(e){
			e.stopPropagation();
			$.G2.forms.ready($(this));
		});
		
		$('form').trigger('contentChange.form');
	});


<?php
	$jscode = ob_get_clean();
	\GApp::document()->addCssCode('.ui.form input{box-sizing:border-box;}');
	\GApp::document()->addJsCode($jscode);
	\GApp::document()->_('g2.forms');
	
	if(strpos($view['content'], 'data-inputmask=') !== false){
		\GApp::document()->_('jquery.inputmask');
	}
	
	if(strpos($view['content'], 'data-signature=') !== false){
		\GApp::document()->_('signature_pad');
		\GApp::document()->addJsCode('jQuery(document).ready(function($){$.G2.signature_pad.ready();});');
	}
	
	if(strpos($view['content'], 'data-editor=') !== false){
		\GApp::document()->_('tinymce');
		//\GApp::document()->addJsCode('jQuery(document).ready(function($){$.G2.tinymce.init();});');
	}
	
	$form_class = (!empty($view['class']) ? $view['class'] : 'ui form').' G2-form'.(!empty($view['dynamic']) ? ' G2-dynamic' : '');
	
	if(!empty($view['size'])){
		$form_class .= ' '.$view['size'];
	}
	
	$formtag_attrs = [
		'action' => r2($url),
		'method' => 'post',
		'name' => $form_id,
		'id' => $form_id,
		'data-id' => $form_id,
		'class' => $form_class,
		'data-valloc' => empty($view['validation']['type']) ? 'inline' : $view['validation']['type'],
		'enctype' => 'multipart/form-data',
		'data-dtask' => 'send/self',
		'data-result' => 'replace/self',
	];
	
	if(!empty($view['invisible'])){
		$tag = 'div';
		$formtag_attrs['data-invisible'] = 1;
	}
	
	if(!empty($view['submit_animation'])){
		$formtag_attrs['data-subanimation'] = 1;
	}
	
	$attrs = [];
	if(!empty($view['attrs'])){
		list($extra_formtag_attrs) = $this->Parser->multiline($view['attrs']);
		
		foreach($extra_formtag_attrs as $k => $formtag_attr){
			$attrs[] = $formtag_attr['name'].'="'.$formtag_attr['value'].'"';
		}
	}
	
	foreach($formtag_attrs as $formtag_attr => $formtag_attr_value){
		$attrs[] = $formtag_attr.'="'.$formtag_attr_value.'"';
	}
?>
<?php ob_start(); ?>
<<?php echo $tag.' '.implode(' ', $attrs); ?>>
	<?php
		if(!empty($view['data_provider'])){
			$data = $this->Parser->parse($view['data_provider'], true);
			
			$content = $this->Parser->parse($view['content'], true);
			
			$DataLoader = new \G2\H\DataLoader();
			$content = $DataLoader->load($content, $data);
			unset($DataLoader);
			
			echo $content;
			
		}else{
			$this->Parser->parse($view['content']);
		}
	?>
	<?php if(!empty($view['validation']['type']) AND $view['validation']['type'] != 'inline'): ?>
	<div class="ui message error"></div>
	<?php endif; ?>
</<?php echo $tag; ?>>
<?php 
	$output = ob_get_clean();
	
	if(!empty($view['modal']['enabled'])){
		$views_path = \G2\Globals::ext_path('chronofc', 'admin').'views'.DS.'area_modal'.DS.'area_modal_output.php';
		$view_data = $view['modal'] + ['name' => $view['name'], 'content' => $output];
		echo $this->view($views_path, ['view' => $view_data], true);
	}else{
		echo $output;
	}
?>