<?php

/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

if (!class_exists("CommunityViewPolls")) {

    class CommunityViewPolls extends CommunityView
    {   
        /**
         * Method to display listing of polls from the site
         * */
        public function display($data = NULL)
        {
            $mainframe = JFactory::getApplication();
            $jinput = $mainframe->input;
            $document = JFactory::getDocument();

            // Get category id from the query string if there are any.
            $categoryId = $jinput->getInt('categoryid', 0);
            $pollId = $jinput->getInt('pollId', null);
            $category = JTable::getInstance('PollCategory', 'CTable');
            $category->load($categoryId);

            if ($categoryId != 0) {
                $this->addPathway(JText::_('COM_COMMUNITY_POLLS'), CRoute::_('index.php?option=com_community&view=polls'));
                
                // Opengraph
                CHeadHelper::setType('website', JText::_('COM_COMMUNITY_POLLS_CATEGORIES') . ' : ' . str_replace('&amp;', '&', JText::_($this->escape($category->name))));
            } else {
                $this->addPathway(JText::_('COM_COMMUNITY_POLLS'));
                
                // Opengraph
                CHeadHelper::setType('website', JText::_('COM_COMMUNITY_POLLS'));
            }

            // If we are browing by category, add additional breadcrumb and add
            // category name in the page title
            /* begin: UNLIMITED LEVEL BREADCRUMBS PROCESSING */
            if ($category->parent == COMMUNITY_NO_PARENT) {
                $this->addPathway(JText::_($this->escape($category->name)), CRoute::_('index.php?option=com_community&view=polls&categoryid=' . $category->id));
            } else {
                // Parent Category
                $parentsInArray = array();
                $n = 0;
                $parentId = $category->id;

                $parent = JTable::getInstance('PollCategory', 'CTable');

                do {
                    $parent->load($parentId);
                    $parentId = $parent->parent;

                    $parentsInArray[$n]['id'] = $parent->id;
                    $parentsInArray[$n]['parent'] = $parent->parent;
                    $parentsInArray[$n]['name'] = JText::_($this->escape($parent->name));

                    $n++;
                } while ($parent->parent > COMMUNITY_NO_PARENT);

                for ($i = count($parentsInArray) - 1; $i >= 0; $i--) {
                    $this->addPathway($parentsInArray[$i]['name'], CRoute::_('index.php?option=com_community&view=polls&categoryid=' . $parentsInArray[$i]['id']));
                }
            }
            /* end: UNLIMITED LEVEL BREADCRUMBS PROCESSING */

            $config = CFactory::getConfig();
            $my = CFactory::getUser();
            $uri = JURI::base();
            $data = new stdClass();
            $sorted = $jinput->get->get('sort', 'latest', 'STRING');
            $limitstart = $jinput->get('limitstart', 0, 'INT');
            
            //cache polls categories
            $data->categories = $this->_cachedCall('getPollsCategories', array($category->id), '', array(COMMUNITY_CACHE_TAG_POLLS_CAT));

            // cache polls list
            $user = CFactory::getUser();
            $username = $user->get('username');
            $featured = (!is_null($username) ) ? true : false;

            $pollsData = $this->_cachedCall('getShowAllPolls', array($category->id, $sorted, $pollId), COwnerHelper::isCommunityAdmin($my->id), array(COMMUNITY_CACHE_TAG_POLLS));
            $pollsHTML = $pollsData['HTML'];

            //Cache Poll Featured List
            $featuredPolls = $this->_cachedCall('_getPollsFeaturedList', array(), '', array(COMMUNITY_CACHE_TAG_FEATURED));
            $featuredHTML = $featuredPolls['HTML'];

            //no Featured Poll headline slideshow on Category filtered page
            if (!empty($categoryId))
                $featuredHTML = '';

            $tmpl = new CTemplate($this);

            $sortItems = array(
                'latest' => JText::_('COM_COMMUNITY_POLLS_SORT_LATEST'),
                'alphabetical' => JText::_('COM_COMMUNITY_SORT_ALPHABETICAL'),
                //'mostactive' => JText::_('COM_COMMUNITY_POLLS_SORT_MOST_ACTIVE')
            );

            // if($config->get('show_featured')){
            //     $sortItems['featured'] = JText::_('COM_COMMUNITY_POLLS_SORT_FEATURED');
            // }

            echo $tmpl->set('featuredHTML', $featuredHTML)
                    ->set('index', true)
                    ->set('categories', $data->categories)
                    ->set('availableCategories', $this->getFullPollsCategories())
                    ->set('pollsHTML', $pollsHTML)
                    ->set('config', $config)
                    ->set('category', $category)
                    ->set('categoryId', $categoryId)
                    ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                    ->set('sortings', CFilterBar::getHTML(CRoute::getURI(), $sortItems, 'latest'))
                    ->set('my', $my)
                    ->set('submenu', $this->showSubmenu(false))
                    ->fetch('polls/base');
        }

        public function mypolls($userid)
        {
            $mainframe = JFactory::getApplication();
            $jinput = $mainframe->input;
            $document = JFactory::getDocument();
            $user = CFactory::getUser($userid);
            $my = CFactory::getUser();

            if(!$user->_userid){
                $mainframe->redirect(CRoute::_('index.php?option=com_community&view=polls', false));
            }

            $title = ($my->id == $user->id) ? JText::_('COM_COMMUNITY_POLLS_MY_POLLS') : JText::sprintf('COM_COMMUNITY_POLLS_USER_TITLE', $user->getDisplayName());
            /**
             * Opengraph
             */
            CHeadHelper::setType('website', $title);

            // Add the miniheader if necessary
            if ($my->id != $user->id) {
                $this->attachMiniHeaderUser($user->id);
            }

            // Load required filterbar library that will be used to display the filtering and sorting.

            $this->addPathway(JText::_('COM_COMMUNITY_POLLS'), CRoute::_('index.php?option=com_community&view=polls'));
            $this->addPathway(JText::_('COM_COMMUNITY_POLLS_MY_POLLS'), '');

            $uri = JURI::base();
            $sorted = $jinput->get->get('sort', 'latest', 'STRING');

            $pollsModel = CFactory::getModel('polls');
            $polls = $pollsModel->getPolls($user->id, $sorted);
            $pagination = $pollsModel->getPagination(count($polls));

            // Attach additional properties that the poll might have
            $pollIds = '';
            if ($polls) {
                foreach ($polls as $poll) {
                    $pollIds = (empty($pollIds)) ? $poll->id : $pollIds . ',' . $poll->id;
                }
            }

            // Get the template for the group lists
            $pollsHTML = $this->_getPollsHTML($polls, $pagination);

            $sortItems = array(
                'latest' => JText::_('COM_COMMUNITY_POLLS_SORT_LATEST'),
                'alphabetical' => JText::_('COM_COMMUNITY_SORT_ALPHABETICAL'),
                //'mostactive' => JText::_('COM_COMMUNITY_POLLS_SORT_MOST_ACTIVE')
            );

            if(CFactory::getConfig()->get('show_featured')){
                $sortItems['featured'] = JText::_('COM_COMMUNITY_POLLS_SORT_FEATURED');
            }

            $tmpl = new CTemplate();
            echo $tmpl->set('pollsHTML', $pollsHTML)
                    ->set('pagination', $pagination)
                    ->set('isMyPolls', true)
                    ->set('my', $my)
                    ->set('user', $user)
                    ->set('title', $title)
                    ->set('sortings', CFilterBar::getHTML(CRoute::getURI(), $sortItems, 'latest'))
                    ->set('submenu', $this->showSubmenu(false))
                    ->fetch('polls/base');
        }

        public function search()
        {
            // Opengraph
            CHeadHelper::setType('website', JText::_('COM_COMMUNITY_POLLS_SEARCH_TITLE'));

            $mainframe = JFactory::getApplication();
            $jinput = $mainframe->input;

            $this->addPathway(JText::_('COM_COMMUNITY_GROUPS'), CRoute::_('index.php?option=com_community&view=polls'));
            $this->addPathway(JText::_("COM_COMMUNITY_SEARCH"), '');

            $search = $jinput->get('search', '', 'STRING');
            $catId = $jinput->get('catid', '', 'INT');
            $polls = '';
            $pagination = null;
            $posted = false;
            $count = 0;

            $model = CFactory::getModel('polls');

            $categories = $model->getCategories();

            // Test if there are any post requests made
            if ((!empty($search) || !empty($catId))) {
                JSession::checkToken('get') or jexit(JText::_('COM_COMMUNITY_INVALID_TOKEN'));

                $appsLib = CAppPlugins::getInstance();
                $saveSuccess = $appsLib->triggerEvent('onFormSave', array('jsform-polls-search'));

                if (empty($saveSuccess) || !in_array(false, $saveSuccess)) {
                    $posted = true;

                    $polls = $model->getAllPolls($catId, null, $search);
                    $pagination = $model->getPagination();
                    $count = count($polls);
                }
            }

            $pollsHTML = $this->_getPollsHTML($polls, $pagination);

            $searchLinks = parent::getAppSearchLinks('polls');

            $tmpl = new CTemplate();
            echo $tmpl->set('posted', $posted)
                    ->set('pollsCount', $count)
                    ->set('pollsHTML', $pollsHTML)
                    ->set('search', $search)
                    ->set('categories', $categories)
                    ->set('catId', $catId)
                    ->set('searchLinks', $searchLinks)
                    ->set('submenu', $this->showSubmenu(false))
                    ->fetch('polls.search');
        }

        public function create($data)
        {   
            //Opengraph
            CHeadHelper::setType('website', JText::_('COM_COMMUNITY_POLLS_CREATE_POLL'));

            $config = CFactory::getConfig();

            $my = CFactory::getUser();
            $model = CFactory::getModel('polls');
            $totalPoll = $model->getPollsCreationCount($my->id);

            $mainframe = JFactory::getApplication();
            $jinput = $mainframe->input;

            if (!$my->authorise('community.create', 'polls')) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_COMMUNITY_POLLS_DISABLE_CREATE_MESSAGE'),'');
                return;
            }

            //initialize default value
            $poll = JTable::getInstance('Poll', 'CTable');
            $poll->title = $jinput->post->get('title', '', 'STRING');
            $poll->catid = $jinput->get('catid', '', 'INT');
            $poll->permissions = $jinput->get('permissions', 10, 'INT');
            $poll->multiple = $jinput->get('multiple', 0, 'INT');
            $poll->pollitems = $jinput->post->get('pollItem', '', 'STRING');

            // Load category tree
            $cTree = CCategoryHelper::getCategories($data->categories);
            $lists['categoryid'] = CCategoryHelper::getSelectList('polls', $cTree, $poll->catid, true);

            $systemOffset = $mainframe->get('offset');
            $timezones = CTimeHelper::getBeautifyTimezoneList();
            $endDate = $poll->getEndDate(false);
            $dateSelection = CEventHelper::getDateSelection('', $endDate);

            $this->addPathway(JText::_('COM_COMMUNITY_POLLS'), CRoute::_('index.php?option=com_community&view=polls'));
            $this->addPathway(JText::_('COM_COMMUNITY_POLLS_CREATE_POLL'));

            $tmpl = new CTemplate();
            echo $tmpl->set('config', $config)
                    ->set('lists', $lists)
                    ->set('categories', $data->categories)
                    ->set('poll', $poll)
                    ->set('pollCreated', $totalPoll)
                    ->set('pollcreatelimit', $config->get('pollcreatelimit'))
                    ->set('isNew', true)
                    ->set('endHourSelect', $dateSelection->endHour)
                    ->set('endMinSelect', $dateSelection->endMin)
                    ->set('endAmPmSelect', $dateSelection->endAmPm)
                    ->set('timezones', $timezones)
                    ->set('systemOffset', $systemOffset)
                    ->fetch('polls.forms');
        }

        public function created() 
        {
            $jinput = JFactory::getApplication()->input;
            $pollid = $jinput->get('pollid', 0);
            $mainframe  = JFactory::getApplication();
            $mainframe->redirect(CRoute::_('index.php?option=com_community&view=polls', false));
        }

        public function edit()
        {
            // Opengraph
            CHeadHelper::setType('website', JText::_('COM_COMMUNITY_POLLS_EDIT_TITLE'));

            $config = CFactory::getConfig();

            $this->showSubmenu();
            $jinput = JFactory::getApplication()->input;
            $pollId = $jinput->request->getInt('pollid');
            $pollModel = CFactory::getModel('Polls');
            $categories = $pollModel->getCategories();
            $poll = JTable::getInstance('Poll', 'CTable');
            $poll->load($pollId);

            $my = CFactory::getUser();
            if (!$my->authorise('community.edit', 'polls.' . $pollId, $poll)) {
                $errorMsg = $my->authoriseErrorMsg();
                if ($errorMsg == 'blockUnregister') {
                    return $this->blockUnregister();
                } else {
                    echo $errorMsg;
                }
                return;
            }

            // @rule: Test if the poll is unpublished, don't display it at all.
            if (!$poll->published) {
                $this->_redirectUnpublishPoll();
                return;
            }

            $this->addPathway(JText::_('COM_COMMUNITY_POLLS'), CRoute::_('index.php?option=com_community&view=polls'));
            $this->addPathway(JText::_('COM_COMMUNITY_POLLS_EDIT_TITLE'));

            // Load category tree
            $cTree = CCategoryHelper::getCategories($categories);
            $lists['categoryid'] = CCategoryHelper::getSelectList('polls', $cTree, $poll->catid, true);

            $mainframe  = JFactory::getApplication();
            $systemOffset = $mainframe->get('offset');
            $timezones = CTimeHelper::getBeautifyTimezoneList();
            $endDate = $poll->getEndDate(false);
            $dateSelection = CEventHelper::getDateSelection('', $endDate);

            $tmpl = new CTemplate();
            echo $tmpl->set('config', $config)
                    ->set('lists', $lists)
                    ->set('categories', $categories)
                    ->set('poll', $poll)
                    ->set('isNew', false)
                    ->set('endHourSelect', $dateSelection->endHour)
                    ->set('endMinSelect', $dateSelection->endMin)
                    ->set('endAmPmSelect', $dateSelection->endAmPm)
                    ->set('timezones', $timezones)
                    ->set('systemOffset', $systemOffset)
                    ->fetch('polls.forms');
        }

        public function getShowAllPolls($category = null, $sorted = null, $pollId = null) 
        {
            $model = CFactory::getModel('polls');

            // Get group in category and it's children.
            $categories = $model->getAllCategories();
            $categoryIds = CCategoryHelper::getCategoryChilds($categories, $category);
            if ((int) $category > 0) {
                $categoryIds[] = (int) $category;
            }

            // It is safe to pass 0 as the category id as the model itself checks for this value.
            $data = new StdClass;
            $data->polls = $model->getAllPolls($categoryIds, $sorted, $pollId);

            // Get pagination object
            $data->pagination = $model->getPagination();

            // Get the template for the group lists
            $groupsHTML['HTML'] = $this->_getPollsHTML($data->polls, $data->pagination);

            return $groupsHTML;
        }

        public function getFullPollsCategories($id = 0, $level = 0, $categoryList = array())
        {
            $model = CFactory::getModel('polls');
            $mainCategories = $model->getCategories($id);

            if(count($mainCategories) > 0){
                foreach($mainCategories as $category){
                    $prefix = '';
                    for($i = 0; $i < $level; $i++){
                        $prefix = $prefix.'-'; // this will add the - in front of the category name
                    }

                    $category->name = $prefix.' '.JText::_($category->name);
                    $categoryList[] = $category;
                    $categoryList = $this->getFullPollsCategories($category->id, $level+1, $categoryList);
                }
            }

            return $categoryList;
        }

        public function getPollsCategories($category)
        {
            $model = CFactory::getModel('polls');
            $categories = $model->getCategoriesCount();

            $categories = CCategoryHelper::getParentCount($categories, $category);

            return $categories;
        }

        public function _getPollsFeaturedList() {
            $featPolls = $this->getPollsFeaturedList();
            $featuredHTML['HTML'] = $this->_getFeatHTML($featPolls);

            return $featuredHTML;
        }

        public function _getPollsHTML($tmpPolls, $tmpPagination = NULL)
        {
            $config = CFactory::getConfig();
            $tmpl = new CTemplate();
            $featured = new CFeatured(FEATURED_POLLS);
            $featuredList = $featured->getItemIds();

            $polls = array();

            if ($tmpPolls) {
                foreach ($tmpPolls as $row) {
                    $poll = JTable::getInstance('Poll', 'CTable');
                    $poll->bind($row);
                    $polls[] = $poll;
                }
                unset($tmpPolls);
            }

            $pollsHTML = $tmpl->set('showFeatured', $config->get('show_featured'))
                    ->set('featuredList', $featuredList)
                    ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                    ->set('polls', $polls)
                    ->set('pagination', $tmpPagination)
                    ->fetch('polls/list');
            unset($tmpl);

            return $pollsHTML;
        }

        public function getPollsFeaturedList() {
            $featured = new CFeatured(FEATURED_POLLS);
            $featuredPolls = $featured->getItemIds();
            $featuredList = array();

            foreach ($featuredPolls as $poll) {
                $table = JTable::getInstance('Poll', 'CTable');
                $table->load($poll);
                $featuredList[] = $table;
            }
            return $featuredList;
        }

        private function _getFeatHTML($polls) {
            $my = CFactory::getUser();
            $config = CFactory::getConfig();
            $poll = JTable::getInstance('Poll', 'CTable');

            $tmpl = new CTemplate();
            return $tmpl->set('polls', $polls)
                            ->set('showFeatured', $config->get('show_featured'))
                            ->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin())
                            ->set('my', $my)
                            ->fetch('polls.featured');
        }

        public function showSubmenu($display=true) {
            $this->_addSubmenu();
            return parent::showSubmenu($display);
        }

        public function _addSubmenu() {
            $mainframe = JFactory::getApplication();
            $jinput = $mainframe->input;

            $task = $jinput->get('task', '');
            $config = CFactory::getConfig();
            $pollid = $jinput->get('pollid', '');
            $categoryid = $jinput->get('categoryid', '');
            $my = CFactory::getUser();

            $pollsModel = CFactory::getModel('polls');
            $isCreator = $pollsModel->isCreator($my->id, $pollid);
            $isSuperAdmin = COwnerHelper::isCommunityAdmin();

            $this->addSubmenuItem('index.php?option=com_community&view=polls', JText::_('COM_COMMUNITY_POLLS_ALL_POLLS'));

            if (COwnerHelper::isRegisteredUser()) {
                $this->addSubmenuItem('index.php?option=com_community&view=polls&task=mypolls&userid=' . $my->id, JText::_('COM_COMMUNITY_POLLS_MY_POLLS'));
            }
        }
    }
}