<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$form                                = JForm::getInstance('renewal_discounts', JPATH_ADMINISTRATOR . '/components/com_osmembership/view/plan/forms/renewal_discounts.xml');
$formData['renewal_discounts'] = [];

foreach ($this->renewalDiscounts as $renewalDiscount)
{
	$formData['renewal_discounts'][] = [
		'id'              => $renewalDiscount->id,
		'number_days'     => $renewalDiscount->number_days,
		'discount_type'   => $renewalDiscount->discount_type,
		'discount_amount' => $renewalDiscount->discount_amount,
	];
}

$form->bind($formData);
?>
<fieldset class="adminform span6 clearfix">
    <legend class="adminform"><?php echo JText::_('OSM_EARLY_RENEWAL_DISCOUNTS'); ?></legend>
    <?php
    foreach ($form->getFieldset() as $field)
    {
        echo $field->input;
    }
    ?>
</fieldset>