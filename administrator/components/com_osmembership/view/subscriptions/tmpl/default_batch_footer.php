<?php
/**
 * @package            Joomla
 * @subpackage         Event Booking
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2016 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
?>
<button class="btn" type="button" data-dismiss="modal">
	<?php echo JText::_('JCANCEL'); ?>
</button>
<button class="btn btn-success" type="submit" onclick="Joomla.submitbutton('subscription.batch_mail');">
	<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
</button>