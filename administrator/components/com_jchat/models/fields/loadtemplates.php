<?php
// namespace administrator\components\com_jfbchat\models\fields;
/**
 *
 * @package FBChat::administrator::components::com_jfbchat::models
 * @subpackage fields
 * @author 2Punti - Marco Biagioni
 * @version $Id: loadtemplates.php 7 02/02/2012 15:01:20Z marco $
 * @copyright (C) 2012 - 2PUNTI SRL
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Templates selector
 *
 * @package FBChat::administrator::components::com_jfbchat::models
 * @subpackage fields
 */
require_once JPATH_SITE . '/libraries/joomla/form/fields/list.php';
class JFormFieldLoadTemplates extends JFormFieldList {
	function getOptions() {
		$options = array ();
		$options [] = JHTML::_ ( 'select.option', 'default.css', '  -Default - ' );
		
		$path = JPATH_SITE . '/components/com_jchat/css/templates';
		$iterator = new DirectoryIterator ( $path );
		foreach ( $iterator as $fileEntity ) {
			$fileName = $fileEntity->getFilename ();
			if (! $fileEntity->isDot () && ! $fileEntity->isDir () && $fileName !== 'index.html' && strpos($fileName, 'default') !== 0) {
				$name = ucfirst ( $fileEntity->getBasename ( '.css' ) );
				$options [] = JHTML::_ ( 'select.option', $fileEntity->getFilename (), $name );
			}
		}
		$options = array_merge ( $options, parent::getOptions () );
		return $options;
	}
}
