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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_services/css/form.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {

    });

    Joomla.submitbutton = function (task) {
        if (task == 'token.cancel') {
            Joomla.submitform(task, document.getElementById('token-form'));
        }
        else {

            if (task != 'token.cancel' && document.formvalidator.isValid(document.id('token-form'))) {

                Joomla.submitform(task, document.getElementById('token-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form
        action="<?php echo JRoute::_('index.php?option=com_services&layout=edit&id=' . (int) $this->item->id); ?>"
        method="post" enctype="multipart/form-data" name="adminForm" id="token-form" class="form-validate">

    <div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_SERVICES_TITLE_TOKEN', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

					<?php echo $this->form->renderField('created'); ?>				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
                    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

					<?php echo $this->form->renderField('created_by'); ?>				<input type="hidden" name="jform[last_used]" value="<?php echo $this->item->last_used; ?>" />
					<?php echo $this->form->renderField('state'); ?>
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


					<?php if ($this->state->params->get('save_history', 1)) : ?>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
                        </div>
					<?php endif; ?>
                </fieldset>
            </div>
        </div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if (JFactory::getUser()->authorise('core.admin','services')) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
			<?php echo $this->form->getInput('rules'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

    </div>
</form>