<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die ;

OSMembershipHelper::addLangLinkForAjax();
$document = JFactory::getDocument();
$document->addScriptDeclaration('
	var siteUrl = "' . JUri::root() . '";			
');
$document->addScript(JUri::root(true) . '/media/com_osmembership/assets/js/membershippro.js');

$selectedState = '';
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;

		if (pressbutton == 'cancel')
		{
			Joomla.submitform(pressbutton, form);
		}
		else
		{
			//Validate the entered data before submitting
			if (form.plan_id.value == 0)
			{
				alert("<?php echo JText::_('OSM_PLEASE_SELECT_PLAN'); ?>");
				return;
			}

			if (form.group_admin_id.value == 0)
			{
				alert("<?php echo JText::_('OSM_PLEASE_SELECT_GROUP'); ?>");
				return;
			}

			if (document.getElementById('user_id_id').value == 0)
			{
				// Require user to enter username and password
				if (form.username.value == '')
				{
					alert("<?php echo JText::_('OSM_PLEASE_ENTER_USERNAME'); ?>");
					return;
				}

				if (form.password.value == '')
				{
					alert("<?php echo JText::_('OSM_PLEASE_ENTER_PASSWORD'); ?>");
					return;
				}
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php?option=com_osmembership&view=groupmember" method="post" name="adminForm" id="adminForm" autocomplete="off" enctype="multipart/form-data">
<div class="row-fluid" style="float:left">
<table class="admintable adminform">
<tr>
	<td class="key">
		<?php echo JText::_('OSM_PLAN'); ?>
	</td>
	<td>
		<?php echo $this->lists['plan_id'] ; ?>
	</td>
</tr>
<tr>
	<td class="key">
		<?php echo JText::_('OSM_GROUP'); ?>
	</td>
	<td id="group_admin_container">
		<?php echo $this->lists['group_admin_id'] ; ?>
	</td>
</tr>
<?php
if (!$this->item->id)
{
?>
	<tr id="username_container">
		<td class="key">
			<?php echo JText::_('OSM_USERNAME'); ?>
		</td>
		<td>
			<input type="text" id="username" name="username" size="20" value="" />
			<?php echo JText::_('OSM_USERNAME_EXPLAIN'); ?>
		</td>
	</tr>
	<tr id="password_container">
		<td class="key">
			<?php echo JText::_('OSM_PASSWORD'); ?>
		</td>
		<td>
			<input type="password" id="password" name="password" size="20" value="" />
		</td>
	</tr>
<?php
}
?>
<tr>
	<td class="key">
		<?php echo JText::_('OSM_USER'); ?>
	</td>
	<td>
		<?php echo OSMembershipHelper::getUserInput($this->item->user_id, (int) $this->item->id) ; ?>
	</td>
</tr>
<?php
$fields = $this->form->getFields();
if (isset($fields['state']))
{
	if ($fields['state']->type == 'State')
	{
		$stateType = 1;
	}
	else
	{
		$stateType = 0;
	}
	$selectedState = $fields['state']->value;
}
foreach ($fields as $field)
{
	if (!$field->row->show_on_group_member_form)
	{
		continue;
	}

	switch (strtolower($field->type))
	{
		case 'heading' :
			?>
			<tr><td colspan="2"><h3 class="osm-heading"><?php echo JText::_($field->title) ; ?></h3></td></tr>
			<?php
			break ;
		case 'message' :
			?>
			<tr>
				<td colspan="2">
					<p class="osm-message">
						<?php echo $field->description ; ?>
					</p>
				</td>
			</tr>
			<?php
			break ;
		default:
			?>
				<tr id="field_<?php echo $field->name; ?>">
					<td class="key">
						<?php echo JText::_($field->title); ?>
					</td>
					<td class="controls">
						<?php echo $field->input; ?>
					</td>
				</tr>
			<?php
			break;
	}
}

if ($this->item->id)
{
?>
	<tr>
		<td class="key">
			<?php echo  JText::_('OSM_CREATED_DATE'); ?>
		</td>
		<td>
			<?php echo JHtml::_('calendar', $this->item->created_date, 'created_date', 'created_date', $this->datePickerFormat . ' %H:%M:%S') ; ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<?php echo  JText::_('OSM_SUBSCRIPTION_START_DATE'); ?>
		</td>
		<td>
			<?php echo JHtml::_('calendar', $this->item->from_date, 'from_date', 'from_date', $this->datePickerFormat . ' %H:%M:%S') ; ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<?php echo  JText::_('OSM_SUBSCRIPTION_END_DATE'); ?>
		</td>
		<td>
			<?php
			if ($this->item->lifetime_membership || $this->item->to_date == '2099-12-31 23:59:59')
			{
				echo JText::_('OSM_LIFETIME');
			}
			else
			{
				echo JHtml::_('calendar', $this->item->to_date, 'to_date', 'to_date', $this->datePickerFormat . ' %H:%M:%S') ;
			}
			?>
		</td>
	</tr>
<?php
}
?>
</table>
</div>
<div class="clr"></div>
<input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>"/>
<input type="hidden" name="task" value="" />
<input type="hidden" id="current_group_admin_id=<?php echo (int) $this->item->group_admin_id; ?>" />
<?php echo JHtml::_( 'form.token' ); ?>
<script type="text/javascript">
	(function($){
		buildGroupAdmin = (function(planId){
			var groupAdminId = $('#current_group_admin_id').val();
			$.ajax({
				type : 'POST',
				url : 'index.php?option=com_osmembership&view=groupmember&format=raw&group_admin_id=' + groupAdminId + '&plan_id=' +planId,
				success: function(data) {
					$('#group_admin_container').html(data);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert(textStatus);
				}
			})
		});

		<?php
		if ($stateType)
		{
		?>
			$(document).ready(function(){
				buildStateFields('state', 'country', '<?php echo $selectedState; ?>');
			});
		<?php
		}
		?>
	})(jQuery);
</script>
</form>