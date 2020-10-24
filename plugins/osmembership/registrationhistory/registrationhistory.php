<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

class plgOSMembershipRegistrationhistory extends JPlugin
{
	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 */
	protected $db;

	/**
	 * Flag determine whether the plugin should be run when events are triggered
	 *
	 * @var bool
	 */
	protected $canRun;

	/**
	 * Plugin constructor.
	 *
	 * @param object $subject
	 * @param array  $config
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->canRun = file_exists(JPATH_ROOT . '/components/com_eventbooking/eventbooking.php');
	}

	/**
	 * Render setting form
	 *
	 * @param JTable $row
	 *
	 * @return array
	 */
	public function onProfileDisplay($row)
	{
		if (!$this->canRun)
		{
			return;
		}

		ob_start();
		$this->drawRegistrationHistory();

		return array('title' => JText::_('EB_REGISTRATION_HISTORY'),
		             'form'  => ob_get_clean(),
		);
	}

	/**
	 * Display registration history of the current logged in user
	 *
	 * @param object $row
	 */
	private function drawRegistrationHistory()
	{
		// Require libraries
		require_once JPATH_ADMINISTRATOR . '/components/com_eventbooking/libraries/rad/bootstrap.php';

		EventbookingHelper::loadLanguage();

		JLoader::register('EventbookingModelHistory', JPATH_ROOT . '/components/com_eventbooking/model/history.php');

		/* @var EventbookingModelHistory $model */
		$model = RADModel::getInstance('History', 'EventbookingModel', [
			'table_prefix'    => '#__eb_',
			'remember_states' => false,
			'ignore_request'  => true,
		]);

		$items = $model->setState('limitstart', 0)
			->setState('limit', 0)
			->getData();

		if (empty($items))
		{
			return;
		}

		$config = EventbookingHelper::getConfig();

		$showDownloadCertificate = false;
		$showDownloadTicket      = false;
		$showDueAmountColumn     = false;

		$numberPaymentMethods = EventbookingHelper::getNumberNoneOfflinePaymentMethods();

		if ($numberPaymentMethods > 0)
		{
			foreach ($items as $item)
			{
				if ($item->payment_status != 1)
				{
					$showDueAmountColumn = true;
					break;
				}
			}
		}

		foreach ($items as $item)
		{
			$item->show_download_certificate = false;

			if ($item->published == 1 && $item->activate_certificate_feature == 1
				&& $item->event_end_date_minutes >= 0
				&& (!$config->download_certificate_if_checked_in || $item->checked_in)
			)
			{
				$showDownloadCertificate         = true;
				$item->show_download_certificate = true;
			}

			if ($item->ticket_code && $item->payment_status == 1)
			{
				$showDownloadTicket = true;
			}
		}

		$db    = $this->db;
		$query = $db->getQuery(true)
			->select('id')
			->from('#__eb_payment_plugins')
			->where('published = 1')
			->where('name NOT LIKE "os_offline%"');
		$db->setQuery($query);
		$onlinePaymentPlugins = $db->loadResult();

		if (in_array('last_name', EventbookingHelper::getPublishedCoreFields()))
		{
			$showLastName = true;
		}
		else
		{
			$showLastName = false;
		}

		$return = base64_encode(JUri::getInstance()->toString());

		$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
		$centerClass = $bootstrapHelper->getClassMapping('center');
		?>
        <table class="<?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered'); ?>" id="adminForm">
            <thead>
            <tr>
                <th>
			        <?php echo JText::_('EB_FIRST_NAME'); ?>
                </th>
		        <?php
		        if ($showLastName)
		        {
			    ?>
                    <th>
	                    <?php echo JText::_('EB_LAST_NAME'); ?>
                    </th>
			    <?php
		        }
		        ?>
                <th class="list_event">
	                <?php echo JText::_('EB_EVENT'); ?>
                </th>
		        <?php
		        if ($config->show_event_date)
		        {
			    ?>
                    <th class="list_event_date">
	                    <?php echo JText::_('EB_EVENT_DATE'); ?>
                    </th>
			    <?php
		        }
		        ?>
                    <th class="list_event_date">
                        <?php echo JText::_('EB_REGISTRATION_DATE'); ?>
                    </th>
		        <?php
		        if ($config->get('history_show_number_registrants', 1))
		        {
			    ?>
                    <th class="list_registrant_number hidden-phone">
	                    <?php echo JText::_('EB_REGISTRANTS'); ?>
                    </th>
			    <?php
		        }

		        if ($config->get('history_show_amount', 1))
		        {
			    ?>
                    <th class="list_amount hidden-phone">
	                    <?php echo JText::_('EB_AMOUNT'); ?>
                    </th>
			    <?php
		        }

		        if ($config->activate_deposit_feature && $showDueAmountColumn)
		        {
			    ?>
                    <th style="text-align: right;">
				        <?php echo JText::_('EB_DUE_AMOUNT'); ?>
                    </th>
			    <?php
		        }
		        ?>
                <th class="list_id">
	                <?php echo JText::_('EB_REGISTRATION_STATUS'); ?>
                </th>
		        <?php
		        if ($config->activate_invoice_feature)
		        {
			    ?>
                    <th class="<?php echo $centerClass; ?>">
	                    <?php echo JText::_('EB_INVOICE_NUMBER'); ?>
                    </th>
			    <?php
		        }

		        if ($showDownloadTicket)
		        {
			    ?>
                    <th class="center">
				        <?php echo JText::_('EB_TICKET'); ?>
                    </th>
			    <?php
		        }

		        if ($showDownloadCertificate)
		        {
			    ?>
                    <th class="center">
				        <?php echo JText::_('EB_CERTIFICATE'); ?>
                    </th>
			    <?php
		        }
		        ?>
            </tr>
            </thead>
            <tbody>
			<?php
                $Itemid = EventbookingHelper::getItemid();
                $registrantItemId = EventbookingHelperRoute::findView('history', $Itemid);

                for ($i=0, $n=count( $items ); $i < $n; $i++)
                {
                    $row       = $items[$i];
                    $link      = JRoute::_('index.php?option=com_eventbooking&view=registrant&id=' . $row->id . '&Itemid=' . $registrantItemId . '&return=' . $return);
                    $eventLink = JRoute::_(EventbookingHelperRoute::getEventRoute($row->event_id, $row->main_category_id, $Itemid));
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo $link; ?>"><?php echo $row->first_name ; ?></a>
                        </td>
                        <?php
                        if ($showLastName)
                        {
                        ?>
                            <td>
                                <?php echo $row->last_name ; ?>
                            </td>
                        <?php
                        }
                        ?>
                        <td>
                            <a href="<?php echo $eventLink; ?>" target="_blank"><?php echo $row->title ; ?></a>
                        </td>
                        <?php
                        if ($config->show_event_date)
                        {
                        ?>
                            <td>
                                <?php
                                if ($row->event_date == EB_TBC_DATE)
                                {
                                    echo JText::_('EB_TBC');
                                }
                                else
                                {
                                    echo JHtml::_('date', $row->event_date, $config->date_format, null);
                                }
                                ?>
                            </td>
                        <?php
                        }
                        ?>
                        <td class="center">
                            <?php echo JHtml::_('date', $row->register_date, $config->date_format) ; ?>
                        </td>
                        <?php
                        if ($config->get('history_show_number_registrants', 1))
                        {
                        ?>
                            <td class="center hidden-phone" style="font-weight: bold;">
                                <?php echo $row->number_registrants; ?>
                            </td>
                        <?php
                        }

                        if ($config->get('history_show_amount', 1))
                        {
                        ?>
                            <td align="right" class="hidden-phone">
                                <?php echo EventbookingHelper::formatCurrency($row->amount, $config, $row->currency_symbol) ; ?>
                            </td>
                        <?php
                        }

                        if ($config->activate_deposit_feature && $showDueAmountColumn)
                        {
                        ?>
                            <td style="text-align: right;">
                                <?php
                                if ($row->payment_status != 1 && $row->published != 2)
                                {
                                    // Check to see if there is an online payment method available for this event
                                    if ($row->payment_methods)
                                    {
                                        $hasOnlinePaymentMethods = count(array_intersect($onlinePaymentPlugins, explode(',', $row->payment_methods)));
                                    }
                                    else
                                    {
                                        $hasOnlinePaymentMethods = count($onlinePaymentPlugins);
                                    }

                                    echo EventbookingHelper::formatCurrency($row->amount - $row->deposit_amount, $config);

                                    if ($hasOnlinePaymentMethods)
                                    {
                                    ?>
                                        <a class="btn-primary" href="<?php echo JRoute::_('index.php?option=com_eventbooking&view=payment&registration_code=' . $row->registration_code . '&Itemid=' . $registrantItemId); ?>"><?php echo JText::_('EB_MAKE_PAYMENT'); ?></a>
                                    <?php
                                    }
                                }
                                ?>
                            </td>
                        <?php
                        }
                        ?>
                        <td class="<?php echo $centerClass; ?>">
                            <?php
                            switch($row->published)
                            {
                                case 0 :
                                    echo JText::_('EB_PENDING');
                                    break ;
                                case 1 :
                                    echo JText::_('EB_PAID');
                                    break ;
                                case 2 :
                                    echo JText::_('EB_CANCELLED');
                                    break;
                                case 3:
                                    echo JText::_('EB_WAITING_LIST');

                                    // If there is space, we will display payment link here to allow users to make payment to become registrants
                                    if ($config->enable_waiting_list_payment && $row->group_id == 0)
                                    {
                                        $event = EventbookingHelperDatabase::getEvent($row->event_id);

                                        if ($event->event_capacity == 0 || ($event->event_capacity - $event->total_registrants >= $row->number_registrants))
                                        {
                                            // Check to see if there is an online payment method available for this event
                                            if ($row->payment_methods)
                                            {
                                                $hasOnlinePaymentMethods = count(array_intersect($onlinePaymentPlugins, explode(',', $row->payment_methods)));
                                            }
                                            else
                                            {
                                                $hasOnlinePaymentMethods = count($onlinePaymentPlugins);
                                            }

                                            if ($hasOnlinePaymentMethods)
                                            {
                                            ?>
                                                <a class="btn-primary" href="<?php echo JRoute::_('index.php?option=com_eventbooking&view=payment&layout=registration&order_number='.$row->registration_code.'&Itemid='.$registrantItemId); ?>"><?php echo JText::_('EB_MAKE_PAYMENT'); ?></a>
                                            <?php
                                            }
                                        }
                                    }
                                    break;
                                }
                            ?>
                        </td>
                        <?php
                        if ($config->activate_invoice_feature)
                        {
                        ?>
                            <td class="<?php echo $centerClass; ?>">
                                <?php
                                if ($row->invoice_number)
                                {
                                ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_eventbooking&task=registrant.download_invoice&id='.($row->cart_id ? $row->cart_id : ($row->group_id ? $row->group_id : $row->id)).'&Itemid='.$registrantItemId); ?>" title="<?php echo JText::_('EB_DOWNLOAD'); ?>"><?php echo EventbookingHelper::formatInvoiceNumber($row->invoice_number, $config, $row) ; ?></a>
                                <?php
                                }
                                ?>
                            </td>
                        <?php
                        }

                        if ($showDownloadTicket)
                        {
                        ?>
                            <td class="<?php echo $centerClass; ?>">
                                <?php
                                if ($row->ticket_code && $row->published == 1 && $row->payment_status == 1)
                                {
                                ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_eventbooking&task=registrant.download_ticket&id='.$row->id.'&Itemid='.$registrantItemId); ?>" title="<?php echo JText::_('EB_DOWNLOAD'); ?>"><?php echo $row->ticket_number ? EventbookingHelperTicket::formatTicketNumber($row->ticket_prefix, $row->ticket_number, $this->config) : JText::_('EB_DOWNLOAD_TICKETS');?></a>
                                <?php
                                }
                                ?>
                            </td>
                        <?php
                        }

                        if ($showDownloadCertificate)
                        {
                        ?>
                            <td class="<?php echo $centerClass; ?>">
                                <?php
                                if ($row->show_download_certificate)
                                {
                                ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_eventbooking&task=registrant.download_certificate&id='.$row->id); ?>" title="<?php echo JText::_('EB_DOWNLOAD'); ?>"><?php echo EventbookingHelper::formatCertificateNumber($row->id, $this->config);?></a>
                                <?php
                                }
                                ?>
                            </td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                }
			?>
            </tbody>
        </table>
		<?php
	}
}
