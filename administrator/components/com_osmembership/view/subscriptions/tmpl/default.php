<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die ;
$ordering = ($this->state->filter_order == 'tbl.ordering');

JToolbarHelper::custom('renew', 'plus', 'plus', 'OSM_RENEW_SUBSCRIPTION', true);
JToolbarHelper::custom('export', 'download', 'download', 'OSM_EXPORT', false);
JToolbarHelper::custom('resend_email', 'envelope', 'envelope', 'OSM_RESEND_EMAIL', true);
JToolbarHelper::custom('disable_reminders', 'delete', 'delete', 'OSM_DISABLE_REMINDERS', true);

// Mass Mail
$layout = new JLayoutFile('joomla.toolbar.batch');
$bar = JToolbar::getInstance('toolbar');
$dhtml = $layout->render(array('title' => JText::_('OSM_MASS_MAIL')));
$bar->appendButton('Custom', $dhtml, 'batch');

$cols = 9 ;
$nullDate = JFactory::getDbo()->getNullDate();
JHtml::_('formbehavior.chosen', 'select');
?>

<form action="index.php?option=com_osmembership&view=subscriptions" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('OSM_FILTER_SEARCH_SUBSCRIPTIONS_DESC');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->filter_search); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('OSM_SEARCH_SUBSCRIPTIONS_DESC'); ?>" onchange="submit();" />
			</div>
            <div class="btn-group pull-left">
                <?php echo $this->lists['filter_date_field']; ?>
            </div>
            <div class="btn-group pull-left osm-filter-date">
	            <?php
	                echo JHtml::_('calendar', $this->state->filter_from_date != $nullDate ? $this->state->filter_from_date : '', 'filter_from_date', 'filter_from_date', $this->datePickerFormat . ' %H:%M:%S', array('class' => 'input-medium', 'showTime' => true, 'placeholder' => JText::_('OSM_FROM')));
	                echo JHtml::_('calendar', $this->state->filter_to_date != $nullDate ? $this->state->filter_to_date : '', 'filter_to_date', 'filter_to_date', $this->datePickerFormat . ' %H:%M:%S', array('class' => 'input-medium', 'showTime' => true, 'placeholder' => JText::_('OSM_TO')));
	            ?>
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span class="icon-search"></span></button>
                <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';document.getElementById('filter_from_date').value='';document.getElementById('filter_to_date').value='';this.form.submit();"><span class="icon-remove"></span></button>
            </div>
			<div class="btn-group pull-right hidden-phone">
				<?php
					echo $this->lists['plan_id'];
					echo $this->lists['subscription_type'];
					echo $this->lists['published'];

                    foreach($this->filters as $filter)
                    {
                        echo $filter;
                    }

                    echo $this->pagination->getLimitBox();
				?>
			</div>
		</div>
		<div class="clearfix"></div>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
					<th class="title" style="text-align: left;">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_FIRSTNAME'), 'tbl.first_name', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="title" style="text-align: left;">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_LASTNAME'), 'tbl.last_name', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<?php
                        foreach ($this->fields as $field)
                        {
                            $cols++;
                            if ($field->is_core || $field->is_searchable)
                            {
                            ?>
                                <th class="title" nowrap="nowrap">
                                    <?php echo JHtml::_('grid.sort', JText::_($field->title), 'tbl.' . $field->name, $this->state->filter_order_Dir, $this->state->filter_order); ?>
                                </th>
                            <?php
                            }
                            else
                            {
                            ?>
                                <th class="title" nowrap="nowrap"><?php echo $field->title; ?></th>
                            <?php
                            }
                        }
				    ?>
					<th class="title" style="text-align: left;">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_PLAN'), 'b.title', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="title center">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_START_DATE'), 'tbl.from_date', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
						/
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_END_DATE'), 'tbl.to_date', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="title center">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_CREATED_DATE'), 'tbl.created_date', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_GROSS_AMOUNT'), 'tbl.gross_amount', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<?php
						if ($this->config->enable_coupon)
						{
						?>
							<th>
								<?php echo JHtml::_('grid.sort',  JText::_('OSM_COUPON'), 'd.code', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
							</th>
						<?php
						}
					?>
					<th width="8%" class="center">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_SUBSCRIPTION_STATUS'), 'tbl.published', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<?php
						if ($this->config->auto_generate_membership_id)
						{
							$cols++ ;
						?>
							<th width="8%" class="center">
								<?php echo JHtml::_('grid.sort',  JText::_('OSM_MEMBERSHIP_ID'), 'tbl.membership_id', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
							</th>
						<?php
						}
						if ($this->config->activate_invoice_feature)
						{
							$cols++ ;
						?>
							<th width="8%" class="center">
								<?php echo JHtml::_('grid.sort',  JText::_('OSM_INVOICE_NUMBER'), 'tbl.invoice_number', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
							</th>
						<?php
						}
					?>
					<th width="2%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_ID'), 'tbl.id', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="<?php echo $cols ; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			$statusCssClasses = [
                0 => 'osm-pending-subscription',
                1 => 'osm-active-subscription',
                2 => 'osm-expired-subscription',
                3 => 'osm-cancelled-pending-subscription',
                5 => 'osm-cancelled-refunded-subscription',
            ];

			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = $this->items[$i];
				$link 	= JRoute::_( 'index.php?option=com_osmembership&task=subscription.edit&cid[]='. $row->id);
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				$accountLink = 'index.php?option=com_users&task=user.edit&id='.$row->user_id ;
				$symbol = $row->currency_symbol ? $row->currency_symbol : $row->currency;
				?>
                <tr class="<?php echo "row$k"; if (isset($statusCssClasses[$row->published])) echo ' ' . $statusCssClasses[$row->published]; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"><?php echo $row->first_name ?: $row->username ; ?></a>
					</td>
					<td>
						<?php echo $row->last_name ; ?>
						<?php
							if ($row->username)
							{
							?>
								<a href="<?php echo $accountLink; ?>" title="View Profile">&nbsp;(<strong><?php echo $row->username ; ?>)</strong></a>
							<?php
							}
						?>
					</td>
                    <?php
                        foreach ($this->fields as $field)
                        {
                            if ($field->is_core)
                            {
                                $fieldValue = $row->{$field->name};
                            }
                            else
                            {
	                            $fieldValue = isset($this->fieldsData[$row->id][$field->id]) ? $this->fieldsData[$row->id][$field->id] : '';
                            }
                        ?>
                            <td>
                                <?php echo $fieldValue; ?>
                            </td>
                        <?php
                        }
					?>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_osmembership&task=plan.edit&cid[]='.$row->plan_id); ?>" target="_blank"><?php echo $row->plan_title ; ?></a>
					</td>
					<td class="center">
						<strong><?php echo JHtml::_('date', $row->from_date, $this->config->date_format); ?></strong> <?php echo JText::_('OSM_TO'); ?>
						<strong>
							<?php
								if ($row->lifetime_membership || $row->to_date == '2099-12-31 23:59:59')
								{
									echo JText::_('OSM_LIFETIME');
								}
								else
								{
									echo JHtml::_('date', $row->to_date, $this->config->date_format);
								}
							?>
						</strong>
					</td>
					<td class="center">
						<?php echo JHtml::_('date', $row->created_date, $this->config->date_format.' H:i:s'); ?>
					</td>
					<td class="center">
						<?php echo OSMembershipHelper::formatCurrency($row->gross_amount, $this->config, $symbol)?>
					</td>
					<?php
						if ($this->config->enable_coupon)
						{
						?>
							<td>
								<a href="index.php?option=com_osmembership&view=coupon&id=<?php echo $row->coupon_id; ?>" target="_blank"><?php  echo $row->coupon_code; ?></a>
							</td>
						<?php
						}
					?>
					<td class="center">
						<?php
		                    switch ($row->published)
		                    {
		                        case 0 :
		                            echo JText::_('OSM_PENDING');
		                            break ;
		                        case 1 :
		                            echo JText::_('OSM_ACTIVE');
		                            break ;
		                        case 2 :
		                            echo JText::_('OSM_EXPIRED');
		                            break ;
		                        case 3 :
		                            echo JText::_('OSM_CANCELLED_PENDING');
		                            break ;
		                        case 4 :
		                            echo JText::_('OSM_CANCELLED_REFUNDED');
		                            break ;
		                    }
							if ($row->recurring_subscription_cancelled)
							{
								echo '<br /><span class="text-error">' . JText::_('OSM_RECURRING_CANCELLED').'</span>';
							}
						?>
					</td>
					<?php
						if ($this->config->auto_generate_membership_id)
						{
						?>
							<td class="center">
								<?php echo OSMembershipHelper::formatMembershipId($row, $this->config); ?>
							</td>
						<?php
						}
						if ($this->config->activate_invoice_feature) {
						?>
							<td class="center">
								<?php
									if ($row->invoice_number)
									{
									?>
										<a href="<?php echo JRoute::_('index.php?option=com_osmembership&task=download_invoice&id='.$row->id); ?>" title="<?php echo JText::_('OSM_DOWNLOAD'); ?>"><?php echo OSMembershipHelper::formatInvoiceNumber($row, $this->config) ; ?></a>
									<?php
									}
								?>
							</td>
						<?php
						}
					?>
					<td class="center">
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
		
		<?php echo JHtml::_( 
				'bootstrap.renderModal',
				'collapseModal',
				array(
						'title' => JText::_('OSM_MASS_MAIL'),
						'footer' => $this->loadTemplate('batch_footer')
				),
				$this->loadTemplate('batch_body')
		); ?>
		
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $this->state->filter_order; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->filter_order_Dir; ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>

	<?php
		if ($this->config->force_select_plan)
		{
		?>
			<script type="text/javascript">
				Joomla.submitbutton = function(pressbutton)
				{
					var form = document.adminForm;

					if (pressbutton == 'add')
					{
						if (form.plan_id.value == 0)
						{
							alert("<?php echo JText::_("OSM_SELECT_PLAN_TO_ADD_SUBSCRIPTION"); ?>");
							form.plan_id.focus();
							return;
						}
					}

					Joomla.submitform( pressbutton );
				}
			</script>
		<?php
		}
	?>
</form>