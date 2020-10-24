<?php
/**
 * @version     1.3.6
 * @package     Annatech.Plugin
 * @subpackage  Services.joomla
 *
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
include_once JPATH_BASE . '/components/com_content/views/category/view.html.php';

/**
 * Class ServicesJoomlaContent
 * @since 1.2.9
 */
class ServicesJoomlaHelpersContent  {

    /**
     * contentServicesJoomla constructor.
     * @since 1.0
     */
    public function __construct()
    {
        $app = \Slim\Slim::getInstance();

        /**
         * Content Services
         */
        $app->group('/content', function () use ($app) {

            /**
             * @SWG\Get(
             *     path="/content/list/all",
             *     summary="Return list of all Joomla content",
             *     operationId="getContentListAll",
             *     tags={"Content"},
             *
             *   @SWG\Response(
             *     response="200",
             *     description="List of all content"
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden"
             *   )
             * )
             */
            $app->get('/list/all', function () use ($app)
            {
                $user = JFactory::getUser();
                if($user->authorise('core.edit','com_content.component')) {
                    $query = $app->_db->getQuery(true);
                    $query->select('*')
                        ->from($app->_db->quoteName('#__content'))
                        ->where($app->_db->quoteName('state') . ' = ' . $app->_db->quote('1'));
                    $app->_db->setQuery($query);

                    $app->render(200, $app->_db->loadObjectList() );
                }
                $app->render(403, array(
                        'msg' => 'Forbidden',
                    )
                );
            });

            /**
             * @SWG\Get(
             *     path="/content/item/{id}",
             *     summary="Get Joomla content by ID",
             *     operationId="getContentItemById",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Content ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="integer",
             *     format="double"
             * ),
             *   @SWG\Response(
             *     response="200",
             *     description="List content item",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Forbidden"
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="Not found"
             *   )
             * )
             */
            $app->get('/item/:id', function ($id) use ($app)
            {
                $user = JFactory::getUser();

                $content = JTable::getInstance('content');
                $content->load($id);

                if($content->id === null){
                    $app->render(404, array(
                            'msg' => 'Not Found',
                        )
                    );
                }

                if ($user->block !== '1' && in_array($content->access, $user->getAuthorisedViewLevels())) {
                    $app->render(200, array(
                            'article' => $content
                        )
                    );
                }

                $app->render(401, array(
                        'msg' => 'Forbidden',
                    )
                );
            })->name('getContentItemById');

            /**
             * @SWG\Post(
             *     path="/content/create",
             *     summary="Create new Joomla content item",
             *     operationId="postContentCreate",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Article title",
             *     in="query",
             *     name="title",
             *     required=true,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article title alias",
             *     in="query",
             *     name="alias",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article introtext",
             *     in="query",
             *     name="introtext",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article fulltext",
             *     in="query",
             *     name="fulltext",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Category ID",
             *     in="query",
             *     name="catid",
             *     required=true,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Article author alias",
             *     in="query",
             *     name="created_by_alias",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Article author ID",
             *     in="query",
             *     name="created_by",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Access level",
             *     in="query",
             *     name="access",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Language ID ex. en-GB",
             *     in="query",
             *     name="language",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Set article state: unpublished, published or trashed",
             *     in="query",
             *     name="state",
             *     required=true,
             *     enum={0,1,-2},
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Robots",
             *     in="query",
             *     name="robots",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: author",
             *     in="query",
             *     name="author",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: content rights",
             *     in="query",
             *     name="rights",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: x-reference",
             *     in="query",
             *     name="xreference",
             *     required=false,
             *     type="string"
             * ),
             *   @SWG\Response(
             *     response="200",
             *     description="Return new content item object",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Error"
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden"
             *   )
             * )
             */
            $app->post('/create', function () use ($app)
            {
                $user = JFactory::getUser();
                if (count($user->getAuthorisedCategories('com_content', 'core.create')) > 0)
                {
                    if (version_compare(JVERSION, '3.0', 'lt')) {
                        JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
                    }

                    $table = JTable::getInstance('content');

                    $article                    = new stdClass();
                    $article->title             = $app->request->params('title');
                    $article->alias             = JFilterOutput::stringURLSafe($app->request->params('alias'));
                    $article->introtext         = $app->request->params('introtext');
                    $article->fulltext          = $app->request->params('fulltext');
                    $article->catid             = $app->request->params('catid');
                    $article->created           = JFactory::getDate()->toSQL();
                    $article->created_by_alias  = $app->request->params('created_by_alias');
                    if($app->request->params('created_by') !== null) {
                        $article->created_by = $app->request->params('created_by');
                    }
                    $article->state             = $app->request->params('state');
                    $article->access            = $app->request->params('access');
                    $article->metadata          = '{"robots":"'.$app->request->params('robots').'","author":"'.$app->request->params('author'). '","rights":"'.$app->request->params('rights').'","xreference":"'.$app->request->params('xreference').'"}';
                    $article->language          = $app->request->params('language');

                    $data = (array)$article;

                    // Bind data
                    if (!$table->bind($data))
                    {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.',
                            )
                        );
                    }

                    // Check to make sure our data is valid, raise notice if it's not.
                    if (!$table->check()) {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.',
                            )
                        );
                    }

                    // Now store the article, raise notice if it doesn't get stored.
                    if (!$table->store(TRUE)) {
                        $app->render(401, array(
                                'msg' => 'Error: Duplicate title or alias.',
                            )
                        );
                    }
                    $app->render(200, get_object_vars($table)
                    );
                }
                $app->render(403, array(
                        'msg' => 'Forbidden',
                    )
                );
            })->name('postContentCreate');

            /**
             * @SWG\Put(
             *     path="/content/update/{id}",
             *     summary="Update Joomla content item by ID",
             *     operationId="putContentUpdateByID",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Article ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article title",
             *     in="query",
             *     name="title",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article title alias",
             *     in="query",
             *     name="alias",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article introtext",
             *     in="query",
             *     name="introtext",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article fulltext",
             *     in="query",
             *     name="fulltext",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Category ID",
             *     in="query",
             *     name="catid",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Article author alias",
             *     in="query",
             *     name="created_by_alias",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Modified-by user ID",
             *     in="query",
             *     name="modified_by",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Access level",
             *     in="query",
             *     name="access",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Language ID ex. en-GB",
             *     in="query",
             *     name="language",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Set article state: unpublished, published or trashed",
             *     in="query",
             *     name="state",
             *     required=false,
             *     enum={0,1,-2},
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Robots - NOT CURRENTLY IMPLEMENTED",
             *     in="query",
             *     name="robots",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: author - NOT CURRENTLY IMPLEMENTED",
             *     in="query",
             *     name="author",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: content rights - NOT CURRENTLY IMPLEMENTED",
             *     in="query",
             *     name="rights",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: x-reference - NOT CURRENTLY IMPLEMENTED",
             *     in="query",
             *     name="xreference",
             *     required=false,
             *     type="string"
             * ),
             *   @SWG\Response(
             *     response="200",
             *     description="Return update content item object",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Error"
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden"
             *   )
             * )
             */
            $app->put('/update/:id', function ($id) use ($app)
            {

                $user = JFactory::getUser();
                if ($user->authorise('core.edit', 'com_content.article.' . $id) || $user->authorise('core.edit.own', 'com_content.article.' . $id))
                {

                    $table = JTable::getInstance('content','JTable');
                    $table->load($id);

                    $article = new stdClass();

                    if($app->request->params('title')){
                        $article->title = $app->request->params('title');
                    }
                    if(!$app->request->params('alias') && $app->request->params('title')){
                        $article->alias = JFilterOutput::stringURLSafe($app->request->params('title'));
                    }
                    if($app->request->params('alias')){
                        $article->alias = JFilterOutput::stringURLSafe($app->request->params('alias'));
                    }
                    if($app->request->params('introtext')) {
                        $article->introtext = $app->request->params('introtext');
                    }
                    if($app->request->params('fulltext')) {
                        $article->fulltext = $app->request->params('fulltext');
                    }
                    if($app->request->params('catid')) {
                        $article->catid = $app->request->params('catid');
                    }
                    if($app->request->params('created_by_alias')) {
                        $article->created_by_alias = $app->request->params('created_by_alias');
                    }
                    if($app->request->params('modified_by')) {
                        $article->modified_by = $app->request->params('modified_by');
                    }
                    if($app->request->params('access')) {
                        $article->access = $app->request->params('access');
                    }
                    // $article->metadata   = '{"robots":"'.$app->request->params('robots').'","author":"'.$app->request->params('author'). '","rights":"'.$app->request->params('rights').'","xreference":"'.$app->request->params('xreference').'"}';

                    if($app->request->params('language')) {
                        $article->language = $app->request->params('language');
                    }
                    if($app->request->params('state') === '1'){
                        $article->state = '1';
                    }elseif($app->request->params('state') === '0'){
                        $article->state = '0';
                    }elseif($app->request->params('state') === '-2'){
                        $article->state = '-2';
                    }

                    $data = (array)$article;

                    // Bind data

                    if (!$table->bind($data))
                    {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.'
                            )
                        );
                    }

                    // Check to make sure our data is valid, raise notice if it's not.
                    if (!$table->check()) {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.'
                            )
                        );
                    }

                    // Now update the article, raise notice if it doesn't get stored.
                    if (!$table->store()) {
                        $app->render(401, array(
                                'msg' => 'Error: Duplicate title or alias.'
                            )
                        );
                    }

                    $app->render(200,
                        get_object_vars($table)
                    );
                }

                $app->render(403, array(
                        'msg' => 'Forbidden'
                    )
                );
            })->name('putContentUpdateByID');

            /**
             * @SWG\Delete(
             *     path="/content/delete/{id}",
             *     summary="Delete Joomla content item by ID",
             *     operationId="deleteContentDeleteByID",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Article ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="string"
             * ),
             *   @SWG\Response(
             *     response="200",
             *     description="Content deleted successfully",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Unauthorized"
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="Not found"
             *   )
             * )
             */
            $app->delete('/delete/:id', function ($id) use ($app)
            {
                $user = JFactory::getUser();

                $content = JTable::getInstance('content');

                if(!$content->load($id)){
                    $app->render(404, array(
                            'msg' => 'Not Found',
                        )
                    );
                }

                if ($user->block !== '1' && in_array($content->access, $user->getAuthorisedViewLevels())) {
                    $app->render(200, array(
                            'content' => $content->delete($id)
                        )
                    );
                }

                $app->render(401, array(
                        'msg' => 'Unauthorized',
                    )
                );
            })->name('deleteContentDeleteByID');

            /**
             * @SWG\Get(
             *     path="/content/fields",
             *     summary="List all Joomla content item fields",
             *     operationId="getContentFields",
             *     tags={"Content"},
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Lists content fields",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Unauthorized"
             *   ),
             * )
             */
            $app->get('/fields', function () use ($app)
            {
                $user = JFactory::getUser();
                if (count($user->getAuthorisedCategories('com_content', 'core.create')) > 0) {
                    if (version_compare(JVERSION, '3.0', 'lt')) {
                        JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
                    }

                    $table = JTable::getInstance('content', 'JTable');

                    $app->render(200, array(
                            'fields' => $table->getFields()
                        )
                    );
                }
                $app->render(401, array(
                        'msg' => 'Unauthorized',
                    )
                );
            })->name('getContentFields');

        });

        /**
         * Category Services
         */
        $app->group('/category', function () use ($app) {

            /**
             * @SWG\Get(
             *     path="/category/list/all",
             *     summary="List all Joomla Content Categories",
             *     operationId="getCategoryListAll",
             *     tags={"Content"},
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Lists all content categories",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Unauthorized"
             *   ),
             * )
             * TODO: Fix to accomodate infinite recursion
             */
            $app->get('/list/all', function () use ($app)
            {
                $user = JFactory::getUser();
                if (count($user->getAuthorisedCategories('com_content', 'core.create')) > 0) {
                    $categories = JCategories::getInstance( 'Content' );
                    foreach ($categories->get()->getChildren() as $key => $value){
                        $category_array[$value->id] = $value;
                        if ($value->getChildren() != NULL) {
                            foreach ($value->getChildren() as $key1 => $value1) {
                                $category_array[$value1->id] = $value1;
                            }
                        }
                    }
                    $app->render(200,
                        $category_array
                    );
                }
                $app->render(401, array(
                        'msg' => 'Unauthorized',
                    )
                );
            })->name('getCategoryListAll');

            /**
             * @SWG\Get(
             *     path="/category/single/{id}",
             *     summary="Return Joomla content category by ID",
             *     operationId="getCategorySingleById",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Category ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Lists Joomla content category",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Unauthorized"
             *   ),
             * )
             */
            $app->get('/single/:id', function ($id) use ($app)
            {
                $user = JFactory::getUser();
                if (count($user->getAuthorisedCategories('com_content', 'core.create')) > 0) {

                    $categories = JCategories::getInstance( 'Content' );
                    $category = $categories->get($id);

                    $app->render(200, array(
                            'category' => $category
                        )
                    );
                }
                $app->render(401, array(
                        'msg' => 'Unauthorized',
                    )
                );
            })->name('getCategorySingleById');

            /**
             * @SWG\Get(
             *     path="/category/list/{id}/categories",
             *     summary="List Joomla Content Sub-Categories by Parent ID",
             *     description="Note: Does not recurse into sub-levels below designated list",
             *     operationId="getCategoryListByIdCategories",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Category ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Lists Joomla content sub-categories",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Unauthorized"
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="Category ID does not exist"
             *   ),
             * )
             */
            $app->get('/list/:id/categories', function ($id) use ($app)
            {
                $user = JFactory::getUser();
                if (count($user->getAuthorisedCategories('com_content', 'core.create')) > 0) {

                    $categories = JCategories::getInstance( 'Content' );
                    $subcategory = $categories->get($id);

                    if($subcategory) {
                        $app->render(200, array(
                                'categories' => $subcategory->getChildren()
                            )
                        );
                    }
                    $app->render(404, array(
                            'msg' => 'Category ID does not exist.'
                        )
                    );


                }
                $app->render(401, array(
                        'msg' => 'Unauthorized',
                    )
                );
            })->name('getCategoryListByIdCategories');

            /**
             * @SWG\Get(
             *     path="/category/list/{id}/content",
             *     summary="List Joomla Content in Category by Category ID",
             *     description="",
             *     operationId="getCategoryListByIdContent",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Category ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Lists Joomla content in category",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden"
             *   ),
             * )
             */
            $app->get('/list/:id/content', function ($id) use ($app)
            {
                $user = JFactory::getUser();
                if($user->authorise('core.admin')) {
                    $query = $app->_db->getQuery(true);
                    $query->select('*')
                        ->from($app->_db->quoteName('#__content'))
                        ->where($app->_db->quoteName('state') . ' = ' . $app->_db->quote('1'))
                        ->where($app->_db->quoteName('catid') . ' = ' . $app->_db->quote($id));
                    $app->_db->setQuery($query);

                    $app->render(200, $app->_db->loadObjectList());
                }
                $app->render(403, array(
                        'msg' => 'Forbidden',
                    )
                );
            })->name('getCategoryListByIdContent');

            /**
             * @SWG\Post(
             *     path="/category/create",
             *     summary="Create Joomla Content Category",
             *     description="",
             *     operationId="postCategoryCreate",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Category title",
             *     in="query",
             *     name="title",
             *     required=true,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Category title alias",
             *     in="query",
             *     name="alias",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article description",
             *     in="query",
             *     name="description",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Parent ID",
             *     in="query",
             *     name="parent_id",
             *     required=true,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Set category published state: unpublished, published or trashed",
             *     in="query",
             *     name="published",
             *     required=true,
             *     enum={0,1,-2},
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Access level",
             *     in="query",
             *     name="access",
             *     required=true,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: robots",
             *     in="query",
             *     name="robots",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: author",
             *     in="query",
             *     name="author",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata description",
             *     in="query",
             *     name="metadesc",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metakey",
             *     in="query",
             *     name="metakey",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Language ID ex. en-GB",
             *     in="query",
             *     name="language",
             *     required=false,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Returns created category title",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Error",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden"
             *   ),
             * )
             */
            $app->post('/create', function () {
                $user = JFactory::getUser();
                $app = \Slim\Slim::getInstance();
                if (count($user->getAuthorisedCategories('com_content', 'core.create')) > 0)
                {
                    if (version_compare(JVERSION, '3.0', 'lt')) {
                        JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
                    }
                    $table = JTable::getInstance('category');

                    $category               = new stdClass();
                    $category->title        = $app->request->params('title');
                    $category->alias        = JFilterOutput::stringURLSafe($app->request->params('alias'));
                    $category->description  = $app->request->params('description');
                    $category->created_time = JFactory::getDate()->toSQL();;
                    $category->parent_id    = $app->request->params('parent_id');
                    $category->published    = $app->request->params('published');
                    $category->access       = $app->request->params('access');
                    $category->metadata     = '{"author":"'.$app->request->params('author').'","robots":"'.$app->request->params('robots'). '"}';
                    $category->metadesc     = $app->request->params('metadesc');
                    $category->metakey      = $app->request->params('metakey');
                    $category->language     = $app->request->params('language');
                    $category->params       = '{"category_layout":"","image":"","image_alt":""}';
                    $category->extension    = 'com_content';

                    $data = (array)$category;

                    // setLocation uses the parent_id and updates the nesting columns correctly
                    $table->setLocation($data['parent_id'], 'last-child');

                    // Push data into the table object
                    if (!$table->bind($data))
                    {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.',
                            )
                        );
                    }

                    //  Data checks including setting the alias based on the name
                    if (!$table->check()) {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.',
                            )
                        );
                    }

                    // Now store the article, raise notice if it doesn't get stored.
                    if (!$table->store(TRUE)) {
                        $app->render(401, array(
                                'msg' => 'Error: Duplicate title or alias.',
                            )
                        );
                    }
                    $app->render(200, array(
                            'msg' => 'Created: '.$category->title
                        )
                    );
                }
                $app->render(403, array(
                        'msg' => 'Forbidden',
                    )
                );
            })->name('postCategoryCreate');

            /**
             * @SWG\Put(
             *     path="/category/update/{id}",
             *     summary="Update Joomla Content Category by ID",
             *     description="",
             *     operationId="putCategoryUpdateById",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Category ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Category title",
             *     in="query",
             *     name="title",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Category title alias",
             *     in="query",
             *     name="alias",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Article description",
             *     in="query",
             *     name="description",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Parent ID",
             *     in="query",
             *     name="parent_id",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Set category published state: unpublished, published or trashed",
             *     in="query",
             *     name="published",
             *     required=false,
             *     enum={0,1,-2},
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Access level",
             *     in="query",
             *     name="access",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata description",
             *     in="query",
             *     name="metadesc",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metakey",
             *     in="query",
             *     name="metakey",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Language ID ex. en-GB",
             *     in="query",
             *     name="language",
             *     required=false,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Returns created category title",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Error",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden"
             *   ),
             * )
             * TODO: Work in progress
             */
            $app->put('/update/:id', function ($id) use ($app)
            {
                $user = JFactory::getUser();
                if ($user->authorise('core.edit', 'com_content.category.' . $id) || $user->authorise('core.edit.own', 'com_content.category.' . $id))
                {
                    $table = JTable::getInstance('category');
                    $table->load($id);

                    $article = new stdClass();

                    if($app->request->params('title')){
                        $article->title = $app->request->params('title');
                    }
                    if($app->request->params('alias')){
                        $article->alias = JFilterOutput::stringURLSafe($app->request->params('alias'));
                    }

                    if($app->request->params('catid')) {
                        $article->catid = $app->request->params('catid');
                    }
                    if($app->request->params('created_by_alias')) {
                        $article->created_by_alias = $app->request->params('created_by_alias');
                    }
                    if($app->request->params('access')) {
                        $article->access = $app->request->params('access');
                    }

                    if($app->request->params('parent_id')) {
                        $article->parent_id = $app->request->params('parent_id');
                    }

                    if($app->request->params('note')) {
                        $article->note = $app->request->params('note');
                    }

                    if($app->request->params('description')) {
                        $article->description = $app->request->params('description');
                    }

                    // TODO: Params update via API not supported at this time.
                    // if($app->request()->get('metakey')) {
                    //    $article->metakey = $app->request()->get('metakey');
                    // }

                    // TODO: JSON Metatdata update via API not supported at this time.
                    // $article->metadata   = '{"robots":"'.$app->request()->get('robots').'","author":"'.$app->request()->get('author'). '","rights":"'.$app->request()->get('rights').'","xreference":"'.$app->request()->get('xreference').'"}';

                    if($app->request->params('metakey')) {
                        $article->metakey = $app->request->params('metakey');
                    }

                    if($app->request->params('metadesc')) {
                        $article->metadesc = $app->request->params('metadesc');
                    }

                    if($app->request->params('language')) {
                        $article->language = $app->request->params('language');
                    }
                    if($app->request->params('published') === '1'){
                        $article->published = '1';
                    }elseif($app->request->params('published') === '0'){
                        $article->published = '0';
                    }elseif($app->request->params('published') === '-2'){
                        $article->published = '-2';
                    }

                    $data = (array)$article;

                    // Bind data

                    if (!$table->bind($data))
                    {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.'
                            )
                        );
                    }

                    // Check to make sure our data is valid, raise notice if it's not.
                    if (!$table->check()) {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.'
                            )
                        );
                    }

                    // Now update the article, raise notice if it doesn't get stored.
                    if (!$table->store()) {
                        $app->render(401, array(
                                'msg' => 'Error: Duplicate alias.'
                            )
                        );
                    }

                    $app->render(200, array(
                            'msg' => 'Updated content item id = '.$id
                        )
                    );
                }

                $app->render(403, array(
                        'msg' => 'Forbidden'
                    )
                );
            })->name('putCategoryUpdateById');

            /**
             * @SWG\Delete(
             *     path="/category/delete/{id}",
             *     summary="Delete Joomla Content Category by ID",
             *     description="",
             *     operationId="deleteCategoryDeleteId",
             *     tags={"Content"},
             *
             *     @SWG\Parameter(
             *     description="Category ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Category ID deleted successfully",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Unauthorized",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Error deleting category ID"
             *   ),
             * )
             */
            $app->delete('/delete/:id', function ($id) use ($app)
            {
                $user = JFactory::getUser();

                $category = JTable::getInstance('category');

                if(!$category->load($id)){
                    $app->render(404, array(
                            'msg' => 'Not Found',
                        )
                    );
                }

                if ($user->block !== '1' && in_array($category->access, $user->getAuthorisedViewLevels()) && $category->delete($id)) {
                    $app->render(200, array(
                            'msg' => 'Category ID '.$id. ' deleted successfully.'
                        )
                    );
                }elseif(!$category->delete($id)){
                    $app->render(403, array(
                            'msg' => 'Error deleting category ID '.$id
                        )
                    );
                }

                $app->render(401, array(
                        'msg' => 'Unauthorized',
                    )
                );
            });

            /**
             * @SWG\Get(
             *     path="/category/fields",
             *     summary="List all Joomla Content Category Fields",
             *     description="",
             *     operationId="getCategoryFields",
             *     tags={"Content"},
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Category ID deleted successfully",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Unauthorized",
             *   ),
             * )
             */
            $app->get('/fields', function () use ($app)
            {
                $user = JFactory::getUser();
                if (count($user->getAuthorisedCategories('com_content', 'core.create')) > 0) {
                    if (version_compare(JVERSION, '3.0', 'lt')) {
                        JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
                    }

                    $table = JTable::getInstance('category', 'JTable');

                    $app->render(200, array(
                            'fields' => $table->getFields()
                        )
                    );
                }
                $app->render(401, array(
                        'msg' => 'Unauthorized',
                    )
                );
            })->name('getCategoryFields');

        });

    }

    function call(){
        return $this->next->call();
    }

}