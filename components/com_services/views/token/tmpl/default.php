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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_services.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_services' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_USERID'); ?></th>
			<td><?php echo $this->item->userid_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_TOKEN'); ?></th>
			<td><?php echo $this->item->token; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_MODE'); ?></th>
			<td><?php echo $this->item->mode; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_DEBUG'); ?></th>
			<td><?php echo $this->item->debug; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_LOG_LEVEL'); ?></th>
			<td><?php echo $this->item->log_level; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_LOG_ENABLED'); ?></th>
			<td><?php echo $this->item->log_enabled; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_COOKIES_ENCRYPT'); ?></th>
			<td><?php echo $this->item->cookies_encrypt; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_COOKIES_DOMAIN'); ?></th>
			<td><?php echo $this->item->cookies_domain; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_COOKIES_SECURE'); ?></th>
			<td><?php echo $this->item->cookies_secure; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_COOKIES_SECRET_KEY'); ?></th>
			<td><?php echo $this->item->cookies_secret_key; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_HTTP_VERSION'); ?></th>
			<td><?php echo $this->item->http_version; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_SERVICES_FORM_LBL_TOKEN_API_RATE_LIMIT'); ?></th>
			<td><?php echo $this->item->api_rate_limit; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_services&task=token.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_SERVICES_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_services.token.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_SERVICES_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_SERVICES_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_SERVICES_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_services&task=token.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_SERVICES_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>