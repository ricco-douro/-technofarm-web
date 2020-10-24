<?php
/**
 * @package        Joomla
 * @subpackage     OSMembership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
$centerClass  = $bootstrapHelper->getClassMapping('center');
?>
<div id="osm-subscription-history" class="osm-container row-fluid">
<form method="post" name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_osmembership&view=schedulecontent&Itemid='.$this->Itemid); ?>">
<h1 class="osm-page-title"><?php echo JText::_('OSM_MY_SCHEDULE_CONTENT') ; ?></h1>
<?php
	if (!empty($this->items))
	{
		$items = $this->items;
		$subscriptions = $this->subscriptions;
		$config = $this->config;
		require_once JPATH_ROOT . '/components/com_content/helpers/route.php';
	?>
		<table class="adminlist <?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered'); ?>" id="adminForm">
			<thead>
			<tr>
				<th class="title"><?php echo JText::_('OSM_TITLE'); ?></th>
				<th class="title"><?php echo JText::_('OSM_CATEGORY'); ?></th>
				<th class="title <?php echo $centerClass; ?>"><?php echo JText::_('OSM_ACCESSIBLE_ON'); ?></th>
			</tr>
			</thead>
			<?php
			if ($this->pagination->total > $this->pagination->limit)
			{
			?>
				<tfoot>
				<tr>
					<td colspan="3">
						<div class="pagination"><?php echo $this->pagination->getPagesLinks(); ?></div>
					</td>
				</tr>
				</tfoot>
			<?php
			}
			?>
			<tbody>
			<?php
            $db = JFactory::getDbo();

            foreach ($items as $item)
			{
				$articleLink  = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid));
				$subscription = $subscriptions[$item->plan_id];
				$date         = JFactory::getDate($subscription->active_from_date);
				$date->add(new DateInterval('P' . $item->number_days . 'D'));

				$articleReleased = false;

				if ($this->releaseArticleOlderThanXDays > 0)
				{
					if ($item->publish_up && $item->publish_up != $db->getNullDate())
					{
						$publishedDate = $item->publish_up;
					}
					else
					{
						$publishedDate = $item->created;
					}

					$today         = JFactory::getDate();
					$publishedDate = JFactory::getDate($publishedDate);
					$numberDays    = $publishedDate->diff($today)->days;

					// This article is older than configured number of days, it can be accessed for free
					if ($today >= $publishedDate && $numberDays >= $this->releaseArticleOlderThanXDays)
					{
						$articleReleased = true;
					}
				}
				?>
				<tr>
					<td>
						<i class="icon-file"></i>
						<?php
						if ($articleReleased || ($subscription->active_in_number_days >= $item->number_days))
						{
						?>
							<a href="<?php echo $articleLink ?>" target="_blank"><?php echo $item->title; ?></a>
						<?php
						}
						else
						{
							echo $item->title . ' <span class="label">' . JText::_('OSM_LOCKED') . '</span>';
						}
						?>
					</td>
					<td><?php echo $item->category_title; ?></td>
					<td class="<?php echo $centerClass; ?>">
						<?php echo JHtml::_('date', $date->format('Y-m-d H:i:s'), $config->date_format); ?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	<?php
	}
	else
	{
	?>
		<p class="text-info"><?php echo JText::_('OSM_NO_SCHEDULE_CONTENT'); ?></p>
	<?php
	}
?>
</form>
</div>