<?php
/**
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
// define some useful constants
define('THUMBLIB_BASE_PATH', dirname(__FILE__));
define('THUMBLIB_PLUGIN_PATH', THUMBLIB_BASE_PATH . '/thumb_plugins/');
define('DEFAULT_THUMBLIB_IMPLEMENTATION', 'gd');

/**
 * JChatThumbFactory Object
 * 
 * This class is responsible for making sure everything is set up and initialized properly,
 * and returning the appropriate thumbnail class instance.  It is the only recommended way 
 * of using this library, and if you try and circumvent it, the sky will fall on your head :)
 * 
 * Basic use is easy enough.  First, make sure all the settings meet your needs and environment...
 * these are the static variables defined at the beginning of the class.
 * 
 * Once that's all set, usage is pretty easy.  You can simply do something like:
 * <code>$thumb = JChatThumbFactory::create('/path/to/file.png');</code>
 * 
 * Refer to the documentation for the create function for more information
 * 
 * @package JChatThumbMain
 * @subpackage Core
 */
class JChatThumbFactory
{
	/**
	 * Which implemenation of the class should be used by default
	 * 
	 * Currently, valid options are:
	 *  - imagick
	 *  - gd
	 *  
	 * These are defined in the implementation map variable, inside the create function
	 * 
	 * @var string
	 */
	public static $defaultImplemenation = DEFAULT_THUMBLIB_IMPLEMENTATION;
	/**
	 * Where the plugins can be loaded from
	 * 
	 * Note, it's important that this path is properly defined.  It is very likely that you'll 
	 * have to change this, as the assumption here is based on a relative path.
	 * 
	 * @var string
	 */
	public static $pluginPath = THUMBLIB_PLUGIN_PATH;
	
	/**
	 * Factory Function
	 * 
	 * This function returns the correct thumbnail object, augmented with any appropriate plugins.  
	 * It does so by doing the following:
	 *  - Getting an instance of JChatThumbMain
	 *  - Loading plugins
	 *  - Validating the default implemenation
	 *  - Returning the desired default implementation if possible
	 *  - Returning the GD implemenation if the default isn't available
	 *  - Throwing an exception if no required libraries are present
	 * 
	 * @return JChatThumbGd
	 * @uses JChatThumbMain
	 * @param string $filename The path and file to load [optional]
	 */
	public static function create ($filename = null, $options = array(), $isDataStream = false)
	{
		// map our implementation to their class names
		$implementationMap = array
		(
			'imagick'	=> 'ImagickThumb',
			'gd' 		=> 'JChatThumbGd'
		);
		
		// grab an instance of JChatThumbMain
		$pt = JChatThumbMain::getInstance(); 
		
		$toReturn = null;
		$implementation = self::$defaultImplemenation;
		
		// attempt to load the default implementation
		if ($pt->isValidImplementation(self::$defaultImplemenation))
		{
			$imp = $implementationMap[self::$defaultImplemenation];
			$toReturn = new $imp($filename, $options, $isDataStream);
		}
		// load the gd implementation if default failed
		else if ($pt->isValidImplementation('gd'))
		{
			$imp = $implementationMap['gd'];
			$implementation = 'gd';
			$toReturn = new $imp($filename, $options, $isDataStream);
		}
		// throw an exception if we can't load
		else
		{
			throw new Exception('You must have either the GD or iMagick extension loaded to use this library');
		}
	 
		return $toReturn;
	}
}