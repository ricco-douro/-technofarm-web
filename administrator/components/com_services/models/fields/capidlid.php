<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.application.plugin.helper');

/**
 * Class JFormFieldCapidlid
 *
 * @since 1.3.5
 */
class JFormFieldCapidlid extends JFormField
{
	/**
	 * @return string
	 * @since 1.3.5
	 */
	public function getInput()
	{
		// Initialize some field attributes.
		$type = $accept = !empty($this->type) ? ' type="' . $this->type . '"' : '';
		// $accept = !empty($this->accept) ? ' accept="' . $this->accept . '"' : '';
		$size = !empty($this->size) ? ' size="' . $this->size . '"' : '';
		// $class = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$name = !empty($this->name) ? ' name="' . $this->name . '"' : '';
		$value = !empty($this->value) ? ' value="' . $this->value . '"' : '';
		$disabled = $this->disabled ? ' disabled' : '';
		$required = $this->required ? ' required' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';

		$html = array();

		$html[] = '<input id="' . $this->id . '"'. strtolower($type) .strtolower($name) . $disabled . $required . $autofocus . $size . $value . ' validate="capidlid"/>';

		$this->getDlidJs();

		return implode($html);
	}

	public function getValue() {

	}

	/**
	 * @since 1.3.5
	 */
	protected function getDlidJs(){

		$js = null;

		if($this->getInactiveCapiPlugins() !== false){
			$inactiveCapiPlugins = $this->getInactiveCapiPlugins();
			$js = "
            jQuery(document).ready(function(){
                    alert('$inactiveCapiPlugins');
            });
            ";
		}

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);

	}

	/**
	 * @return string
	 * @since 1.3.5
	 */
	protected function getInactiveCapiPlugins(){
		$disabledPlugins = array();
		$servicesPlugins = array(
			'joomla'                =>  'cAPI - Services Joomla Plugin\n',
			'slim'                  =>  'cAPI - Services Slim Configuration Plugin\n',
			'rest'                  =>  'cAPI - Services REST Plugin\n',
			'slimjsonapiview'       =>  'cAPI - Slim JSON API View Plugin\n',
			'slimjsonapimiddleware' =>  'cAPI - Slim JSON API Middleware Plugin\n'
		);

		$i = 0;
		foreach ($servicesPlugins as $k => $v) {
			if(!JPluginHelper::isEnabled('services', $k)){
				$disabledPlugins[$i] = $v;
				$i++;
			}
		}

		if($i >0) {
			$disabledPlugins = implode('', $disabledPlugins);
			$disabledPluginsString = "***IMPORTANT***" . '\n\n' . "The following plugins still need to be enabled:" . '\n\n' . $disabledPlugins;
			return $disabledPluginsString;
		}
		return false;
	}
}