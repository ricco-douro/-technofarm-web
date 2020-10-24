<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

class plgOSMembershipVMShopperGroups extends JPlugin
{
    /**
     * Database object.
     *
     * @var    JDatabaseDriver
     */
    protected $db;

    /**
	 * Whether the plugin should be run when events are triggered
	 *
	 * @var bool
	 */
	protected $canRun;

	/**
	 * Constructor
	 *
	 * @param   object &$subject The object to observe
	 * @param   array  $config   An optional associative array of configuration settings.
	 */
	public function __construct($subject, array $config = array())
	{
		parent::__construct($subject, $config);

		$this->canRun = file_exists(JPATH_ROOT . '/components/com_virtuemart/virtuemart.php');
	}

    /**
     * Render settings from
     *
     * @param OSMembershipTablePlan $row
     *
     * @return array
     */
    public function onEditSubscriptionPlan($row)
    {
        if (!$this->canRun)
        {
            return;
        }

        ob_start();
        $this->drawSettingForm($row);
        $form = ob_get_contents();
        ob_end_clean();

        return array('title' => JText::_('OSM_VM_SHOPPER_GROUPS_SETTINGS'),
            'form'  => $form,
        );
    }

    /**
     * Store settings into database
     *
     * @param OSMembershipTablePlan $row
     * @param Boolean               $isNew true if create new plan, false if edit
     */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
	    if(!$this->canRun)
        {
            return;
        }

        $params = new Registry($row->params);

		if (isset($data['vm_shopper_group_ids']))
		{
			$vmShopperGroupIds = implode(',', $data['vm_shopper_group_ids']);
		}
		else
		{
			$vmShopperGroupIds = '';
		}

		if (isset($data['vm_expired_shopper_group_ids']))
		{
			$vmExpiredShopperGroupIds = implode(',', $data['vm_expired_shopper_group_ids']);
		}
		else
		{
			$vmExpiredShopperGroupIds = '';
		}

		$params->set('vm_shopper_group_ids', $vmShopperGroupIds);
		$params->set('vm_expired_shopper_group_ids', $vmExpiredShopperGroupIds);
		$row->params = $params->toString();

		$row->store();
	}

    /**
     * Run when a membership activated
     *
     * @param OSMembershipTableSubscriber $row
     */
	public function onMembershipActive($row)
	{
	    if (!$this->canRun)
        {
            return;
        }

        if (!$row->user_id)
		{
			return;
		}

		$plan = JTable::getInstance('Osmembership', 'Plan');
		$plan->load($row->plan_id);
		$params = new Registry($plan->params);
		$groups = explode(',', $params->get('vm_shopper_group_ids'));
		$groups = array_filter(ArrayHelper::toInteger($groups));

		if (empty($groups))
        {
            return;
        }

		// Get all the shopper groups which the subscriber was assigned
		$query = $this->db->getQuery(true)
			->select('virtuemart_shoppergroup_id')
			->from('#__virtuemart_vmuser_shoppergroups')
			->where('virtuemart_user_id = ' . $row->user_id);
		$this->db->setQuery($query);
		$groupIds = $this->db->loadColumn();

		foreach ($groups AS $group)
		{
			if (in_array($group, $groupIds))
			{
				continue;
			}

			$query->clear()
				->insert('#__virtuemart_vmuser_shoppergroups')
				->columns('virtuemart_user_id, virtuemart_shoppergroup_id')
				->values(implode(',', $this->db->quote([$row->user_id, $group])));
			$this->db->setQuery($query)
				->execute();
		}
	}

	/**
	 * Run when a membership expiried die
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipExpire($row)
	{
	    if (!$this->canRun)
        {
            return;
        }

        if (!$row->user_id)
		{
			return;
		}

		$activePlans = OSMembershipHelper::getActiveMembershipPlans($row->user_id, array($row->id));

		// He renewed his subscription before, so don't remove him from the groups
		if (in_array($row->plan_id, $activePlans))
		{
			return;
		}

		$plan = JTable::getInstance('Osmembership', 'Plan');
		$plan->load($row->plan_id);
		$params         = new Registry($plan->params);
		$removeGroupIds = explode(',', $params->get('vm_expired_shopper_group_ids'));
		$removeGroupIds = array_filter(ArrayHelper::toInteger($removeGroupIds));

		if (empty($removeGroupIds))
		{
			return;
		}

		// If user
		$query = $this->db->getQuery(true);
		$query->select('params')
			->from('#__osmembership_plans')
			->where('id IN  (' . implode(',', $activePlans) . ')');
		$this->db->setQuery($query);
		$rowPlans = $this->db->loadObjectList();

		foreach ($rowPlans as $rowPlan)
		{
			$planParams     = new Registry($rowPlan->params);
			$planGroups     = explode(',', $planParams->get('vm_shopper_group_ids'));
			$planGroups     = array_filter(ArrayHelper::toInteger($planGroups));
			$removeGroupIds = array_diff($removeGroupIds, $planGroups);
		}

		foreach ($removeGroupIds AS $removeGroupId)
		{
			$query->clear()->delete('#__virtuemart_vmuser_shoppergroups')
				->where('virtuemart_shoppergroup_id =' . $removeGroupId)
				->where('virtuemart_user_id =' . $row->user_id);
			$this->db->setQuery($query)
				->execute();
		}
	}

    /**
     * Display form allows users to change setting for this subscription plan
     *
     * @param OSMembershipTablePlan $row
     */
    private function drawSettingForm($row)
    {
        JFactory::getLanguage()->load('com_virtuemart_shoppers', JPATH_ROOT.'/components/com_virtuemart');
        $params                            = new Registry($row->params);
        $vmShopperGroupIds        = explode(',', $params->get('vm_shopper_group_ids', ''));
        $vmExpiredShopperGroupIds = explode(',', $params->get('vm_expired_shopper_group_ids', ''));

        $query            = $this->db->getQuery(true);
        $query->select('virtuemart_shoppergroup_id, shopper_group_name')->from('#__virtuemart_shoppergroups');
        $this->db->setQuery($query);
        $rows = $this->db->loadObjectList();

        $options =[];
        foreach ($rows as $row)
        {
            $options[] = JHtml::_('select.option', $row->virtuemart_shoppergroup_id, JText::_($row->shopper_group_name));
        }
        ?>
        <div class="row-fluid">
            <div class="span6 pull-left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('OSM_WHEN_SUBSCRIPTION_ACTIVE');?></legend>
                    <table class="admintable adminform" style="width: 90%;">
                        <tr>
                            <td width="220" class="key">
                                <?php echo JText::_('OSM_ASSIGN_TO_VM_SHOPPER_GROUPS'); ?>
                            </td>
                            <td>
                                <?php echo JHtml::_('select.genericlist', $options, 'vm_shopper_group_ids[]',' multiple="multiple" size="6" ','value','text', $vmShopperGroupIds); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="span6 pull-left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('OSM_WHEN_SUBSCRIPTION_EXPIRED');?></legend>
                    <table class="admintable adminform" style="width: 90%;">
                        <tr>
                            <td width="220" class="key">
                                <?php echo JText::_('OSM_REMOVE_FROM_VM_SHOPPER_GROUPS'); ?>
                            </td>
                            <td>
                                <?php echo JHtml::_('select.genericlist', $options,'vm_expired_shopper_group_ids[]',' multiple="multiple" size="6" ','value','text', $vmExpiredShopperGroupIds); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
        </div>
        <?php
    }
}