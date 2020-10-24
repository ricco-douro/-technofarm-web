<?php
/**
 * @package        Joomla
 * @subpackage     OSMembership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;
?>
<input type="<?php echo $type; ?>"
       name="<?php echo $name; ?>" id="<?php echo $name; ?>"
       value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?>"<?php echo $attributes; ?> />
