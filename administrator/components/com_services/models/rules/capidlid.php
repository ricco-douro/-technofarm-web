<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */

/**
 * Class JFormRuleCapidlid
 *
 * @since 1.3.5
 */
class JFormRuleCapidlid extends JFormRule {

	/**
	 * @param SimpleXMLElement $element
	 * @param mixed            $value
	 * @param null             $group
	 * @param JRegistry|null   $input
	 * @param JForm|null       $form
	 *
	 * @return bool
	 *
	 * @since 1.3.5
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		if (ctype_alnum($value) || $value === '' || $value === null) {
			/**
			 * Save $value to extension update extra_query field as download ID
			 */
			$this->setDlid($value);
			return true;
		}

		$element->attributes()->message = 'The value ' . $value . ' is not valid because it is not alphanumeric';
		// The above line works if you have already specified a custom message by adding the message="..." attribute
		// to your form field. If you haven't then use instead

		return false;
	}

	/**
	 * @param $dlid
	 * @return mixed
	 * @since 1.3.5
	 */
	protected function setDlid($dlid)
	{
		$updateSiteIds = array();
		$dlid = 'dlid='.$dlid;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('update_site_id');
		$query->from($db->quoteName('#__update_sites'));
		$query->where($db->quoteName('location')." like ".$db->quote('%www.annatech.com%'));

		$db->setQuery($query);
		$result = $db->loadColumn();

		foreach ($result as $a) {
			$updateSiteIds['update_site_id'] = $a;
		}

		$query->update('#__update_sites');
		$query->set("extra_query='$dlid'");
		$query->where($updateSiteIds);
		$db->setQuery($query);

		return $db->execute();
	}
}