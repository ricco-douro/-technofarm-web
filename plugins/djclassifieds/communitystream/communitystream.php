<?php
/**
* @version 2.0
* @package DJ Classifieds
* @subpackage DJ Classifieds Component
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.plugin.plugin');

require_once (JPATH_ROOT . '/components/com_community/libraries/core.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_djclassifieds/lib/djimage.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_djclassifieds/lib/djseo.php');

class plgDJClassifiedsCommunitystream extends JPlugin{

	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	public function onAfterDJClassifiedsSaveAdvert( &$row, $is_new ){

		$app	  = JFactory::getApplication();
		$db		  = JFactory::getDBO();
		$category = '';
		$catslug  = '0:all';		
		$pluginParams	= $this->params; 
								
		if($row->user_id && ($is_new==1 || $pluginParams->get('stream_on_edit', 0)==1)){			
			CFactory::load('libraries', 'activities');
			$actor = CFactory::getUser($row->user_id); 
			//echo '<pre>';print_R($actor);die();
			
			$act = new stdClass();
			$act->actor = $row->user_id;
			$act->target = 0;
			$act->title = '<a class="cStream-Author" href="' .CUrlHelper::userLink($actor->id).'">'.$actor->getDisplayName().'</a> ';
			
			if($row->cat_id){
				$query = "SELECT * FROM #__djcf_categories WHERE id='".$row->cat_id."' LIMIT 1";
			 	$db->setQuery($query);
			 	$category = $db->loadObject();	
				$catslug = $category->id.':'.$category->alias;
			}		 	
			$ad_link = DJClassifiedsSEO::getItemRoute($row->id.':'.$row->alias,$catslug);

			if($is_new){
				$act->cmd = 'djclassifieds.newadvert';
				$act->title .= JText::_('PLG_DJCLASSIFIEDS_COMMUNITYSTREAM_ADDED_ADVERT');
				$act->content = JText::_('PLG_DJCLASSIFIEDS_COMMUNITYSTREAM_NEW_ADVERT');	
			}else{
				$act->cmd = 'djclassifieds.editadvert';
				$act->title .= JText::_('PLG_DJCLASSIFIEDS_COMMUNITYSTREAM_EDITED_ADVERT');
				$act->content = JText::_('PLG_DJCLASSIFIEDS_COMMUNITYSTREAM_CHANGED_ADVERT');
			}
			
			$act->content .= ' <a href="'.$ad_link.'">'.$row->name.'</a><br />';
			$act->content .= $row->intro_desc;
			
			$item_imgs = DJClassifiedsImage::getAdsImages($row->id);	
																					
			if($item_imgs){
				$act->content .= '<br /><br />';				
				//echo '<pre>';print_r($item_imgs);die();
				for($i=0; $i<count($item_imgs); $i++){
					if($i==3){break;} 
					$act->content .= '<a style="margin-right:10px" href="'.$ad_link.'"><img src="'.JURI::base().$item_imgs[$i]->thumb_s.'" alt="" /></a>'; 
				}	
			}

			$act->app = 'djclassifieds';
			$act->cid = $row->id;
			$act->params = '';
			CActivityStream::add($act);
		}
	
		return true;
	}
	
}


