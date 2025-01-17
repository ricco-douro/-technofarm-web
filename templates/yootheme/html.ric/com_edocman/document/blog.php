<?php
/**
 * @version        1.11.3
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011-2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
JHtml::_('behavior.modal', 'a.edocman-modal');

/* @var JDocumentHtml $document */
$document					= JFactory::getDocument();
$rootUri					= JUri::root();
$largeImageUri				= '';
$item						= $this->item ;

//og:title
$document->setMetaData('og:title', $item->title, 'property');

//og:image
if ($item->image && JFile::exists(JPATH_ROOT.'/media/com_edocman/document/thumbs/'.$item->image))
{
	$imgSrc					= $rootUri.'media/com_edocman/document/thumbs/'.$item->image ;
}

if ($imgSrc)
{
	$document->setMetaData('og:image', $imgSrc, 'property');
}
$document->setMetaData('og:url', JUri::getInstance()->toString(), 'property');

//og:description
$description				= !empty($item->metadesc) ? $item->metadesc : $item->metadesc;
$description				= JHtml::_('string.truncate', $description, 200, true, false);
$document->setMetaData('og:description', $description, 'property');

//og:site name
$document->setMetaData('og:site_name', JFactory::getConfig()->get('sitename'), 'property');


$config						= $this->config;
$session					= JFactory::getSession();
if(!$config->onetime_collect){
	$session->set('name','');
	$session->set('email','');
}
$name						= $session->get('name','');
$email						= $session->get('email','');

$url						= JRoute::_(EDocmanHelperRoute::getDocumentRoute($item->id, $this->categoryId, $this->Itemid), false);
$siteUrl					= JUri::base();
$socialUrl					= JUri::getInstance()->toString();
$user						= JFactory::getUser() ;
$userId						= $user->get('id');
$canDownload				= $user->authorise('edocman.download', 'com_edocman.document.'.$item->id) ;
$canEdit					= $user->authorise('core.edit',		'com_edocman.document.'.$item->id);
$canDelete					= $user->authorise('core.delete',		'com_edocman.document.'.$item->id);
if(!$canDelete)
{
	$canDelete				= $user->authorise('edocman.deleteown',			'com_edocman.document.'.$item->id) && ($item->created_user_id == $userId);
}
$canCheckin					= $user->authorise('core.admin',        'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
$canEditOwn					= $user->authorise('core.edit.own',		'com_edocman.document.'.$item->id) && $item->created_user_id == $userId;
$canChange					= $user->authorise('core.edit.state',	'com_edocman.document.'.$item->id) && $canCheckin;
$canDownload				= ($user->authorise('core.admin',       'com_config') ||$item->created_user_id == $userId) || ($item->user_ids =="" && ($canDownload || $canEdit) || ($item->user_ids != '' && in_array($userId, explode(',', $item->user_ids)))) ;
$bootstrapHelper			= $this->bootstrapHelper;
$btnClass					= $bootstrapHelper->getClassMapping('btn');
$rowFluidClass				= $bootstrapHelper->getClassMapping('row-fluid');
$span12Class				= $bootstrapHelper->getClassMapping('span12');
require_once JPATH_ROOT.'/components/com_edocman/helper/file.class.php' ;
$accept_license				= 0;
if($config->accept_license && $this->default_license > 0)
{
	$accept_license			= 1;
}
$hide_download_button		= $hide_download_button;
$hide_download = $this->category->hide_download;
if((int)$hide_download == 1){
	$hide_download_button	= 1;
}
elseif((int)$hide_download == 2)
{
	$hide_download_button	= 0;
}
$show_view_button			= $this->config->show_view_button;
$show_view					= $this->category->show_view;
if((int)$show_view == 1)
{
	$show_view_button		= 1;
}
elseif((int)$show_view == 2)
{
	$show_view_button		= 0;
}

$item->data					= new EDocman_File($item->id,$item->filename, $config->documents_path);
if($item->filename != "")
{
	$fileExt				= strtolower(JFile::getExt($item->filename));
}
else
{
	$fileExt				= strtolower(JFile::getExt($item->document_url));
}
if ($config->collect_downloader_information && !$userId && ($name == '' || $email == ''))
{
	$showDownloadForm		= true;
}
else
{
	$showDownloadForm		= false;
}
?>


<div id="edocman-document-page-blog" class="edocman-container edocman-document">
	<div class="edocman-box-heading clearfix">
		<h1 class="edocman-page-heading pull-left">
			<?php
			if($config->show_icon_beside_title)
			{
			?>
            	<i class="<?php echo $item->data->fileicon; ?>"></i>
			<?php
			}
			?>
			<?php echo $item->title; ?>
			<?php
			if(!empty($item->indicators) || !empty($item->new_indicator) || !empty($item->update_indicator))
			{
				$indicators = $item->indicators;
			?>
			<span class="indicators">
				<?php
				if (!empty($item->new_indicator))
				{
				?>
					<span class="edocman_new">
							<?php echo JText::_('EDOCMAN_NEW');?>
					</span>
				<?php
				}elseif(!empty($item->update_indicator)){
					?>
					<span class="edocman_updated">
							<?php echo JText::_('EDOCMAN_UPDATED');?>
					</span>
				<?php
				}
				if(in_array('featured', $indicators))
				{
				?>
					<span class="edocman_featured">
							<?php echo JText::_('EDOCMAN_FEATURED');?>
					</span>
				<?php
				}
				if(in_array('hot', $indicators))
				{
				?>
					<span  class="edocman_hot">
						<?php echo JText::_('EDOCMAN_HOT');?>
					</span>
				<?php
				}
				?>
			</span>
			<?php
			}
			?>
		</h1>
		<div class="clearfix"></div>
		<p class="edocman_document_details_information">
			<?php
			if(($item->created_time > 0) && ($this->config->show_creation_date == 1)){
			?>
			<span class="created-on-label">
				<time datetime="<?php echo $item->created_time;?>" itemprop="datePublished"> <?php echo JText::_('EDOCMAN_PUBLISHED_ON');?> <?php echo JHTML::_('date',$item->created_time, 'j F Y');?> </time>
			</span>
			<?php
			}
			if(($item->created_user_id > 0) && ($this->config->show_creation_user == 1)){
			?>
				<span class="owner-label">
					<?php
					echo JText::_('EDOCMAN_BY');
					?>
					<span itemprop="author"><?php echo JFactory::getUser($item->created_user_id)->name;?></span>
				</span>
			<?php
			}
			if ($this->config->show_number_downloaded){
			?>
				<meta content="UserDownloads:<?php echo $item->downloads;?>" itemprop="interactionCount">
				<span class="hits-label"> <?php echo $item->downloads;?> <?php echo JText::_('EDOCMAN_DOWNLOADS');?> </span>
			<?php
			}
			?>
		</p>
	</div>
	<div id="edocman-document-details" class="edocman-description">
		<div class="edocman-description-details edocman-document-blog clearfix">
			<?php
			if($accept_license)
			{
				$disable_download_button = "downloaddisabled";
			}
			else
			{
				$disable_download_button = "";
			}
			if ($canDownload && $hide_download_button != 1)
			{
				$downloadUrl = JRoute::_('index.php?option=com_edocman&task=document.download&id='.$item->id.'&Itemid='.$this->Itemid) ;
				if ($showDownloadForm)
				{
				?>
					<div class="edocman-download-blog">
						<a data-toggle="modal" data-document-title="<?php echo $this->item->title; ?>" title="<?php echo JText::_('EDOCMAN_DOWNLOAD'); ?>"  id="<?php echo $this->item->id; ?>" class="email-popup edocman-document-title-link downloadbtn1 <?php echo $disable_download_button;?> edocmandownloadlink" href="#form-content" target="<?php echo $target;?>">
							<?php if($this->item->document_url != ""){
								if($config->external_download_link == 1){
									$target = "_blank";
								}else{
									$target = "";
								}
							?>
								<i class="edicon edicon-link"></i>
								<?php echo JText::_('EDOCMAN_OPEN_DOCUMENT'); ?>
							<?php }else{ ?>
								<i class="edicon edicon-cloud-download"></i>
								<?php echo JText::_('EDOCMAN_DOWNLOAD'); ?>
							<?php } ?>
							<div class="clearfix"></div>
							<span class="documentstatistic">
							(
								<?php
								echo $fileExt;
								?>
								<?php
								if(trim($item->data->size) != "")
								{
								?>
								,
								<?php echo $item->data->size;
								}
								?>
							)
							</span>
						</a>
					</div>
				<?php
				}
				else
				{
				?>
					<div class="edocman-download-blog">
						<a href="<?php echo $downloadUrl; ?>" class="edocmandownloadlink downloadbtn1 <?php echo $disable_download_button;?>" target="<?php echo $target;?>">
							<?php if($this->item->document_url != ""){?>
								<i class="edicon edicon-link"></i>
								<?php echo JText::_('EDOCMAN_OPEN_DOCUMENT'); ?>
							<?php }else{ ?>
								<i class="edicon edicon-cloud-download"></i>
								<?php echo JText::_('EDOCMAN_DOWNLOAD'); ?>
							<?php } ?>
							<div class="clearfix"></div>
							<span class="documentstatistic">
							(
								<?php
								echo $fileExt;
								?>
								<?php
								if(trim($item->data->size) != "")
								{
								?>
								,
								<?php echo $item->data->size;
								}
								?>
							)
							</span>
						</a>
					</div>
				<?php
				}
			}elseif(!$canDownload && $hide_download_button != 1 && $this->config->login_to_download && (int)$userId == 0){
				?>
				<div class="edocman-download-blog">
					<a data-toggle="modal" class="edocmandownloadlink email-popup <?php echo $disable_download_button;?> downloadbtn1" href="#login-form">
						<span class="edocman_download_label">
                            <i class="edicon edicon-user"></i>
							<?php
							echo JText::_('EDOCMAN_LOGIN_TO_DOWNLOAD'); ?>
						</span>
					</a>
				</div>
				<?php
			}
			?>
			<?php
				if ($item->image && JFile::exists(JPATH_ROOT.'/media/com_edocman/document/thumbs/'.$item->image))
				{
					$modal_url = $imgSrc = JUri::base().'media/com_edocman/document/'.$item->image ;
					$imgSrc = JUri::base().'media/com_edocman/document/thumbs/'.$item->image ;
				}
				else
				{
					$modal_url = "";
					if (!isset($this->config->show_default_document_thumbnail) || $this->config->show_default_document_thumbnail)
					{
						$ext = strtolower(EDocmanHelper::getFileExtension($item)) ;
						if (JFile::exists(JPATH_ROOT.'/components/com_edocman/assets/images/icons/32x32/'.$ext.'.png'))
						{
							$imgSrc = JUri::base().'components/com_edocman/assets/images/icons/32x32/'.$ext.'.png' ;
						}
						else
						{
							$imgSrc = JUri::base().'components/com_edocman/assets/images/icons/32x32/generic.png';
						}
					}
					else
					{
						$imgSrc = '' ;
					}
				}
				if ($imgSrc)
				{
					if($modal_url != ""){
						?>
						<a href="<?php echo $modal_url?>" class="edocman-modal edocman_thumbnail thumbnail">
						<?php
					}
					?>
						<img src="<?php echo $imgSrc; ?>" alt="<?php echo $item->title; ?>" class="edocman-thumb-left" />
					<?php
					if($modal_url != ""){
						?>
						</a>
						<?php
					}
				}
				//echo $item->short_description;
				$item->description = JHtml::_('content.prepare', $item->description);
				echo $item->description ;
			?>
		</div>

    <?php
    //show plugin
    foreach ($this->plugins as $plugin)
    {
        ?>
        <h3><?php echo $plugin['title']; ?></h3>
        <?php
        echo $plugin['form'];
    }
    ?>


	<?php
	if ($item->tags)
	{
		$tags = explode(',', $item->tags);
	?>
		<ul class="edocman_tag_container clearfix">
			<?php
			foreach ($tags as $tag)
			{
			?>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_edocman&view=search&filter_tag='.$tag.'&Itemid='.$this->Itemid); ?>" title="<?php echo $tag; ?>"><?php echo $tag; ?></a>
				</li>
			<?php
			}
			?>
		</ul>
	<?php
	}

	if ($this->default_license > 0)
	{
	?>
		<div class="<?php echo $bootstrapHelper->getClassMapping('row-fluid'); ?>">
			<div class="<?php echo $bootstrapHelper->getClassMapping('span12'); ?> licensebox">
				<strong><?php echo JText::_('EDOCMAN_LICENSE');?></strong>
				<div class="clearfix"></div>
				<?php
					echo $this->license->description;
				?>
			</div>
		</div>
		<?php
		if($accept_license){
		?>
		<div class="<?php echo $bootstrapHelper->getClassMapping('row-fluid'); ?>">
			<div class="<?php echo $bootstrapHelper->getClassMapping('span12'); ?>">
				<input type="checkbox" name="agreeterm" id="agreeterm" value="0" onClick="javascript:agreeTerm();"/>
				<?php echo JText::_('EDOCMAN_AGREE_TERM_AND_CONDITION');?>
			</div>
		</div>
		<?php } ?>
	<?php
	}

	if ($this->showTaskBar)
	{
		if($this->item->document_url != "")
		{
			if($config->external_download_link == 1)
			{
				$target = "_blank";
			}
			else
			{
				$target = "_self";
			}
		}
		else
		{
			$target = "_self";
		}
		?>
		<div class="edocman-taskbar clearfix">
			<ul>
				<?php
				

				if ($canDownload && $show_view_button == 1 && $item->canView)
				{
					$playextension = array('mp4','flv','mp3','ogg','ogv');
					$audio_array = array('mp3','ogg');
					$frame_array = array('flv');
					$ext = \Joomla\String\StringHelper::strtolower(EDocmanHelper::getFileExtension($item)) ;
					if(in_array($ext,$playextension) && !EDocmanHelper::isDropBoxTurnedOn() && !EDocmanHelper::isAmazonS3TurnedOn()){
						$viewUrl = JUri::root()."index.php?option=com_edocman&view=play&id=".$item->id."&tmpl=component";
						if(in_array($ext,$audio_array)){
							$audio_player = "rel=\"{handler: 'iframe', size: {x: 300, y: 50}, iframeOptions: {scrolling: 'no'}}\"";
						}elseif(in_array($ext,$frame_array)){
							$audio_player = "rel=\"{handler: 'iframe', size: {x: 450, y: 300}, iframeOptions: {scrolling: 'no'}}\"";
						}else{
							$audio_player = "";
						}
						?>
						<li>
							<?php
							if($config->view_option == 0){
							?>
								<a href="<?php echo $viewUrl; ?>" class="<?php echo $btnClass; ?> edocman-modal" data-toggle="modal" <?php echo $audio_player;?>>
									<i class="edicon edicon-eye"></i>
									<?php echo JText::_('EDOCMAN_VIEW'); ?>
								</a>
							<?php
							}else{
							?>
								<a href="<?php echo $viewUrl; ?>" class="<?php echo $btnClass; ?>" target="_blank" data-toggle="modal" <?php echo $audio_player;?>>
									<i class="edicon edicon-eye"></i>
									<?php echo JText::_('EDOCMAN_VIEW'); ?>
								</a>
							<?php
							}
							?>

						</li>
						<?php
					}else {
						$viewUrl = JRoute::_('index.php?option=com_edocman&task=document.viewdoc&id=' . $item->id . '&Itemid=' . $this->Itemid);
						?>
						<li>
							<a href="<?php echo $viewUrl; ?>" target="_blank" class="<?php echo $btnClass; ?>">
								<i class="edicon edicon-eye"></i>
								<?php echo JText::_('EDOCMAN_VIEW'); ?>
							</a>
						</li>
						<?php
					}
				}
				//share document
				if($this->config->turn_on_sharing)
				{
				?>
					<li>
						<a data-toggle="modal" class="email-popup btn" href="#sharing-form" data-document-title="<?php echo $this->item->title; ?>" data-original-title="<?php echo JText::_('EDOCMAN_SHARE_DOCUMENT'); ?>"  id="<?php echo $this->item->id; ?>" >
							<i class="edicon edicon-share"></i>
							<?php
							echo JText::_('EDOCMAN_SHARE_DOCUMENT');
							?>
						</a>
					</li>
				<?php
				}
                if($this->config->show_bookmark_button)
                {
                    ?>
                    <li>
                        <a href="javascript:void(0);" onclick="javascript:addBookmark('<?php echo JUri::root()?>',<?php echo $this->item->id?>);" class="btn" href="#sharing-form" data-document-title="<?php echo JText::_('EDOCMAN_BOOKMARK_THIS_FILE');?>" data-original-title="<?php echo JText::_('EDOCMAN_BOOKMARK_THIS_FILE');?>"  id="<?php echo $this->item->id; ?>" >
                            <i class="edicon edicon-bookmark"></i>
                            <?php
                            echo JText::_('EDOCMAN_BOOKMARK');
                            ?>
                        </a>
                    </li>
                    <?php
                }
				//end share document
				$pass_lock = true;
				if($config->lock_function)
				{ //lock function is turned on
					if(($item->locked_by != $user->id) && ($item->is_locked == 1))
					{
						$pass_lock = false;
					}
				}
				if (($canEdit || $canEditOwn) && ($pass_lock))
				{
					$url = JRoute::_('index.php?option=com_edocman&task=document.edit&id='.$item->id.'&Itemid='.$this->Itemid) ;
					?>
					<li>
						<a href="<?php echo $url; ?>" class="<?php echo $btnClass; ?>">
							<i class="edocman-icon-pencil"></i>
							<?php echo JText::_('EDOCMAN_EDIT'); ?>
						</a>
					</li>
					<?php
				}
				if ($canDelete)
				{
				?>
					<li>
						<a href="javascript:deleteConfirm();" class="<?php echo $btnClass; ?>">
							<i class="edocman-icon-trash"></i>
							<?php echo JText::_('EDOCMAN_DELETE'); ?>
						</a>
					</li>
				<?php
				}
				if ($canChange)
				{
					if ($item->published)
					{
						$text = JText::_('EDOCMAN_UNPUBLISH');
						$url = "javascript:publishConfirm('documents.unpublish')";
						$class = 'edocman-icon-remove';
					}
					else
					{
						$url = "javascript:publishConfirm('documents.publish')";
						$text = JText::_('EDOCMAN_PUBLISH');
						$class = 'edocman-icon-ok';
					}
					?>
					<li>
						<a href="<?php echo $url; ?>" class="<?php echo $btnClass; ?>">
							<i class="<?php echo $class; ?>"></i>
							<?php echo $text; ?>
						</a>
					</li>
				<?php
				}
				?>
			</ul>
		</div>
	<?php
	}

	if($this->config->show_related_documents && count($this->related_items) > 0)
	{
		echo $this->loadTemplate('relates');
	}

	if ($this->config->show_social_sharing_buttons !== '0')
	{
		?>
		<div class="<?php echo $rowFluidClass;?>">
			<div class="<?php echo $span12Class;?>">
				<?php
				$alt     = JText::sprintf('EDOCMAN_SUBMIT_ITEM_IN_SOCIAL_NETWORK', $item->title, 'FaceBook');
				echo '<a href="http://www.facebook.com/sharer.php?u=' . rawurlencode($socialUrl) . '&amp;t=' . rawurlencode($item->title) . '" title="' . $alt . '" target="blank" class="social_sharing_button" style="color:#3b5998">
						<i class="edicon edicon-facebook2"></i>
					  </a>';

				$alt     = JText::sprintf('EDOCMAN_SUBMIT_ITEM_IN_SOCIAL_NETWORK', $item->title, 'Twitter');
				echo '<a href="http://twitter.com/?status=' . rawurlencode($item->title . " " . $socialUrl) . '" title="' . $alt . '" target="blank" style="color:#55acee;" class="social_sharing_button">
                        <i class="edicon edicon-twitter"></i>
                    </a>';

				$alt     = JText::sprintf('EDOCMAN_SUBMIT_ITEM_IN_SOCIAL_NETWORK', $item->title, 'Google plus');
				echo '<a href="http://www.google.com/bookmarks/mark?op=edit&bkmk=' . rawurlencode($socialUrl) . '" title="' . $alt . '" target="blank" style="color:#dd4b39;" class="social_sharing_button">
                        <i class="edicon edicon-google-plus2"></i>
                        </a>';

				$alt     = JText::sprintf('EDOCMAN_SUBMIT_ITEM_IN_SOCIAL_NETWORK', $item->title, 'LinkedIn');
				echo '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . $socialUrl . '&amp;title=' . $item->title . '" title="' . $alt . '" target="_blank" style="color:#0976b4;" class="social_sharing_button"><i class="edicon edicon-linkedin"></i></a>';
				?>
			</div>
		</div>
		<?php
	}

	if (@$this->config->jcomment_integration && file_exists(JPATH_ROOT.'/components/com_jcomments/jcomments.php'))
	{
		require_once JPATH_ROOT.'/components/com_jcomments/jcomments.php';
		?>
		<div class="edocman-comments clearfix">
			<?php  echo JComments::showComments($item->id, 'com_edocman', $item->title); ?>
		</div>
		<?php
	}
	?>
	</div>
</div>

<form method="post" name="edocman_form" id="edocman_form" action="index.php">
	<input type="hidden" name="cid[]" value="<?php echo $item->id; ?>" id="document_id" />
	<input type="hidden" name="category_id" value="<?php echo $this->categoryId ; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
	<input type="hidden" name="option" value="com_edocman" />
	<?php echo JHtml::_('form.token'); ?>

	<script type="text/javascript">
		function deleteConfirm()
		{
			var msg = "<?php echo JText::_('EDOCMAN_DELETE_CONFIRM'); ?>";
			if (confirm(msg))
			{
				var form = document.edocman_form ;
				form.task.value = 'documents.delete';
				form.submit();
			}
		}
		function publishConfirm(task) {
			var msg;
			if (task == 'documents.publish')
			{
				msg = "<?php echo JText::_('EDOCMAN_PUBLISH_CONFIRM'); ?>";
			}
			else
			{
				msg = "<?php echo JText::_('EDOCMAN_UNPUBLISH_CONFIRM'); ?>";
			}
			if (confirm(msg))
			{
				var form = document.edocman_form ;
				form.task.value = task;
				form.submit();
			}
		}
		function agreeTerm(){
			var agreeTerm = document.getElementById('agreeterm');
			if(agreeTerm.value == "0"){
				agreeTerm.value = "1";
				jQuery(".edocmandownloadlink").removeClass("downloaddisabled");
			}else{
				agreeTerm.value = "0";
				jQuery(".edocmandownloadlink").addClass("downloaddisabled");
			}
		}
		function addBookmark(live_site, id)
        {
            jQuery.ajax({
                type: 'POST',
                url: live_site + 'index.php?option=com_edocman',
                data: 'task=document.bookmark&id=' + id + '&tmpl=component',
                dataType: 'json',
                success: function(response)
                {
                    alert(response.result);
                }
            });
        }
	</script>
</form>
<?php
if( $this->config->turn_on_sharing){
	echo EDocmanHelperHtml::loadCommonLayout('common/sharing.php', array('bootstrapHelper' => $bootstrapHelper));
}

if ($showDownloadForm)
{
	echo EDocmanHelperHtml::loadCommonLayout('common/modal.php', array('bootstrapHelper' => $bootstrapHelper,'config' => $config));
}
if(!$canDownload && $hide_download_button != 1 && $this->config->login_to_download && (int)$userId == 0){
	echo EDocmanHelperHtml::loadCommonLayout('common/login.php', array('bootstrapHelper' => $bootstrapHelper));
}
?>