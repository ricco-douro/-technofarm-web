<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace G2\L\Wordpress;
/*** FILE_DIRECT_ACCESS_HEADER ***/
defined("GCORE_SITE") or die;
class Document extends \G2\L\Document {
	
	function _($name, $params = array()){
		if($name == 'jquery'){
			wp_enqueue_script('jquery');
			return;
		}
		if($name == 'jquery-migrate'){
			wp_enqueue_script('jquery-migrate');
			return;
		}
		if($name == 'jquery-ui'){
			$jquery_ui = array(
				"jquery-ui-core",			//UI Core - do not remove this one
				"jquery-ui-widget",
				"jquery-ui-mouse",
				"jquery-ui-accordion",
				"jquery-ui-autocomplete",
				"jquery-ui-slider",
				"jquery-ui-tabs",
				"jquery-ui-sortable",	
				"jquery-ui-draggable",
				"jquery-ui-droppable",
				"jquery-ui-selectable",
				"jquery-ui-position",
				"jquery-ui-datepicker",
				"jquery-ui-resizable",
				"jquery-ui-dialog",
				"jquery-ui-button"
			);
			foreach($jquery_ui as $script){
				wp_enqueue_script($script);
			}
			return;
		}
		
		parent::_($name, $params);
	}
	
	function title($title = null){
		if(is_null($title)){
			return wp_title('>', false);
		}else{
			add_filter('wp_title', function() use ($title){return $title;}, 10, 1);
		}
	}
	
	function meta($name, $content = null, $http = false){
		
	}
	
	public function buildHeader(){
		$this->printHeader();
	}
	
}