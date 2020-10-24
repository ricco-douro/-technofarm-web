<?php 
/** 
 * @package GDPR::CPANEL::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage cpanel
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<!-- CPANEL ICONS -->
<div class="row no-margin">
	<div class="accordion-group span5">
		<div class="accordion-heading opened">
			<div class="accordion-toggle accordion_lightblue noaccordion">
				<h3><span class="icon-pencil"></span><?php echo JText::_('COM_GDPR_CPANEL_TASKS' ); ?></h3>
			</div>
		</div>
		<div id="placeholder_cpanelicons" class="accordion-body accordion-inner collapse in">
			<div id="cpanel">
				<?php echo $this->icons; ?>
			
				<div id="updatestatus">
					<?php 
					if(is_object($this->updatesData)) {
						if(version_compare($this->updatesData->latest, $this->currentVersion, '>')) { ?>
							<a href="https://storejextensions.org/extensions/gdpr.html" target="_blank" alt="storejoomla link">
								<label data-content="<?php echo JText::sprintf('COM_GDPR_GET_LATEST', $this->currentVersion, $this->updatesData->latest, $this->updatesData->relevance);?>" class="label label-important hasPopover">
									<span class="icon-warning"></span>
									<?php echo JText::sprintf('COM_GDPR_OUTDATED', $this->updatesData->latest);?>
								</label>
							</a>
						<?php } else { ?>
							<label data-content="<?php echo JText::sprintf('COM_GDPR_YOUHAVE_LATEST', $this->currentVersion);?>" class="label label-success hasPopover">
								<span class="icon-checkmark"></span>
								<?php echo JText::sprintf('COM_GDPR_UPTODATE', $this->updatesData->latest);?>
							</label>	
						<?php }
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="accordion span7" id="gdpr_accordion_cpanel">
		<div class="accordion-group">
	    	<div class="accordion-heading">
	    		<div class="accordion-toggle" data-toggle="collapse" data-parent="#gdpr_accordion_cpanel" href="#gdpr_stats">
		      		<h4 class="accordion-title">
		      			<span class="icon-chart"></span>
		      			<?php echo JText::_('COM_GDPR_CPANEL_STATS');?>
	      			</h4>
	      		</div>
	    	</div>
	    	
	    	 <div id="gdpr_stats" class="accordion-body collapse">
				<div class="accordion-inner">
					<div class="single_stat_container">
						<div class="statcircle">
							<span class="icon-users icon-large"></span>
						</div>
						<ul class="subdescription_stats">
							<li class="es-stat-no"><?php echo $this->infodata['chart_gdpr_canvas']['new']; ?></li>
							<li class="es-stat-title"><?php echo JText::_('COM_GDPR_NEW_CHART');?></li>
						</ul>
					</div>
					
					<div class="single_stat_container">
						<div class="statcircle">
							<span class="icon-users icon-large"></span>
						</div>
						<ul class="subdescription_stats">
							<li class="es-stat-no"><?php echo $this->infodata['chart_gdpr_canvas']['deleted']; ?></li>
							<li class="es-stat-title"><?php echo JText::_('COM_GDPR_DELETED_CHART');?></li>
						</ul>
					</div>
					
					<div class="single_stat_container">
						<div class="statcircle">
							<span class="icon-users icon-large"></span>
						</div>
						<ul class="subdescription_stats">
							<li class="es-stat-no"><?php echo $this->infodata['chart_gdpr_canvas']['breached']; ?></li>
							<li class="es-stat-title"><?php echo JText::_('COM_GDPR_BREACHED_CHART');?></li>
						</ul>
					</div>
					
					<div class="chart_container">
						<canvas id="chart_gdpr_canvas"></canvas>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="accordion-group">
		    <div class="accordion-heading">
				<div class="accordion-toggle" data-toggle="collapse" data-parent="#gdpr_accordion_cpanel" href="#gdpr_status">
					<h4 class="accordion-title">
						<span class="icon-help"></span>
						<?php echo JText::_('COM_GDPR_ABOUT');?>
					</h4>
		      	</div>
	    	</div>
		    <div id="gdpr_status" class="accordion-body collapse">
		 		<div class="accordion-inner">
					<div class="single_container">
				 		<label class="label label-warning"><?php echo JText::_('COM_GDPR_CURRENT_VERSION') . $this->currentVersion;?></label>
			 		</div>
			 		
			 		<div class="single_container">
				 		<label class="label label-info"><?php echo JText::_('COM_GDPR_AUTHOR_COMPONENT');?></label>
			 		</div>
			 		
			 		<div class="single_container">
				 		<label class="label label-info"><?php echo JText::_('COM_GDPR_SUPPORTLINK');?></label>
			 		</div>
			 		
			 		<div class="single_container">
				 		<label class="label label-info"><?php echo JText::_('COM_GDPR_DEMOLINK');?></label>
			 		</div>
				</div>
		    </div>
	 	</div>
	</div>
</div>
<form name="adminForm" id="adminForm" action="index.php">
	<input type="hidden" name="option" value="<?php echo $this->option;?>"/>
	<input type="hidden" name="task" value=""/>
</form>