<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die ;

$document = JFactory::getDocument();
$document->addScriptDeclaration('
	var siteUrl = "' . JUri::root() . '";			
');
$document->addScript(JUri::root(true) . '/media/com_osmembership/assets/js/membershippro.js');
OSMembershipHelper::addLangLinkForAjax($this->item->language);

$selectedState = '';
$stateType = 0;

if ($this->item->user_id)
{
?>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_USERNAME'); ?>
		</div>
		<div class="controls">
			<?php echo $this->item->username; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_PASSWORD'); ?>
		</div>
		<div class="controls">
			<input type="password" name="password" size="20" value="" />
		</div>
	</div>
<?php
}

if ($this->item->membership_id)
{
?>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_MEMBERSHIP_ID'); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelper::formatMembershipId($this->item, $this->config);?>
		</div>
	</div>
<?php
}

$fields = $this->form->getFields();

if (isset($fields['state']))
{
	$selectedState = $fields['state']->value;

	if ($fields['state']->type == 'State')
	{
		$stateType = 1;
	}
}

if (isset($fields['email']))
{
	$fields['email']->setAttribute('class', 'validate[required,custom[email]]');
}

foreach ($fields as $field)
{
	/* @var MPFFormField $field */
	echo $field->getControlGroup();
}

if ($stateType)
{
?>
	<script type="text/javascript">
		(function($){
			$(document).ready(function(){
				buildStateFields('state', 'country', '<?php echo $selectedState; ?>');
			})
		})(jQuery);
	</script>
	<?php
}
