<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
$JSecureCommon 
= array('publish'						  => JText::_('ENABLE'),
			'enableMasterPassword' => JText::_('MASTER_PASSWORD_ENABLED'), 
			'master_password'		  => JText::_('MASTER_PASSWORD'),
			'passkeytype'				  => JText::_('PASS_KEY'), 
			'key'							  => JText::_('KEY'), 
			'options'						  => JText::_('REDIRECT_OPTIONS'), 
			'custom_path'				  => JText::_('CUSTOM_PATH'), 
			);


$enableMasterPassword = array('0' => JText::_('NO') , '1'	=> JText::_('YES') );

$passkeytype =  array(
		   '0'	=> JText::_('URL'),
		   '1'	=> JText::_('FORM')
);

$options = array(
		   '0'	=> JText::_('REDIRECT_INDEX'),
		   '1'	=> JText::_('CUSTOM_PATH')
);

?>