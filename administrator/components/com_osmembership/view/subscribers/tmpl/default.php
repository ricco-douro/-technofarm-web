<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die ;
$ordering = ($this->state->filter_order == 'tbl.ordering');
$cols = 6;
?>
<form action="index.php?option=com_osmembership&view=subscribers" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('OSM_FILTER_SEARCH_SUBSCRIBERS_DESC');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->filter_search); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('OSM_SEARCH_SUBSCRIBERS_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span class="icon-search"></span></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><span class="icon-remove"></span></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<?php
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
					<th class="title">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_FIRSTNAME'), 'tbl.first_name', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_LASTNAME'), 'tbl.last_name', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_EMAIL'), 'tbl.email', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<?php
						if ($this->config->auto_generate_membership_id)
						{
							$cols++ ;
						?>
							<th width="8%">
								<?php echo JHtml::_('grid.sort',  JText::_('OSM_MEMBERSHIP_ID'), 'tbl.membership_id', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
							</th>
						<?php
						}
					?>
					<th class="title center">
						<?php echo JText::_('OSM_PLANS'); ?>
					</th>
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
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = $this->items[$i];
				$link 	= JRoute::_( 'index.php?option=com_osmembership&task=subscriber.edit&cid[]='. $row->id);
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				$accountLink = 'index.php?option=com_users&task=user.edit&id='.$row->user_id ;
				$plans = $row->plans;
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"><?php echo $row->first_name ; ?></a>
						<?php
						if ($row->username)
						{
						?>
							[<a href="<?php echo $accountLink; ?>" title="View Profile"><strong><?php echo $row->username ; ?></strong></a>]
						<?php
						}
						?>
					</td>
					<td>
						<?php echo $row->last_name ; ?>
					</td>
					<td>
						<a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a>
					</td>
					<?php
						if ($this->config->auto_generate_membership_id)
						{
						?>
							<td>
								<?php echo OSMembershipHelper::formatMembershipId($row, $this->config); ?>
							</td>
						<?php
						}
					?>
					<td>
						<ul class="osm-plans-container">
							<?php
								foreach($plans as $plan)
								{
								?>
									<li>
										<strong><?php echo $plan->title; ?></strong> - [


										<strong><?php echo JHtml::_('date', $plan->subscription_from_date, $this->config->date_format); ?></strong> <?php echo JText::_('OSM_TO'); ?>
										<strong>
											<?php
												if ($plan->lifetime_membership || $plan->subscription_to_date == '2099-12-31 23:59:59')
												{
													echo JText::_('OSM_LIFETIME');
												}
												else
												{
													echo JHtml::_('date', $plan->subscription_to_date, $this->config->date_format);
												}
											?>
										</strong>
										] -
										<?php
											switch ($plan->subscription_status)
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
					                            default:
					                                echo JText::_('OSM_CANCELLED');
					                                break;

											}
										?>
									</li>
								<?php
								}
							?>
						</ul>
					</td>
					<td align="center">
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $this->state->filter_order; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->filter_order_Dir; ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
</form>