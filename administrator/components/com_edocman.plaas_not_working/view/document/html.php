<?php
/**
 * @version        1.9.7
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011 - 2018 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// No direct access
defined('_JEXEC') or die;

/**
 * View to edit
 */
class EdocmanViewDocumentHtml extends OSViewItem
{
	protected function prepareView()
	{
		parent::prepareView();
		$this->config       = EDocmanHelper::getConfig();
		$document           = JFactory::getDocument();
		$db                 = JFactory::getDbo();
		$query              = $db->getQuery(true);
		$query->select('count(extension_id)');
		$query->from('#__extensions');
		$query->where('`element` like "indexer" and `folder` like "edocman" and enabled=1');
		$db->setQuery($query);
		$count              = $db->loadResult();
		$row                = $this->item;
		//print_r($row);
		$ext                = strtolower(JFile::getExt($row->filename)) ;
		if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx')
		{
			if(($count > 0) and (JFolder::exists(JPATH_ROOT.'/plugins/edocman/indexer')))
			{
				$this->indexer = 1;
			}
			else
			{
				$this->indexer = 0;
			}
		}
		else
		{
			$this->indexer = 0;
		}


        if($this->config->activate_multilingual_feature && $row->id > 0)
        {
            //get all available language in Joomla system
            if($row->language != "" && $row->language != "*")
            {
                $query->clear();
                $query->select('lang_id, lang_code, title')->from('#__languages')->where('published = 1')->where('lang_code <> "'.$row->language.'"')->order('ordering');
                $db->setQuery($query);
                $langs = $db->loadObjectList();
                if(count($langs))
                {
                    foreach($langs as $lang)
                    {
                        $query->clear();
                        $query->select('assoc_id')->from('#__edocman_associations')->where('document_id = '.$row->id)->where('assoc_lang = "'.$lang->lang_code.'"');
                        $db->setQuery($query);
                        $assoc_id = $db->loadResult();
                        $lang->assoc_id = $assoc_id;
                    }
                }
            }
            $this->langs = $langs;
        }

        #Plugin support
        JPluginHelper::importPlugin('edocman');
        $results = JFactory::getApplication()->triggerEvent('onEditDocument', array($this->item));
        $this->plugins  = $results;
        $this->bootstrapHelper = new EDocmanHelperBootstrap(EDocmanHelperHtml::getAdminBootstrapHelper());
	}	
}
