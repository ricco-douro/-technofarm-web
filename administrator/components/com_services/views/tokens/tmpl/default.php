<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_services/assets/css/services.css');
$document->addStyleSheet(JUri::root() . 'media/com_services/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_services');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_services&task=tokens.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'tokenList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo JRoute::_('index.php?option=com_services&view=tokens'); ?>" method="post"
      name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
		<?php else : ?>
        <div id="j-main-container">
			<?php endif; ?>

			<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

            <div class="clearfix"></div>
            <table class="table table-striped" id="tokenList">
                <thead>
                <tr>
					<?php if (isset($this->items[0]->ordering)): ?>
                        <th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
                    <th width="1%" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value=""
                               title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                    </th>
					<?php if (isset($this->items[0]->state)): ?>
                        <th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                        </th>
					<?php endif; ?>

                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_SERVICES_TOKENS_ID', 'a.`id`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_SERVICES_TOKENS_USERID', 'a.`userid`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_SERVICES_TOKENS_TOKEN', 'a.`token`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_SERVICES_TOKENS_API_RATE_LIMIT', 'a.`api_rate_limit`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_SERVICES_TOKENS_API_THROTTLE', 'a.`api_throttle`', $listDirn, $listOrder); ?>
                    </th>

                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
                </tfoot>
                <tbody>
				<?php foreach ($this->items as $i => $item) :
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_services');
					$canEdit    = $user->authorise('core.edit', 'com_services');
					$canCheckin = $user->authorise('core.manage', 'com_services');
					$canChange  = $user->authorise('core.edit.state', 'com_services');
					?>
                    <tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
                            <td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif; ?>
                                    <span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
                                          title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
                                    <input type="text" style="display:none" name="order[]" size="5"
                                           value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
								<?php else : ?>
                                    <span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
								<?php endif; ?>
                            </td>
						<?php endif; ?>
                        <td class="hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
						<?php if (isset($this->items[0]->state)): ?>
                            <td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'tokens.', $canChange, 'cb'); ?>
                            </td>
						<?php endif; ?>
                        <td>
							<?php echo $item->id; ?>
                        </td>
                        <td>
	                        <?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
		                        <?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'tokens.', $canCheckin); ?>
	                        <?php endif; ?>
	                        <?php if ($canEdit) : ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_services&task=token.edit&id='.(int) $item->id); ?>">
			                        <?php echo $this->escape($item->userid); ?></a>
	                        <?php else : ?>
		                        <?php echo $this->escape($item->userid); ?>
	                        <?php endif; ?>
                        </td>
                        <td>
							<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'tokens.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($canEdit) : ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_services&task=token.edit&id='.(int) $item->id); ?>">
									<?php echo $this->escape($item->token); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->token); ?>
							<?php endif; ?>
                        </td>
                        <td>
							<?php echo $item->api_rate_limit; ?>
                        </td>
                        <td>
							<?php echo $item->api_throttle; ?>
                        </td>

                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>

            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
        </div>
</form>
<script>
    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[ id ];

        if (!cb) return false;

        while (true) {
            cbx = f[ 'cb' + i ];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField   = document.createElement('input');

        inputField.type  = 'hidden';
        inputField.name  = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        window.submitform(task);

        return false;
    };
</script>