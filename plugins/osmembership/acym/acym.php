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

class plgOSMembershipAcym extends JPlugin
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

        $this->canRun = file_exists(JPATH_ROOT . '/components/com_acym/acym.php');
    }

    /**
     * Render setting form
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
        $form = ob_get_clean();

        return array('title' => JText::_('PLG_OSMEMBERSHIP_ACYMAILING_LIST_SETTINGS'),
            'form'  => $form,
        );
    }

    /**
     * Store setting into database, in this case, use params field of plans table
     *
     * @param OSMembershipTablePlan $row
     * @param bool                  $isNew true if create new plan, false if edit
     */
    public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
    {
        if (!$this->canRun)
        {
            return;
        }

        $params = new Registry($row->params);

        $params->set('acymailing_list_ids', implode(',', $data['acymailing_list_ids']));
        $params->set('acymailing_active_remove_list_ids', implode(',', $data['acymailing_active_remove_list_ids']));
        $params->set('subscription_expired_acymailing_list_ids', implode(',', $data['subscription_expired_acymailing_list_ids']));
        $params->set('acymailing_expired_assign_list_ids', implode(',', $data['acymailing_expired_assign_list_ids']));
        $params->set('mailing_list_custom_field', isset($data['mailing_list_custom_field']) ? $data['mailing_list_custom_field'] : 0);
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

        $config = OSMembershipHelper::getConfig();

        // In case subscriber doesn't want to subscribe to newsleter, stop
        if ($config->show_subscribe_newsletter_checkbox && empty($row->subscribe_newsletter))
        {
            return;
        }

        /* @var OSMembershipTablePlan $plan */
        $plan = JTable::getInstance('Osmembership', 'Plan');
        $plan->load($row->plan_id);
        $params = new Registry($plan->params);

        if ($fieldId = (int) $params->get('mailing_list_custom_field'))
        {
            $query = $this->db->getQuery(true);
            $query->select('field_value')
                ->from('#__osmembership_field_value')
                ->where('subscriber_id = ' . $row->id)
                ->where('field_id = ' . $fieldId);
            $this->db->setQuery($query);
            $fieldValue = $this->db->loadResult();

            if ($fieldValue && is_array(json_decode($fieldValue)))
            {
                $listNames = array_map('trim', json_decode($fieldValue));
            }
            elseif (is_string($fieldValue) && strpos($fieldValue, ', ') !== false)
            {
                $listNames = explode(', ', $fieldValue);
            }
            elseif (is_string($fieldValue))
            {
                $listNames = [$fieldValue];
            }
            else
            {
                $listNames = [];
            }

            if (!empty($listNames))
            {
                $listNames = array_map(array($this->db, 'quote'), $listNames);
                $query->clear()
                    ->select('listid')
                    ->from('#__acym_list')
                    ->where('published = 1')
                    ->where('(name = ' . implode(' OR name = ', $listNames) . ')');
                $this->db->setQuery($query);
                $listIds = implode(',', $this->db->loadColumn());
            }
            else
            {
                $listIds = '';
            }
        }
        else
        {
            $listIds = trim($params->get('acymailing_list_ids', ''));
        }

        $removeListIds = trim($params->get('acymailing_active_remove_list_ids'));

        if ($listIds || $removeListIds)
        {
            require_once JPATH_ADMINISTRATOR . '/components/com_acym/helpers/helper.php';

            /* @var acymUserClass $userClass */
            $userClass               = acym_get('class.user');
            $userClass->checkVisitor = false;
            $subId                   = $userClass->getUserIdByEmail($row->email);

            if (!$subId)
            {
                $myUser         = new stdClass();
                $myUser->email  = $row->email;
                $myUser->name   = $row->first_name . ' ' . $row->last_name;
                $myUser->cms_id = $row->user_id;
                $subId          = $userClass->save($myUser); //this
            }

            if($listIds)
            {
                $userClass->subscribe($subId, $listIds);
            }

            if($removeListIds)
            {
                $userClass->unsubscribe($subId, $removeListIds);
            }
        }
    }
    /**
     * Plugin triggered when user update his profile
     *
     * @param OSMembershipTableSubscriber $row The subscription record
     */
    public function onProfileUpdate($row)
    {
        if (!$this->canRun)
        {
            return;
        }

        $query = $this->db->getQuery(true);
        $user  = JFactory::getUser($row->user_id);
        $query->update('#__acym_user')
            ->set('email = ' . $this->db->quote($row->email))
            ->where('email = ' . $this->db->quote($user->email));
        $this->db->setQuery($query);

        try
        {
            $this->db->execute();
        }
        catch (Exception $e)
        {
            // There is another ACYMailing user uses this email, ignore
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

        $config = OSMembershipHelper::getConfig();

        // In case subscriber doesn't want to subscribe to newsleter, stop
        if ($config->show_subscribe_newsletter_checkbox && empty($row->subscribe_newsletter))
        {
            return;
        }

        /* @var OSMembershipTablePlan $plan */
        $plan = JTable::getInstance('Osmembership', 'Plan');
        $plan->load($row->plan_id);
        $params        = new Registry($plan->params);
        $listIds       = trim($params->get('subscription_expired_acymailing_list_ids', ''));
        $assignListIds = trim($params->get('acymailing_expired_assign_list_ids', ''));

        if ($row->user_id)
        {
            $activePlans = OSMembershipHelper::getActiveMembershipPlans($row->user_id, array($row->id));

            // He renewed his subscription before, so don't remove him from the lists
            if (in_array($row->plan_id, $activePlans))
            {
                return;
            }
        }

        if ($listIds != '' || $assignListIds != '')
        {
            require_once JPATH_ADMINISTRATOR . '/components/com_acym/helpers/helper.php';

            /* @var acymuserClass $userClass */
            $userClass               = acym_get('class.user');
            $userClass->checkVisitor = false;

            $subId = $userClass->getUserIdByEmail($row->email);

            if (!$subId && $assignListIds)
            {
                // Create new subscriber as it is needed to assign user to the lists
                $myUser         = new stdClass();
                $myUser->email  = $row->email;
                $myUser->name   = $row->first_name . ' ' . $row->last_name;
                $myUser->cms_id = $row->user_id;
                $subId          = $userClass->save($myUser); //this
            }

            if ($subId)
            {
                if($listIds)
                {
                    $userClass->unsubscribe($subId, $listIds);
                }

                if($assignListIds)
                {
                    $userClass->subscribe($subId, $assignListIds);
                }
            }
        }
    }

    /**
     * Display form allows users to change settings on subscription plan add/edit screen
     *
     * @param OSMembershipTablePlan $row
     */
    private function drawSettingForm($row)
    {
        require_once JPATH_ADMINISTRATOR . '/components/com_acym/helpers/helper.php';

        $params               = new Registry($row->params);
        $activeAssignListIds  = explode(',', $params->get('acymailing_list_ids', ''));
        $activeRemoveListIds  = explode(',', $params->get('acymailing_active_remove_list_ids', ''));
        $expiredRemoveListIds = explode(',', $params->get('subscription_expired_acymailing_list_ids', ''));
        $expiredAssignListIds = explode(',', $params->get('acymailing_expired_assign_list_ids', ''));

        /* @var acymlistClass $listClass */
        $listClass            = acym_get('class.list');
        $allLists             = $listClass->getAllWithIdName();

        $query = $this->db->getQuery(true);
        $query->select('id, name')
            ->from('#__osmembership_fields')
            ->where('published = 1')
            ->where('fieldtype = "Checkboxes"')
            ->order('name');
        $this->db->setQuery($query);
        $mailingListFields = $this->db->loadObjectList();
        ?>
        <div class="row-fluid">
            <div class="span6 pull-left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('OSM_WHEN_SUBSCRIPTION_ACTIVE'); ?></legend>
                    <table class="admintable adminform" style="width: 90%;">
                        <tr>
                            <td width="220" class="key">
                                <?php echo OSMembershipHelperHtml::getFieldLabel('acymailing_list_ids', JText::_('OSM_ASSIGN_TO_MAILING_LISTS'), JText::_('OSM_ASSIGN_TO_MAILING_LISTS_EXPLAIN')); ?>
                            </td>
                            <td>
                                <?php echo JHtml::_('select.genericlist', $allLists, 'acymailing_list_ids[]', 'class="inputbox" multiple="multiple" size="10"', 'listid', 'name', $activeAssignListIds) ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="220" class="key">
                                <?php echo OSMembershipHelperHtml::getFieldLabel('acymailing_active_remove_list_ids', JText::_('OSM_REMOVE_FROM_MAILING_LISTS'), JText::_('OSM_REMOVE_FROM_MAILING_LISTS_EXPLAIN')); ?>
                            </td>
                            <td>
                                <?php
                                echo JHtml::_('select.genericlist', $allLists, 'acymailing_active_remove_list_ids[]', 'class="inputbox" multiple="multiple" size="10"', 'listid', 'name', $activeRemoveListIds);
                                ?>
                            </td>
                        </tr>
                        <?php
                        if (count($mailingListFields))
                        {
                            $options   = array();
                            $options[] = JHtml::_('select.option', '', 'Select Field', 'id', 'name');
                            $options   = array_merge($options, $mailingListFields);
                        ?>
                            <tr>
                                <td width="220" class="key">
                                    <?php echo OSMembershipHelperHtml::getFieldLabel('mailing_list_custom_field', JText::_('Mailing Lists Custom Field'), JText::_('If you select a custom field here, subscribers will be assigned to the mailist list which he choose on the options of this custom field instead of the lists you select for this plan')); ?>
                                </td>
                                <td>
                                    <?php echo JHtml::_('select.genericlist', $options, 'mailing_list_custom_field', '', 'id', 'name', (int) $params->get('mailing_list_custom_field')); ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </fieldset>
            </div>
            <div class="span6 pull-left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('OSM_WHEN_SUBSCRIPTION_EXPIRED'); ?></legend>
                    <table class="admintable adminform" style="width: 90%;">
                        <tr>
                            <td width="220" class="key">
                                <?php echo OSMembershipHelperHtml::getFieldLabel('subscription_expired_acymailing_list_ids', JText::_('OSM_REMOVE_FROM_MAILING_LISTS'), JText::_('OSM_REMOVE_FROM_MAILING_LISTS_EXPLAIN')); ?>
                            </td>
                            <td>
                                <?php echo JHtml::_('select.genericlist', $allLists, 'subscription_expired_acymailing_list_ids[]', 'class="inputbox" multiple="multiple" size="10"', 'listid', 'name', $expiredRemoveListIds) ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="220" class="key">
                                <?php echo OSMembershipHelperHtml::getFieldLabel('acymailing_expired_assign_list_ids', JText::_('OSM_ASSIGN_TO_MAILING_LISTS'), JText::_('OSM_ASSIGN_TO_MAILING_LISTS_EXPLAIN')); ?>
                            </td>
                            <td>
                                <?php echo JHtml::_('select.genericlist', $allLists, 'acymailing_expired_assign_list_ids[]', 'class="inputbox" multiple="multiple" size="10"', 'listid', 'name', $expiredAssignListIds); ?>
                            </td>
                        </tr>
                    </table>
            </div>
        </div>
        <?php
    }
}
