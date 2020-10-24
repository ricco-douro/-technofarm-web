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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_services') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'tokenform.xml');
$canEdit    = $user->authorise('core.edit', 'com_services') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'tokenform.xml');
$canCheckin = $user->authorise('core.manage', 'com_services');
$canChange  = $user->authorise('core.edit.state', 'com_services');
$canDelete  = $user->authorise('core.delete', 'com_services');
?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	<table class="table table-striped" id="tokenList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_SERVICES_TOKENS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_SERVICES_TOKENS_USERID', 'a.userid', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_SERVICES_TOKENS_TOKEN', 'a.token', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_SERVICES_TOKENS_MODE', 'a.mode', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_SERVICES_TOKENS_DEBUG', 'a.debug', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_SERVICES_TOKENS_API_RATE_LIMIT', 'a.api_rate_limit', $listDirn, $listOrder); ?>
				</th>


            <?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_SERVICES_TOKENS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

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
		<?php foreach ($this->items as $i => $item) : ?>
            <?php if($item->created_by === $userId ){ ?>
                <?php $canEdit = $user->authorise('core.edit.own', 'com_services.token.'.$item->id); ?>
                <?php if($item->created_by === $userId ){$canChange = true; } ?>
				<?php if($item->created_by === $userId ){$canDelete = true; } ?>

                <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_services')): ?>
                    <?php $canEdit = (JFactory::getUser()->id === $item->created_by); ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					
				<?php endif; ?>

                <td>

					<?php echo $item->id; ?>
				</td>
				<td>
                    <?php echo JFactory::getUser($item->userid)->name; ?>
                </td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'tokens.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_services&view=token&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->token); ?></a>
				</td>
				<td>

					<?php echo $item->mode; ?>
				</td>
				<td>

					<?php echo $item->debug; ?>
				</td>
				<td>

					<?php echo $item->api_rate_limit; ?>
				</td>

                <?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_services&task=tokenform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_services&task=tokenform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
			<?php } ?>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_services&task=tokenform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_SERVICES_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_SERVICES_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
