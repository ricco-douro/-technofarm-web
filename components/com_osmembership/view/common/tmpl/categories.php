<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
$clearfixClass   = $bootstrapHelper->getClassMapping('clearfix');

for ($i = 0 , $n = count($items) ; $i < $n ; $i++)
{
	$item = $items[$i] ;
	$link = JRoute::_(OSMembershipHelperRoute::getCategoryRoute($item->id, $Itemid));
	?>
	<div class="osm-item-wrapper clearfix">
		<div class="osm-item-heading-box">
			<h3 class="osm-item-title">
				<a href="<?php echo $link; ?>" class="osm-item-title-link">
					<?php echo $item->title;?>
				</a>
				<small>( <?php echo $item->total_plans ;?> <?php echo $item->total_plans > 1 ? JText::_('OSM_PLANS') :  JText::_('OSM_PLAN') ; ?> )</small>
			</h3>
		</div>
		<?php
		if($item->description)
		{
		?>
			<div class="osm-item-description <?php echo $clearfixClass; ?>">
				<?php echo $item->description;?>
			</div>
		<?php
		}
		?>
	</div>
<?php
}