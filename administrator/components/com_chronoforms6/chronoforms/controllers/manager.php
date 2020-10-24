<?php
/**
* COMPONENT FILE HEADER
**/
namespace G2\A\E\Chronoforms\C;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Manager extends \G2\L\Controller {
	var $models = array(
		'\G2\A\E\Chronoforms\M\Connection',
		'\G2\A\E\Chronoforms\M\Block',
	);
	var $helpers= array(
		'\G2\A\E\Chronofc\H\Parser',
		'\G2\A\E\Chronofc\H\Field',
		'\G2\H\Paginator',
		'\G2\H\Sorter',
	);
	
	function _initialize(){
		//$this->layout('default');
		$this->fparams = \GApp::extension('chronoforms')->settings();
	}
	
	function index(){
		$conn = $this->get('chronoform', $this->data('chronoform'));
		
		if(empty($conn)){
			return ['error' => rl('Error, form does not exist.')];
		}
		
		$connection = $this->Connection->where('alias', $conn, '= BINARY')->where('published', 1)->order(['id' => 'desc'])->select('first', ['json' => ['events', 'sections', 'views', 'functions', 'locales', 'rules', 'params']]);
		
		if(empty($connection)){
			return ['error' => rl('Error, form does not exist or is not published.')];
		}
		
		if(empty($connection['Connection']['public']) AND $this->site == 'front'){
			return ['error' => rl('Error, form is not available for frontend users.')];
		}
		
		if(!empty($connection['Connection']['rules']['access'])){
			$rules = array_filter($connection['Connection']['rules']['access']);
			if(!empty($rules) AND \GApp::access($connection['Connection']['rules'], 'access') !== true){
				return ['error' => rl('You do not have enough permissions to access this resource.')];
			}
		}
		
		$this->set('__connection', $connection['Connection']);
		
		//find if blocks are needed
		$view_blocks = \G2\L\Arr::getVal($connection, 'Connection.views.[n].type', []);
		$fn_blocks = \G2\L\Arr::getVal($connection, 'Connection.functions.[n].type', []);
		$blocks_ids = [];
		foreach($view_blocks as $k => $view_block){
			if($view_block == 'stored_block'){
				$blocks_ids[] = $connection['Connection']['views'][$k]['id'];
			}
		}
		
		foreach($fn_blocks as $k => $fn_block){
			if($fn_block == 'stored_block'){
				$blocks_ids[] = $connection['Connection']['functions'][$k]['id'];
			}
		}
		
		$blocks_ids = array_unique($blocks_ids);
		
		$blocks = [];
		if(!empty($blocks_ids)){
			$blocks = $this->Block->where('id', $blocks_ids, 'in')->select('all', ['json' => ['content']]);
		}
		
		$this->set('__blocks', $blocks);
		
		//get events
		$event = $this->get('event', $this->data('event'));
		
		if(empty($event)){
			if(!empty($connection['Connection']['params']['default_event'])){
				$event = $connection['Connection']['params']['default_event'];
			}else{
				$event = 'load';
			}
		}
		
		$this->set('__event', $event);
		
		if(\GApp::instance()->site == 'front'){
			$good = (\GApp::extension('chronoforms')->valid() OR !isset($connection['Connection']['params']['limited_edition']));
			if(!$good){
				$this->set('__viewslimit', 15);
			}
		}
		
		$this->view = [
			'views' => [
				'site' => 'admin',
				'cont' => 'manager',
				'act' => 'event',
			]
		];
	}
	
	function _finalize(){
		if(empty($this->tvout) AND \GApp::extension('chronoforms')->valid() == false){
			if(\G2\Globals::get('app') != 'wordpress'){
				echo '<a href="http://www.chronoengine.com/" target="_blank" class="chronoforms6_credits">Form by ChronoForms - ChronoEngine.com</a>';
			}else{
				echo '<h3>Form by ChronoForms - ChronoEngine.com</h3>';
			}
		}
	}
}
?>