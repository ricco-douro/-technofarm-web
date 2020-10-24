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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_services', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_services/js/form.js');

$user    = JFactory::getUser();
$canEdit = ServicesHelpersServices::canUserEdit($this->item, $user);


if($this->item->state == 1){
	$state_string = 'Publish';
	$state_value = 1;
} else {
	$state_string = 'Unpublish';
	$state_value = 0;
}
if($this->item->id) {
	$canState = JFactory::getUser()->authorise('core.edit.state','com_services.token');
} else {
	$canState = JFactory::getUser()->authorise('core.edit.state','com_services.token.'.$this->item->id);
}

?>

<div class="token-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_SERVICES_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_SERVICES_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo JText::_('COM_SERVICES_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-token"
			  action="<?php echo JRoute::_('index.php?option=com_services&task=token.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

				<?php echo $this->form->getInput('created'); ?>
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
	<input type="hidden" name="jform[last_used]" value="<?php echo $this->item->last_used; ?>" />

	<div class="control-group">
		<?php if(!$canState): ?>
			<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
			<div class="controls"><?php echo $state_string; ?></div>
			<input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>" />
		<?php else: ?>
			<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
		<?php endif; ?>
	</div>

	<?php echo $this->form->renderField('userid'); ?>

	<?php echo $this->form->renderField('token'); ?>

	<?php echo $this->form->renderField('mode'); ?>

	<?php echo $this->form->renderField('debug'); ?>

	<?php echo $this->form->renderField('log_level'); ?>

	<?php echo $this->form->renderField('log_enabled'); ?>

	<?php echo $this->form->renderField('cookies_encrypt'); ?>

	<?php echo $this->form->renderField('cookies_domain'); ?>

	<?php echo $this->form->renderField('cookies_secure'); ?>

	<?php echo $this->form->renderField('cookies_secret_key'); ?>

	<?php echo $this->form->renderField('http_version'); ?>

	<?php echo $this->form->renderField('api_rate_limit'); ?>
				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','services')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','services')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-token").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_services&task=tokenform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_services"/>
			<input type="hidden" name="task"
				   value="tokenform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
