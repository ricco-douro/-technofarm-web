<?php
/**
 * @version         1.9.7
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011-2016 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
require_once JPATH_ROOT.'/components/com_edocman/helper/file.class.php' ;
$uploadItemId = EDocmanHelperRoute::findUploadMenuId($this->Itemid);
$bootstrapHelper = $this->bootstrapHelper;
$config = $this->config;
?>
<div id="edocman-category-page-default" class="edocman-container <?php echo $bootstrapHelper->getClassMapping('row-fluid'); ?>">
	<form method="post" name="edocman_form" id="edocman_form" action="<?php echo JRoute::_('index.php?option=com_edocman&view=userdocuments&Itemid='.$this->Itemid); ?>">
		<h1 class="edocman-page-heading" style="display: inline; margin-bottom: 10px;">
			<?php echo JText::_('EDOCMAN_MANAGE_DOCUMENTS'); ?>
			<span style="float: right;"><a href="<?php echo JRoute::_('index.php?option=com_edocman&view=document&layout=edit&Itemid=' . $uploadItemId); ?>" class="edocman_upload_link btn btn-success"><i class="edocman-icon-upload"></i><?php echo JText::_('EDOCMAN_UPLOAD'); ?></a></span>
		</h1>
		<?php
			if (count($this->items))
			{
			?>
				<table class="table table-striped table-bordered table-condensed" style="margin-top: 10px;" id="userdocumentstable">
					<thead>
						<tr>
							<th class="edocman-title-col">
								<?php echo JText::_('EDOCMAN_TITLE'); ?>
							</th>
							<th class="edocman-createddate-col center">
								<?php echo JText::_('EDOCMAN_CREATED_DATE'); ?>
							</th>
							<th class="edocman-filetype-col">
								<?php echo JText::_('EDOCMAN_FILE_TYPE'); ?>
							</th>
							<th class="edocman-filesize-col">
								<?php echo JText::_('EDOCMAN_FILESIZE'); ?>
							</th>
							<?php
							if($config->lock_function){
							?>
							<th class="edocman-lock-col">
							</th>
							<?php
							}
							?>
							<th class="center edocman-edit-delete"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total = 0 ;
						$activeItemid = $this->defaultItemid;
						$catId = 0;
						for ($i = 0 , $n = count($this->items) ; $i < $n; $i++)
						{
							$catId = $categoryId;
							$item = $this->items[$i] ;
							$item->data = new EDocman_File($item->id,$item->filename, $this->path);
							$Itemid = EDocmanHelperRoute::getDocumentMenuId($item->id, $catId, $activeItemid);
							$url = JRoute::_('index.php?option=com_edocman&view=document&id='.$item->id.'&catid='.$catId.'&Itemid='.$Itemid);
							?>
							<tr>
								<td data-label="<?php echo JText::_('EDOCMAN_TITLE'); ?>">
									<a href="<?php echo $url; ?>" target="_blank"><?php echo $item->title; ?></a>
									<?php
									if(($item->publish_down != "") && ($item->publish_down != "0000-00-00 00:00:00") && (strtotime($item->publish_down) < time())){
										?>
										<span class="expired"><?php echo JText::_('EDOCMAN_EXPIRED');?></span>
										<?php
									}
									?>
								</td>
								<td class="center" data-label="<?php echo JText::_('EDOCMAN_CREATED_DATE'); ?>">
									<?php echo JHtml::_('date', $item->created_time, $config->date_format, null); ?>
								</td>
								<td data-label="<?php echo JText::_('EDOCMAN_FILE_TYPE'); ?>">
									<?php echo $item->data->mime; ?>
								</td>
								<td data-label="<?php echo JText::_('EDOCMAN_FILESIZE'); ?>">
									<?php echo $item->data->size; ?>
								</td>
								<?php
								if($config->lock_function){
								?>
								<td data-label="">
									<?php
									if($item->is_locked){
										?>
										<span class="red"><?php echo JText::_('EDOCMAN_LOCKED');?></span>
										<?php
									}else{
										?>
										<span class="green"><?php echo JText::_('EDOCMAN_UNLOCKED');?></span>
										<?php
									}
									?>
								</td>
								<?php
								}
								?>
								<td class="center" data-label="<?php echo JText::_('EDOCMAN_EDIT'); ?>/ <?php echo JText::_('EDOCMAN_DELETE'); ?>">
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_edocman&task=document.edit&id='.$item->id.'&Itemid='.$Itemid) ; ?>">
										<i class="edocman-icon-pencil"></i>
									</a>
									<a class="btn" href="javascript:deleteConfirm(<?php echo $item->id; ?>);">
										<i class="edocman-icon-trash"></i>
									</a>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			<?php
			}
			else
			{
				echo JText::_('EDOCMAN_NO_DOCUMENT_UPLOADED_YET');
			}
			if ($this->pagination->total > $this->pagination->limit)
			{
				?>
				<div class="pagination">
					<?php echo $this->pagination->getPagesLinks(); ?>
				</div>
			<?php
			}

		?>
		<input type="hidden" name="cid[]" value="0" id="document_id" />
		<input type="hidden" name="category_id" value="0" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>

		<script type="text/javascript">
			function deleteConfirm(id)
			{
				var msg = "<?php echo JText::_('EDOCMAN_DELETE_CONFIRM'); ?>";
				if (confirm(msg))
				{
					var form = document.edocman_form ;
					form.task.value = 'documents.delete';
					document.getElementById('document_id').value = id;
					form.submit();
				}
			}
		</script>
	</form>
</div>