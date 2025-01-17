<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
*
*
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
*
*/
defined ('_JEXEC') or die('Restricted access');
jimport( 'joomla.access.access' );
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$dispatcher	= JDispatcher::getInstance();
?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
			<div class="row-fluid">
			<div class="span12 form-horizontal">
				<fieldset class="adminform">	
				
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#details" data-toggle="tab"><?php echo empty($this->category->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?></a>
					</li>
					<li>
						<a href="#custom_fields" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_CUSTOM_FIELDS');?></a>
					</li>
					<li>
						<a href="#a_restrictions" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_ACCESS_RESTRICTIONS');?></a>
					</li>
					<li>
						<a href="#publishing" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING');?></a>
					</li>
					<li>
						<a href="#images" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_NAME');?></div>
							<div class="controls">
								<input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->category->name; ?>" />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ALIAS');?></div>
							<div class="controls">
								<input class="text_area" type="text" name="alias" id="alias" size="50" maxlength="250" value="<?php echo $this->category->alias; ?>" />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE');?><br />(11.22)</div>
							<div class="controls">
								<input class="text_area" type="text" name="price" id="price" size="20" maxlength="250"
								<?php if($this->category->id>0 && $this->category->price==0){ echo 'disabled="true"'; }?>
								value="<?php echo $this->category->price/100; ?>" />
								<input onchange="freeprice();" type="checkbox" value="1" name="price_free" id="price_free" <?php if($this->category->id>0 && $this->category->price==0){ echo 'checked'; }?> />
								<span style="margin-top:3px;display:inline-block;"><?php echo JText::_('COM_DJCLASSIFIEDS_FREE');?></span>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_POINTS');?></div>
							<div class="controls">
								<input class="text_area" type="text" name="points" id="points" size="20" maxlength="250" value="<?php echo $this->category->points; ?>" />
							</div>
						</div>
						<?php
							$plugin_fields = $dispatcher->trigger('onAdminCategoryEditFields', array ($this->category));
							if(count($plugin_fields)){
								foreach($plugin_fields as $plugin_field){
									echo $plugin_field;
								}
							}
						?>	
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PARENT_CATEGORY');?></div>
							<div class="controls">
								<?php
										$optionss = array();
										$optionss=DJClassifiedsCategory::getCatSelect();
										if($this->category->id>0){
											$cat_list= DJClassifiedsCategory::getSubCat($this->category->id);
											$cat_list_assoc = array();
											$cat_list_assoc[$this->category->id]=1;
											foreach($cat_list as $cl){
												$cat_list_assoc[$cl->id]=1;
											}
											foreach($optionss as $op){
												if(isset($cat_list_assoc[$op->value])){
													$op->disable=1;
												}
											}
										}
										
										$main_tab = array();
										$main_tab[0]= JHTML::_('select.option', '0', JText::_('COM_DJCLASSIFIEDS_MAIN_CATEGORY'));
										$options = array();
										$options = array_merge_recursive ($main_tab, $optionss);
										//print_r($options);die();
										echo JHTML::_('select.genericlist', $options, 'parent_id', null, 'value', 'text', $this->category->parent_id);
									?>								
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PUBLISHED');?></div>
							<div class="controls">
								<input autocomplete="off" type="radio" name="published" value="1" <?php  if($this->category->published==1 || $this->category->id==0){echo "checked";}?> /><span style="float:left; margin:5px 10px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
								<input autocomplete="off" type="radio" name="published" value="0" <?php  if($this->category->published==0 && $this->category->id>0){echo "checked";}?> /><span style="float:left; margin:5px 10px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ADDING_ADVERTS');?></div>
							<div class="controls">
				          	    <select name="ads_disabled">
			               	    	<option value="0" <?php if($this->category->ads_disabled=='0'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASSIFIEDS_YES_ALLOW_ADDING_ADVERTS_TO_THIS_CATEGORY');?></option>
									<option value="1" <?php if($this->category->ads_disabled=='1'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASSIFIEDS_NO_DISABLE_ADDING_ADVERTS_TO_THIS_CATEGORY');?></option>
			               	    </select>                  
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_AUTOPUBLISH');?></div>
							<div class="controls">
				          	    <select name="autopublish">
			               	    	<option value="0" <?php if($this->category->autopublish=='0'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASSIFIEDS_GLOBAL');?></option>
									<option value="1" <?php if($this->category->autopublish=='1'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASSIFIEDS_YES');?></option>
									<option value="2" <?php if($this->category->autopublish=='2'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASSIFIEDS_NO');?></option>
			               	    </select>                  
							</div>
						</div>
						<div class="control-group">												
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_THEME');?></div>
							<div class="controls">
				          	    <select name="theme">
				          	    	<option value="" <?php if($this->category->theme==''){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASIFIEDS_DEFAULT_INHERIT');?></option>
				          	    	<?php 						
										if (is_dir(JPATH_COMPONENT_SITE.DS.'themes'.DS)) {
											if ($dh = opendir(JPATH_COMPONENT_SITE.DS.'themes'.DS)) {
												while (($file = readdir($dh)) !== false) {
													if(filetype(JPATH_COMPONENT_SITE.DS.'themes'.DS. $file)=='dir' && $file!="." && $file!=".." && $file!="default"){
														echo "filename: $file : filetype: " . filetype(JPATH_COMPONENT_SITE.'/'.'themes/' . $file) . "<br />";
														echo '<option value="'.$file.'" ';
															if($this->category->theme==$file){echo 'selected';}
														echo ' >'.$file.'</option>';
													}
												}
												closedir($dh);								
											}
										}								
									?>
			               	    </select>                  
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_SCHEME_TYPE');?></div>
							<div class="controls">
								<?php if(!$this->category->schema_type){
									$this->category->schema_type = 'Product';
								}?>
								<input class="text_area" type="text" name="schema_type" id="schema_type" size="20" maxlength="250" value="<?php echo $this->category->schema_type; ?>" />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION');?></div>
							<div class="controls">
		            	    	<?php 
								 	jimport( 'joomla.html.editor' );
									$editor = JFactory::getEditor();
									echo $editor->display( 'description', $this->category->description, '100%', '350', '50', '20',false );
								 ?> 								
							</div>
						</div>						
						<div class="control-group">
							<div class="control-label"></div>
							<div class="controls">
								
							</div>
						</div>
					</div>
					<div class="tab-pane" id="custom_fields">
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_CUSTOM_FIELDS');?></div>
							<div class="controls">
								<?php 
								if($this->category->id>0){
									if(count($this->fields)>0){
										echo '<table class="table table-striped" width="50%">';
											echo "<thead><tr><th>".JText::_('COM_DJCLASSIFIEDS_NAME')."</th><th>".JText::_('COM_DJCLASSIFIEDS_LABEL')."</th><th>".JText::_('COM_DJCLASSIFIEDS_TYPE')."</th>";
										echo '</tr></thead>';
										
										foreach($this->fields as $f){
											echo '<tr><td>'.$f->name.'</td><td>'.$f->label.'</td><td>'.$f->type.'</td></tr>';
										}
										echo '</table>';
										
									}else{
										echo JText::_('COM_DJCLASSIFIEDS_NO_CUSTOM_FIELDS');
									}
									echo '<br /><a href="index.php?option=com_djclassifieds&view=fieldsxref&id='.$this->category->id.'&tmpl=component" class="modal" rel="{handler: \'iframe\', size: {x: 800, y: 450},onClose:function(){window.parent.document.location.reload(true);}}">'.JText::_('COM_DJCLASSIFIEDS_MANAGE_CUSTOM_FIELDS').'</a>';
								}else{
									echo JText::_('COM_DJCLASSIFIEDS_PLEASE');
									echo ' <button style="float:none" onclick="save_to_manage();">'.JText::_('COM_DJCLASSIFIEDS_save').'</button>';
									echo JText::_('COM_DJCLASSIFIEDS_CATEGORY_TO_MANAGE_FIELDS');
								}
								?> 
							</div>
						</div>
					</div>						
					<div class="tab-pane" id="a_restrictions">
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ACCESS_ONLY_FOR_18_YEARS_OLD');?></div>
							<div class="controls">
								<select name="restriction_18" autocomplete="off">
	                	    		<option value="0" <?php if($this->category->restriction_18=='0'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASIFIEDS_DEFAULT_INHERIT');?></option>
									<option value="1" <?php if($this->category->restriction_18=='1'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASIFIEDS_RESTRICTED');?></option>
	                	   		</select>  
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ACCESS_RESTRICTIONS_VIEWING');?></div>
							<div class="controls">
							   <?php  echo JHTML::_('select.genericlist', $this->view_levels, 'access_view', null, 'id', 'title', $this->category->access_view); ?>  
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ACCESS_RESTRICTIONS_VIEWING_ADVERT_DETAILS');?></div>
							<div class="controls">
							   <?php  echo JHTML::_('select.genericlist', $this->view_levels, 'access_item_view', null, 'id', 'title', $this->category->access_item_view); ?>  
							</div>
						</div>					
					
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ACCESS_RESTRICTIONS_ADDING');?></div>
							<div class="controls">
							   <?php 
								if($this->category->id>0){ ?>
										<div>
											<select name="access" onchange="changeAccess(this.value)" autocomplete="off">
			                	    			<option value="0" <?php if($this->category->access=='0'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASIFIEDS_DEFAULT_INHERIT');?></option>
												<option value="1" <?php if($this->category->access=='1'){echo 'selected';}?> ><?php echo JText::_('COM_DJCLASIFIEDS_RESTRICTED');?></option>
			                	   			</select>
			                	   			<div style="clear:both"></div>
			                	   		</div>
		                	    	<?php
									 if($this->category->access){$st= "";}else{ $st='display:none;'; } ?>  
										<div id="group_box"  style="<?php echo $st;?>" ><br />
											<?php
											echo JText::_('COM_DJCLASSIFIEDS_AVAILABLE_ONLY_FOR_SELECTED_GROUPS'); ?>:<br />
											<select name="cat_groups[]" autocomplete="off" multiple="true" size="12">
												<?php 
												foreach($this->groups as $group){
													if($group->active){
														$sel=' SELECTED ';
													}else{$sel='';}
													echo '<option '.$sel.' value="'.$group->id.'">'.$group->title.'</option>';											
												}										
												?>
			                	   			</select>
											
										</div>
								<?php }else{
									echo JText::_('COM_DJCLASSIFIEDS_PLEASE');
									echo ' <button style="float:none" onclick="save_to_manage();">'.JText::_('COM_DJCLASSIFIEDS_save').'</button>';
									echo JText::_('COM_DJCLASSIFIEDS_CATEGORY_TO_SET_ACCESS_RESTRICTIONS');
									echo '<input type="hidden" name="access" value="0" />';
								}
								?>  
							</div>
						</div>						
					</div>									
					<div class="tab-pane" id="publishing">
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_META_TITLE');?></div>
							<div class="controls">
								<input class="text_area" type="text" name="metatitle" id="metatitle" size="50" maxlength="250" value="<?php echo $this->category->metatitle; ?>" />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_METAKEY');?></div>
							<div class="controls">
		                	    <textarea id="metakey" name="metakey" rows="5" cols="55" class="inputbox"><?php echo $this->category->metakey; ?></textarea>								
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_METADESC');?></div>
							<div class="controls">
								<textarea id="metadesc" name="metadesc" rows="5" cols="55" class="inputbox"><?php echo $this->category->metadesc; ?></textarea>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_METATAG_ROBOTS');?></div>
							<div class="controls">
								<select name="metarobots" autocomplete="off">
                	    			<option value="" ><?php echo JText::_('COM_DJCLASIFIEDS_DEFAULT_INHERIT');?></option>
									<option value="INDEX, FOLLOW" <?php if($this->category->metarobots=='INDEX, FOLLOW'){echo 'selected';}?> >INDEX, FOLLOW</option>
									<option value="NOINDEX, FOLLOW" <?php if($this->category->metarobots=='NOINDEX, FOLLOW'){echo 'selected';}?> >NOINDEX, FOLLOW</option>
									<option value="INDEX, NOFOLLOW" <?php if($this->category->metarobots=='INDEX, NOFOLLOW'){echo 'selected';}?> >INDEX, NOFOLLOW</option>
									<option value="NOINDEX, NOFOLLOW" <?php if($this->category->metarobots=='NOINDEX, NOFOLLOW'){echo 'selected';}?> >NOINDEX, NOFOLLOW</option>
                	   			</select>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="images">
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ICON');?></div>
							<div class="controls">
			                    <?php
									if(!$this->images){
										echo JText::_('COM_DJCLASSIFIEDS_NO_ICON_INCLUDED');
									}else{
										$icon_img = $this->images[0];
										echo '<img src="'.JURI::root().$icon_img->path.$icon_img->name.'_ths.'.$icon_img->ext.'" />';?>
										<input type="checkbox" name="del_icon" id="del_icon" value="<?php echo $icon_img->id;?>"/>
										<input type="hidden" name="del_icon_id" value="<?php echo $icon_img->id;?>"/>
										<input type="hidden" name="del_icon_path" value="<?php echo $icon_img->path;?>"/>
										<input type="hidden" name="del_icon_name" value="<?php echo $icon_img->name;?>"/>
										<input type="hidden" name="del_icon_ext" value="<?php echo $icon_img->ext;?>"/>
										<?php echo JText::_('COM_DJCLASSIFIEDS_CHECK_TO_DELETE'); 
									}
								?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo JText::_('COM_DJCLASSIFIEDS_ADD_ICON');?><br />
			                    <?php echo JText::_('COM_DJCLASSIFIEDS_NEW_IMAGES_OVERWRITE_EXISTING_ONE');?>
			                </div>
							<div class="controls">
								<input type="file"  name="icon" />
							</div>
						</div>
						<?php /* if($par->get('include_awesome_font','1')){ ?>														
							<div class="control-group">
								<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_OR_SELECT_ICON');?></div>
								<div class="controls">
								<i class="fa fa-check"></i>
									<?php echo DJClassifiedsTheme::getFontsAwesomeSelect(); ?>
								</div>
							</div>
						<?php } */ ?>
					</div>
				</div>
				</fieldset>
				</div>
				<input type="hidden" name="id" value="<?php echo $this->category->id; ?>" />
				<input type="hidden" name="ordering" value="<?php echo $this->category->ordering; ?>" />
				<input type="hidden" name="option" value="com_djclassifieds" />
				<input type="hidden" name="task" value="category" />
				<input type="hidden" name="boxchecked" value="0" />
			</div>
		</form>
	<script type="text/javascript">
	function freeprice(){
		if(document.getElementById('price_free').checked){
			document.getElementById('price').value='0';
			document.getElementById('price').disabled="true";
		}else{
			document.getElementById('price').disabled="";
		}
	}
	
	function save_to_manage(){
		Joomla.submitform('category.apply', document.getElementById('adminForm'));
	}
	
	function changeAccess(v){
		if(v=='1'){
			$('group_box').setStyle("display","");
		}else{
			$('group_box').setStyle("display","none");
		}
	}	
	</script>
	<?php echo DJCFFOOTER; ?>