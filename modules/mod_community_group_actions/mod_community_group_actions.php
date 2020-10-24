<?php
	defined( '_JEXEC' ) or die( 'Unauthorized Access' );

	// Check if JomSocial core file exists
	$corefile 	= JPATH_ROOT . '/components/com_community/libraries/core.php';

	jimport( 'joomla.filesystem.file' );
	if( !JFile::exists( $corefile ) )
	{
		return;
	}

	// Include JomSocial's Core file, helpers, settings...
	require_once( $corefile );
	require_once dirname(__FILE__) . '/helper.php';

	// Add proper stylesheet
    JFactory::getLanguage()->isRTL() ? CTemplate::addStylesheet('style.rtl') : CTemplate::addStylesheet('style');

	$config = CFactory::getConfig();

   	$user = CFactory::getUser();

    require(JModuleHelper::getLayoutPath('mod_community_group_actions', $params->get('layout', 'default')));
