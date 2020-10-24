<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2010 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
JToolBarHelper::title(JText::_('OSM_DASHBOARD'), 'generic.png');

$user = JFactory::getUser();
$bootstrapHelper = OSMembershipHelperHtml::getAdminBootstrapHelper();
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="<?php echo $bootstrapHelper->getClassMapping('row-fluid'); ?>">
        <div class="<?php echo $bootstrapHelper->getClassMapping('span6'); ?>">
            <div id="cpanel">
                <?php
                if (OSMembershipHelper::canAccessThisView('configuration'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=configuration', 'icon-48-config.png', JText::_('OSM_CONFIGURATION'));
                }

                if (OSMembershipHelper::canAccessThisView('categories'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=categories', 'icon-48-categories.png', JText::_('OSM_PLAN_CATEGORIES'));
                }

                if (OSMembershipHelper::canAccessThisView('plans'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=plans', 'icon-48-package.png', JText::_('OSM_SUBSCRIPTION_PLANS'));
                }

                if ($user->authorise('membershippro.subscriptions', 'com_osmembership'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=subscriptions', 'icon-48-subscribers.png', JText::_('OSM_SUBSCRIPTIONS'));
                    $this->quickiconButton('index.php?option=com_osmembership&view=subscribers', 'icon-48-profiles.png', JText::_('OSM_SUBSCRIBERS'));
                    $this->quickiconButton('index.php?option=com_osmembership&view=groupmembers', 'icon-48-profiles.png', JText::_('OSM_GROUPMEMBERS'));
                }

                if (OSMembershipHelper::canAccessThisView('fields'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=fields', 'icon-48-fields.png', JText::_('OSM_CUSTOM_FIELDS'));
                }

                if (OSMembershipHelper::canAccessThisView('taxes'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=taxes', 'icon-48-taxrules.png', JText::_('OSM_TAX_RULES'));
                }

                if (OSMembershipHelper::canAccessThisView('coupons'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=coupons', 'icon-48-coupons.png', JText::_('OSM_COUPONS'));
                }

                if ($user->authorise('core.admin', 'com_osmembership'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=message', 'icon-48-mail.png', JText::_('OSM_EMAIL_MESSAGES'));
                    $this->quickiconButton('index.php?option=com_osmembership&view=plugins', 'icon-48-payments-plugin.png', JText::_('OSM_PAYMENT_PLUGINS'));
                }

                if ($user->authorise('core.admin', 'com_osmembership'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=language', 'icon-48-language.png', JText::_('OSM_TRANSLATION'));
                }

                if ($user->authorise('membershippro.subscriptions', 'com_osmembership'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&task=subscription.export', 'icon-48-export.png', JText::_('OSM_EXPORT_SUBSCRIBERS'));
                }

                if (OSMembershipHelper::canAccessThisView('countries'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=countries', 'icon-48-countries.png', JText::_('OSM_COUNTRIES'));
                }

                if (OSMembershipHelper::canAccessThisView('states'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership&view=states', 'icon-48-states.png', JText::_('OSM_STATES'));
                }

                if ($user->authorise('core.admin', 'com_osmembership'))
                {
                    $this->quickiconButton('index.php?option=com_osmembership', 'icon-48-download.png', JText::_('OSM_UPDATE_CHECKING'), 'update-check');
                }
                ?>
            </div>
        </div>
        <div class="<?php echo $bootstrapHelper->getClassMapping('span6'); ?>">
            <?php
            echo JHtml::_('bootstrap.startAccordion', 'statistics_pane', array('active' => 'statistic'));
            echo JHtml::_('bootstrap.addSlide', 'statistics_pane', JText::_('OSM_STATISTICS'), 'statistic');
            echo $this->loadTemplate('statistics');
            echo JHtml::_('bootstrap.endSlide');
            echo JHtml::_('bootstrap.addSlide', 'statistics_pane', JText::_('OSM_LASTEST_SUBSCRIPTIONS'), 'subscriptions');
            echo $this->loadTemplate('subscriptions');
            echo JHtml::_('bootstrap.endSlide');

            if ($user->authorise('core.admin', 'com_osmembership'))
            {
	            echo JHtml::_('bootstrap.addSlide', 'statistics_pane', JText::_('OSM_USEFUL_LINKS'), 'links_panel');
	            echo $this->loadTemplate('useful_links');
	            echo JHtml::_('bootstrap.endSlide');
            }

            echo JHtml::_('bootstrap.endAccordion');
            ?>
        </div>
    </div>
	<input type="hidden" name="option" value=""/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>

<style>
	#statistics_pane {
		margin: 0px !important
	}
</style>

<?php
	if ($user->authorise('core.admin', 'com_osmembership'))
	{
	?>
		<script type="text/javascript">
			var upToDateImg = '<?php echo JUri::base(true) . '/components/com_osmembership/assets/icons/icon-48-jupdate-uptodate.png' ?>';
			var updateFoundImg = '<?php echo JUri::base(true) . '/components/com_osmembership/assets/icons/icon-48-jupdate-updatefound.png';?>';
			var errorFoundImg = '<?php echo JUri::base(true) . '/components/com_osmembership/assets/icons/icon-48-deny.png';?>';
			jQuery(document).ready(function () {
				jQuery.ajax({
					type: 'GET',
					url: 'index.php?option=com_osmembership&task=check_update',
					dataType: 'json',
					success: function (msg, textStatus, xhr) {
						if (msg.status == 1) {
							jQuery('#update-check').find('img').attr('src', upToDateImg).attr('title', msg.message);
							jQuery('#update-check').find('span').text(msg.message);
						}
						else if (msg.status == 2) {
							jQuery('#update-check').find('img').attr('src', updateFoundImg).attr('title', msg.message);
							jQuery('#update-check').find('a').attr('href', 'index.php?option=com_installer&view=update');
							jQuery('#update-check').find('span').text(msg.message);
						}
						else {
							jQuery('#update-check').find('img').attr('src', errorFoundImg);
							jQuery('#update-check').find('span').text('<?php echo JText::_('OSM_UPDATE_CHECKING_ERROR'); ?>');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						jQuery('#update-check').find('img').attr('src', errorFoundImg);
						jQuery('#update-check').find('span').text('<?php echo JText::_('OSM_UPDATE_CHECKING_ERROR'); ?>');
					}
				});
			});
		</script>
	<?php
	}
?>