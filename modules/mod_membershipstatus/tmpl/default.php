<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

if (empty($rowSubscriptions))
{
?>
	<p class="text-info"><?php echo JText::_('OSM_NO_ACTIVE_SUBSCRIPTIONS'); ?></p>
<?php
}
else
{
?>
	<ul class="osm-active-plans-list">
		<?php
			$todayDate = JFactory::getDate();

			foreach($rowSubscriptions as $rowSubscription)
			{
				if ($rowSubscription->lifetime_membership || $rowSubscription->subscription_to_date  == '2099-12-31 23:59:59')
				{
					$membershipStatus = JText::_('OSM_MEMBERSHIP_STATUS_LIFETIME');
				}
				else
				{
					$expiredDate = JFactory::getDate($rowSubscription->subscription_to_date);
					$numberDays = $todayDate->diff($expiredDate)->days;

					if ($todayDate < $expiredDate)
					{
						$membershipStatus = JText::_('OSM_MEMBERSHIP_STATUS_ACTIVE');
					}
					else
					{
						$membershipStatus = JText::_('OSM_MEMBERSHIP_STATUS_EXPIRED');
					}
				}

				$membershipStatus = str_replace('[PLAN_TITLE]', $rowSubscription->title, $membershipStatus);
				$membershipStatus = str_replace('[EXPIRED_DATE]', JHtml::_('date', $rowSubscription->subscription_to_date, OSMembershipHelper::getConfigValue('date_format')), $membershipStatus);
				$membershipStatus = str_replace('[NUMBER_DAYS]', abs($numberDays), $membershipStatus);
			?>
				<li><?php echo $membershipStatus; ?></li>
			<?php
			}
		?>
	</ul>
<?php
}