<?php
/**
 * @package            Joomla
 * @subpackage         Event Booking
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2016 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));
?>
<div class="row-fluid form form-horizontal">
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_EMAIL_SUBJECT'); ?>
		</div>
		<div class="controls">
			<input type="text" name="subject" value="" size="70" class="input-xxlarge" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_EMAIL_MESSAGE'); ?>
            <p><strong><?php echo JText::_('OSM_AVAILABLE_TAGS'); ?> : [PLAN_TITLE], [FIRST_NAME], [LAST_NAME], [ADDRESS] ...,[CREATED_DATE],[FROM_DATE], [TO_DATE]</strong></p>
		</div>
		<div class="controls">
			<?php echo $editor->display( 'message',  '' , '100%', '250', '75', '10' ) ; ?>
		</div>
	</div>
</div>

