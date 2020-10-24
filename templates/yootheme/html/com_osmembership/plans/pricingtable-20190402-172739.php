<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
$categoryId = $this->category ? $this->category->id : 0;
?>
<div id="osm-plans-list-columns" class="osm-container osm-pricingtable-container<?php echo $categoryId; ?>">
	<?php
		if (!$this->input->getInt('hmvc_call'))
		{
			if ($this->params->get('show_page_heading', 1))
			{
				if ($this->category)
				{
					$pageHeading = $this->params->get('page_heading') ? $this->params->get('page_heading') : $this->category->title;
				}
				else
				{
					$pageHeading = $this->params->get('page_heading') ? $this->params->get('page_heading') : JText::_('OSM_SUBSCRIPTION_PLANS');
				}
			?>
				<h1 class="osm-page-title"><?php echo $pageHeading; ?></h1>
			<?php
			}

			if (!empty($this->category->description))
			{
			?>
				<div class="osm-description <?php echo $this->bootstrapHelper->getClassMapping('clearfix'); ?>"><?php echo $this->category->description;?></div>
			<?php
			}
		}

		if (count($this->categories))
		{
			echo OSMembershipHelperHtml::loadCommonLayout('common/tmpl/categories.php', array('items' => $this->categories, 'categoryId' => $this->categoryId, 'config' => $this->config, 'Itemid' => $this->Itemid));
		}

		if (count($this->items))
		{
			echo OSMembershipHelperHtml::loadCommonLayout('common/tmpl/pricingtable_plans.php', array('items' => $this->items, 'input' => $this->input, 'config' => $this->config, 'Itemid' => $this->Itemid, 'categoryId' => $this->categoryId, 'bootstrapHelper' => $this->bootstrapHelper));
		}
	?>
</div>