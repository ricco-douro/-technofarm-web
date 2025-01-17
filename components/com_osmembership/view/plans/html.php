<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */


defined('_JEXEC') or die;

/**
 * HTML View class for OS Membership component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewPlansHtml extends MPFViewHtml
{
	public function display()
	{
		$config = OSMembershipHelper::getConfig();
		$model  = $this->getModel();
		$items  = $model->getData();

		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item    = $items[$i];
			$taxRate = OSMembershipHelper::calculateTaxRate($item->id);

			if ($config->show_price_including_tax && $taxRate > 0)
			{
				$item->price        = $item->price * (1 + $taxRate / 100);
				$item->trial_amount = $item->trial_amount * (1 + $taxRate / 100);

				$item->setup_fee = $item->setup_fee * (1 + $taxRate / 100);
			}

			$item->short_description = JHtml::_('content.prepare', $item->short_description);
			$item->description       = JHtml::_('content.prepare', $item->description);
		}

		$categoryId = (int) $model->getState()->get('id', 0);

		// Load sub-categories of the current category
		if ($categoryId > 0)
		{
			$categoriesModel = new OSMembershipModelCategories(
				array(
					'remember_states' => false,
					'ignore_request'  => true,
				)
			);

			$this->categories = $categoriesModel->limitstart(0)
				->limit(0)
				->filter_order('tbl.ordering')
				->id($categoryId)
				->getData();
		}
		else
		{
			$this->categories = array();
		}

		// Process page title and meta data
		$active = JFactory::getApplication()->getMenu()->getActive();
		$params = OSMembershipHelper::getViewParams($active, array('categories', 'plans'));

		if ($active)
		{
			$this->setDocumentMetadata($params);
		}

		$category = OSMembershipHelperDatabase::getCategory($categoryId);

		if ($category)
		{
			$category->description = JHtml::_('content.prepare', $category->description);
		}

		$this->category        = $category;
		$this->pagination      = $model->getPagination();
		$this->items           = $items;
		$this->config          = $config;
		$this->categoryId      = $categoryId;
		$this->bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
		$this->params          = $params;

		parent::display();
	}
}
