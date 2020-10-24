<?php
/**
 * @package        Joomla
 * @subpackage     OSMembership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$class      = '';
$useTooltip = false;

if (!empty($description))
{
	JHtml::_('bootstrap.tooltip');
	JFactory::getDocument()->addStyleDeclaration(".hasTip{display:block !important}");
	$useTooltip = true;
	$class = 'hasTooltip hasTip';
}
?>
<label id="<?php echo $name; ?>-lbl" for="<?php echo $name; ?>"<?php if ($class) echo ' class="' . $class . '"' ?> <?php if ($useTooltip) echo ' title="' . JHtml::tooltipText(trim($title, ':'), $description, 0) . '"'; ?>>
	<?php
	echo $title;

	if ($row->required)
	{
	?>
		<span class="star">&#160;*</span>
	<?php
	}
	?>
</label>