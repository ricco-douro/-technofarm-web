<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
$centerClass  = $bootstrapHelper->getClassMapping('center');
?>
<div id="osm-container">
	<div class="page-header">
		<h1 class="osm-heading"><?php echo JText::_('OSM_IMPORT_SUBSCRIPTIONS'); ?></h1>
	</div>
	<form action="index.php?option=com_osmembership&view=subscribers&Itemid=<?php echo $this->Itemid; ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
		<div class="btn-toolbar" id="btn-toolbar">
			<?php echo JToolbar::getInstance('toolbar')->render('toolbar'); ?>
		</div>
        <p class="<?php echo $bootstrapHelper->getClassMapping('text-info'); ?>"><?php echo JText::_('OSM_SUBSCRIBERS_FILE_EXPLAIN'); ?></p>
        <div class="<?php echo $bootstrapHelper->getClassMapping('control-group'); ?>">
              <div class="<?php echo $bootstrapHelper->getClassMapping('control-label'); ?>">
                    <?php echo JText::_('OSM_SUBSCRIBERS_FILE'); ?>
              </div>
              <div class="<?php echo $bootstrapHelper->getClassMapping('controls'); ?>">
                  <input type="file" name="input_file" size="50" />
              </div>
        </div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_( 'form.token' ); ?>

		<script type="text/javascript">
			Joomla.submitbutton = function(pressbutton)
			{
				var form = document.adminForm;

				if (pressbutton == 'subscriber.import')
				{
					if (form.input_file.value == '')
					{
						alert("<?php echo JText::_("OSM_SELECT_FILE_TO_IMPORT_SUBSCRIPTIONS"); ?>");
						form.input_file.focus();
						return;
					}
				}

				Joomla.submitform( pressbutton );
			}
		</script>

	</form>
</div>