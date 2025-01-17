<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

class os_eway extends MPFPaymentOmnipay
{
	protected $omnipayPackage = 'Eway_Direct';

	/**
	 * Constructor
	 *
	 * @param JRegistry $params
	 * @param array     $config
	 */
	public function __construct($params, $config = array('type' => 1))
	{
		$config['params_map'] = array(
			'customerId' => 'eway_customer_id',
			'testMode'   => 'eway_mode',
		);

		parent::__construct($params, $config);
	}
}
