<?php
/**
 * @package        Joomla
 * @subpackage     OSMembership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$controlGroupClass = $bootstrapHelper ? $bootstrapHelper->getClassMapping('control-group') : 'control-group';
$controlLabelClass = $bootstrapHelper ? $bootstrapHelper->getClassMapping('control-label') : 'control-label';
$controlsClass     = $bootstrapHelper ? $bootstrapHelper->getClassMapping('controls') : 'controls';
?>
<div class="<?php echo $controlGroupClass . $class ?>" <?php echo $controlGroupAttributes ?>>
	<div class="<?php echo $controlLabelClass ?>">
		<?php echo $label; ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<?php echo $input; ?>
	</div>
</div>

