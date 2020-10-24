<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
?>
<div class="control-group">
    <div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('enable_api', JText::_('OSM_ENABLE_API'), JText::_('OSM_ENABLE_API_EXPLAIN')); ?>
    </div>
    <div class="controls">
		<?php echo OSMembershipHelperHtml::getBooleanInput('enable_api', $config->get('enable_api', 0)); ?>
    </div>
</div>
<div class="control-group">
    <div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('api_key', JText::_('OSM_API_KEY'), JText::_('OSM_API_KEY_EXPLAIN')); ?>
    </div>
    <div class="controls">
        <input type="text" name="api_key" class="input-xlarge" value="<?php echo $config->api_key; ?>" />
    </div>
</div>

