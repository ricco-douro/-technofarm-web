<?php
/**
 * @version     1.3.6
 * @package     Annatech.Plugin
 * @subpackage  Services.joomla
 *
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @since       1.2.8
 */

defined('_JEXEC') or die;
JLoader::import('joomla.application.component.model');

/**
 * Class ServicesJoomlaTag
 * @since 1.2.9
 */
class ServicesJoomlaHelpersTag  {

    public function __construct()
    {
        $app = \Slim\Slim::getInstance();

        /**
         * Tag Services
         */
        $app->group('/tag', function () use ($app) {

            /**
             * @SWG\Get(
             *     path="/tag/types",
             *     summary="Return list of all tag types",
             *     operationId="getTagTypes",
             *     tags={"Tags"},
             *
             *   @SWG\Response(
             *     response="200",
             *     description="List of all tag types"
             *   )
             * )
             */
            $app->get('/types', function () use ($app)
            {
            	$response = null;
                $tagTypes = JHelperTags::getTypes();

                $c = count($tagTypes);
	            for ($x = 0; $x <= $c; $x++) {
	            	foreach($tagTypes[$x] as $key => $value){
						if($this->json_validate($value)){
							$response[$x]->$key = json_decode($value);
						}else
						{
							$response[$x]->$key = $value;
						}
		            }
	            }

	            $app->render(200,
		            $response
                );
            })->name('getTagTypes');


            /**
             * @SWG\Get(
             *     path="/tag/fields",
             *     summary="Get tag fields",
             *     operationId="getTagFields",
             *     tags={"Tags"},
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Tag fields."
             *   )
             * )
             */
            $app->get('/fields', function () use ($app)
            {
                JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tags/tables/');
                $table = JTable::getInstance('Tag','TagsTable');

                $app->render(200,
                    $table->getFields()
                );

            })->name('getTagFields');

            /**
             * @SWG\Get(
             *     path="/tag/search",
             *     summary="Return list of searched tags",
             *     operationId="getTagSearch",
             *     tags={"Tags"},
             *
             *     @SWG\Parameter(
             *     description="Tag search value",
             *     in="query",
             *     name="tag",
             *     required=true,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Tag title",
             *     in="query",
             *     name="title",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Tag language",
             *     in="query",
             *     name="flanguage",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Tag published state",
             *     in="query",
             *     name="published",
             *     required=false,
             *     type="string",
             *     enum={0,1,-2}
             * ),
             *     @SWG\Parameter(
             *     description="Tag partent id",
             *     in="query",
             *     name="parent_id",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="List of all tag types"
             *   )
             * )
             */
            $app->get('/search', function () use ($app)
            {
                // Receive request data
                $filters = array(
                    'like'      => trim($app->request->params('tag')),
                    'title'     => trim($app->request->params('title')),
                    'flanguage' => $app->request->params('flanguage'),
                    'published' => $app->request->params('published'),
                    'parent_id' => $app->request->params('parent_id')
                );

                $app->render(200,
                    JHelperTags::searchTags($filters)
                );
            })->name('getTagSearch');

            /**
             * @SWG\Get(
             *     path="/tag/name/{ids}",
             *     summary="Return tag name(s) by id.",
             *     description="Enter one or more tag ids separate by a common to return the tag name(s).",
             *     operationId="getTagNameById",
             *     tags={"Tags"},
             *
             *     @SWG\Parameter(
             *     description="Tag id(s)",
             *     in="path",
             *     name="ids",
             *     required=true,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Tag name."
             *   )
             * )
             */
            $app->get('/name/:ids', function ($ids) use ($app)
            {
                $tagsHelper = new JHelperTags();
                $tags = $tagsHelper->getTagNames(explode(',',$ids));

                if($tags) {
                    $app->render(200,
                        $tags
                    );
                }else{
                    $app->render(404, array(
                            'msg' => 'Not found'
                        )
                    );
                }
            })->name('getTagNameById');

            /**
             * @SWG\Post(
             *     path="/tag",
             *     summary="Create tag",
             *     operationId="postTag",
             *     tags={"Tags"},
             *
             *     @SWG\Parameter(
             *     description="Tag title",
             *     in="query",
             *     name="title",
             *     required=true,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Tag name."
             *   )
             * )
             */
            $app->post('/', function () use ($app) {
                $user = JFactory::getUser();
                if($user->authorise('core.edit','com_tags.component')) {

                    // $tagsHelper = new JHelperTags();

                    JLoader::import('tag', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_tags' . DS . 'models');
                    $tagsModel = JModelLegacy::getInstance('Tag', 'TagsModel', array('ignore_request' => true));

                    JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tags/tables/');
                    // $table = JTable::getInstance('Tag', 'TagsTable');

                    $newTags = new stdClass();

                    if ($app->request->params('title')) {
                        $newTags->title = $app->request->params('title');
                    }
                    if (!is_numeric($app->request->params('parent_id'))) {
                        $newTags->parent_id = '1';
                    } else {
                        $newTags->parent_id = $app->request->params('parent_id');
                    }
                    if ($app->request->params('alias')) {
                        $newTags->alias = $app->request->params('alias');
                    }
                    if ($app->request->params('note')) {
                        $newTags->note = $app->request->params('note');
                    }
                    if ($app->request->params('description')) {
                        $newTags->description = $app->request->params('description');
                    }
                    if (!in_array($app->request->params('published'), array(0, 1, -2), false)) {
                        $newTags->published = 1;
                    } else {
                        $newTags->published = $app->request->params('published');
                    }
                    if ($app->request->params('access')) {
                        $newTags->access = $app->request->params('access');
                    }
                    if ($app->request->params('params')) {
                        // $newTags->params = $app->request->params('params');
                    } else {
                        $newTags->params = '{"tag_layout":"","tag_link_class":"label label-info"}';
                    }
                    if ($app->request->params('metadesc')) {
                        $newTags->metadesc = $app->request->params('metadesc');
                    }
                    if ($app->request->params('metakey')) {
                        $newTags->metakey = $app->request->params('metakey');
                    }
                    if ($app->request->params('metadata')) {
                        // $newTags->metadata = $app->request->params('metadata');
                    } else {
                        $newTags->metadata = '{"author":"","robots":""}';
                    }
                    if (JFactory::getUser($app->request->params('created_user_id'))) {
                        $newTags->created_user_id = $app->request->params('created_user_id');
                    } else {
                        $newTags->created_user_id = $user->id;
                    }
                    if ($app->request->params('created_by_alias')) {
                        $newTags->created_by_alias = $app->request->params('created_by_alias');
                    }
                    if ($app->request->params('images')) {
                        // $newTags->images = $app->request->params('images');
                    } else {
                        $newTags->images = '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}';
                    }
                    /**
                     * TODO: As of Joomla 3.6.5 there appears to be a bug which exponentially adds backslash escapes with each successive save. After several saves, this breaks the database field character limit, creating errors on subsequnt updates or deletes. For now, this field will be force to "{}".
                     */
                    if($app->request->params('urls')){
                        //$newTags->urls = $app->request->params('urls');
                        $newTags->urls = '{}';
                    }else{
                        // $newTags->urls = '{"0":"{}"}';
                        $newTags->urls = '{}';

                    }
                    if (!$app->request->params('language')) {
                        $newTags->language = '*';
                    } else {
                        $newTags->language = $app->request->params('language');
                    }

                    $data = (array)$newTags;

                    /**
                     * // Bind data
                     *
                     * if (!$table->bind($data))
                     * {
                     * $app->render(401, array(
                     * 'msg' => 'Error: Incorrect format.'
                     * )
                     * );
                     * }
                     *
                     * // Check to make sure our data is valid, raise notice if it's not.
                     * if (!$table->check()) {
                     * $app->render(401, array(
                     * 'msg' => 'Error: Incorrect format.'
                     * )
                     * );
                     * }
                     *
                     * if (!$table->store()) {
                     * $app->render(401, array(
                     * 'msg' => 'Error: Duplicate title or alias.'
                     * )
                     * );
                     * }
                     **/

                    try {
                        $tagsModel->save($data);
                    } catch (Exception $e) {
                        $app->render(401, array(
                                $e->getMessage()
                            )
                        );
                    }

                    if (!$tagsModel->getItem()->id) {
                        $app->render(401, array(
                                'msg' => 'Duplicate title, alias or incorrect parameter value.'
                            )
                        );
                    } else {
                        $app->render(200, array(
                                $tagsModel->getItem()
                            )
                        );
                    }
                }
                $app->render(403, array(
                        'msg' => 'Forbidden'
                    )
                );
            })->name('postTag');

            /**
             * @SWG\Put(
             *     path="/tag/{id}",
             *     summary="Update tag",
             *     operationId="putTag",
             *     tags={"Tags"},
             *
             *     @SWG\Parameter(
             *     description="Tag ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="integer"
             * ),
             *
             *     @SWG\Parameter(
             *     description="Title",
             *     in="query",
             *     name="title",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Parent ID",
             *     in="query",
             *     name="parent_id",
             *     required=false,
             *     type="integer"
             * ),
             *     @SWG\Parameter(
             *     description="Alias",
             *     in="query",
             *     name="alias",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Description",
             *     in="query",
             *     name="description",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Published",
             *     in="query",
             *     name="published",
             *     required=false,
             *     enum={0,1,-2},
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Access Level",
             *     in="query",
             *     name="access",
             *     required=false,
             *     type="integer"
             * ),
             *     @SWG\Parameter(
             *     description="Tag Layout",
             *     in="query",
             *     name="tag_layout",
             *     required=false,
             *     default="",
             *     enum={"","_:list","_:default"},
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Tag Link Class",
             *     in="query",
             *     name="tag_link_class",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Meta Description",
             *     in="query",
             *     name="metadesc",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Meta Keywords",
             *     in="query",
             *     name="metakey",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: Author",
             *     in="query",
             *     name="author",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Metadata: Robots",
             *     in="query",
             *     name="robots",
             *     type="string",
             *     required=false,
             *     @SWG\Items(
             *             type="string",
             *             enum={"index, follow","noindex, follow","index, nofollow","noindex, nofollow"},
             *             default=""
             *      ),
             * ),
             *     @SWG\Parameter(
             *     description="Created-by User ID",
             *     in="query",
             *     name="created_user_id",
             *     required=false,
             *     type="integer"
             * ),
             *     @SWG\Parameter(
             *     description="Created-by Alias",
             *     in="query",
             *     name="created_by_alias",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Image - Intro. URL or relative link.",
             *     in="query",
             *     name="image_intro",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Image - Intro. Float position.",
             *     in="query",
             *     name="float_intro",
             *     type="string",
             *     @SWG\Items(
             *             type="string",
             *             enum={"right","left","none"},
             *             default=""
             *      ),
             *     required=false,
             * ),
             *     @SWG\Parameter(
             *     description="Image - Intro. Alternate text.",
             *     in="query",
             *     name="image_intro_alt",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Image - Intro. Caption.",
             *     in="query",
             *     name="image_intro_caption",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Image - Fulltext. URL or relative link.",
             *     in="query",
             *     name="image_fulltext",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Image - Fulltext. Float position",
             *     in="query",
             *     name="float_fulltext",
             *     @SWG\Items(
             *             type="string",
             *             enum={"right","left","none"},
             *             default=""
             *      ),
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Image - Fulltext. Alternate text.",
             *     in="query",
             *     name="image_fulltext_alt",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Image - Fulltext. Caption.",
             *     in="query",
             *     name="image_fulltext_caption",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Language",
             *     in="query",
             *     name="language",
             *     required=false,
             *     type="string"
             * ),
             *
             *     @SWG\Response(
             *     response="404",
             *     description="Not Found."
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden"
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Error."
             *   ),
             *   @SWG\Response(
             *     response="200",
             *     description="Tag updated successfully."
             *   )
             * )
             */
            $app->put('/:id', function ($id) use ($app) {
                $user = JFactory::getUser();
                if($user->authorise('core.edit','com_tags.component')) {

                    // $tagsHelper = new JHelperTags();

                    JLoader::import('tag', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_tags' . DS . 'models');
                    $tagsModel = JModelLegacy::getInstance('Tag', 'TagsModel', array('ignore_request' => true));

                    JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tags/tables/');
                    $table = JTable::getInstance('Tag', 'TagsTable');

                    if(!$table->load($id)){
                        $app->render(404, array(
                                'msg' => 'Not found.'
                            )
                        );
                    }

                    $newTags = new stdClass();

                    if ($app->request->params('title')) {
                        $newTags->title = $app->request->params('title');
                    }
                    if (!is_numeric($app->request->params('parent_id'))) {
                        $newTags->parent_id = $app->request->params('parent_id');
                    }
                    if ($app->request->params('alias')) {
                        $newTags->alias = $app->request->params('alias');
                    }
                    if ($app->request->params('note')) {
                        $newTags->note = $app->request->params('note');
                    }
                    if ($app->request->params('description')) {
                        $newTags->description = $app->request->params('description');
                    }
                    if (!in_array($app->request->params('published'), array(0, 1, -2), false)) {
                        $newTags->published = $app->request->params('published');
                    }
                    if ($app->request->params('access')) {
                        $newTags->access = $app->request->params('access');
                    }

                    /**
                     * "Params" options
                     */
                    $tagParams = json_decode($table->params);
                    if (in_array($app->request->params('tag_layout'),array('','_:list','_:default'),true)) {
                        $tagParams->tag_layout = $app->request->params('tag_layout');
                    }
                    if ($app->request->params('tag_link_class')) {
                        $tagParams->tag_link_class = $app->request->params('tag_link_class');
                    }
                    $newTags->params = json_encode($tagParams);

                    if ($app->request->params('metadesc')) {
                        $newTags->metadesc = $app->request->params('metadesc');
                    }
                    if ($app->request->params('metakey')) {
                        $newTags->metakey = $app->request->params('metakey');
                    }

                    /**
                     * Metadata parameters
                     */
                    $tagMetadata = json_decode($table->metadata);
                    if ($app->request->params('author')) {
                        $tagMetadata->author = $app->request->params('author');
                    }
                    if ($app->request->params('robots')) {
                        $tagMetadata->robots = $app->request->params('robots');
                    }
                    $newTags->metadata = json_encode($tagMetadata);


                    if (JFactory::getUser($app->request->params('created_user_id'))) {
                        $newTags->created_user_id = $app->request->params('created_user_id');
                    }
                    if ($app->request->params('created_by_alias')) {
                        $newTags->created_by_alias = $app->request->params('created_by_alias');
                    }

                    /**
                     * Images parameters
                     */
                    $tagImages = json_decode($table->images);
                    if ($app->request->params('image_intro')) {
                        $tagImages->image_intro = $app->request->params('image_intro');
                    }
                    if ($app->request->params('float_intro')) {
                        $tagImages->float_intro = $app->request->params('float_intro');
                    }
                    if ($app->request->params('image_intro_alt')) {
                        $tagImages->image_intro_alt = $app->request->params('image_intro_alt');
                    }
                    if ($app->request->params('image_intro_caption')) {
                        $tagImages->image_intro_caption = $app->request->params('image_intro_caption');
                    }
                    if ($app->request->params('image_fulltext')) {
                        $tagImages->image_fulltext = $app->request->params('image_fulltext');
                    }
                    if ($app->request->params('float_fulltext')) {
                        $tagImages->float_fulltext = $app->request->params('float_fulltext');
                    }
                    if ($app->request->params('image_fulltext_alt')) {
                        $tagImages->image_fulltext_alt = $app->request->params('image_fulltext_alt');
                    }
                    if ($app->request->params('image_fulltext_caption')) {
                        $tagImages->image_fulltext_caption = $app->request->params('image_fulltext_caption');
                    }
                    $newTags->images = json_encode($tagImages);

                    /**
                     * TODO: As of Joomla 3.6.5 there appears to be a bug which exponentially adds backslash escapes with each successive save. After several saves, this breaks the database field character limit, creating errors on subsequnt updates or deletes. For now, this field will be force to "{}".
                     */
                    $newTags->urls = '{}';
                    /**
                    if($app->request->params('urls')){
                    $newTags->urls = $app->request->params('urls');
                    }
                     **/
                    if ($app->request->params('language')) {
                        $newTags->language = $app->request->params('language');
                    }

                    $data = (array)$newTags;

                    /**
                     * Bind data
                     */
                    if (!$table->bind($data))
                    {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.'
                            )
                        );
                    }
                    /**
                     * Check to make sure our data is valid, raise notice if it's not.
                     */
                    if (!$table->check()) {
                        $app->render(401, array(
                                'msg' => 'Error: Incorrect format.'
                            )
                        );
                    }
                    /**
                     * Store data
                     */
                    if (!$table->store()) {
                        $app->render(401, array(
                                'msg' => 'Error: Duplicate title or alias.'
                            )
                        );
                    }
                    $tagsModel->rebuild();
                    $app->render(200,
                        get_object_vars($tagsModel->getItem($id))
                    );
                }
                $app->render(403, array(
                        'msg' => 'Forbidden'
                    )
                );
            })->name('putTag');

            /**
             * @SWG\Delete(
             *     path="/tag/{id}",
             *     summary="Delete tag",
             *     operationId="deleteTag",
             *     tags={"Tags"},
             *
             *     @SWG\Parameter(
             *     description="Tag ID",
             *     in="path",
             *     name="id",
             *     required=true,
             *     type="integer"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Tag deleted successfully."
             *   )
             * )
             */
            $app->delete('/:id', function ($id) use ($app) {
                $user = JFactory::getUser();

                if($user->authorise('core.edit','com_tags.component')) {
                    JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tags/tables/');
                    $table = JTable::getInstance('Tag', 'TagsTable');

                    if(!$table->delete($id)){
                        $app->render(404, array(
                                'msg' => 'Tag id '.$id.' not found or delete not permitted.'
                            )
                        );
                    }else{
                        $app->render(200, array(
                                'msg' => 'Deleted tag with id '.$id.'.'
                            )
                        );
                    }

                }
                $app->render(403, array(
                        'msg' => 'Forbidden'
                    )
                );
            })->name('deleteTag');

        });

    }

	/**
	 * Validate JSON
	 * @url https://stackoverflow.com/questions/6041741/fastest-way-to-check-if-a-string-is-json-in-php
	 *
	 * @param $string
	 *
	 * @return mixed
	 * @since 1.3.5
	 */
	protected function json_validate($string)
	{
		// decode the JSON data
		$result = json_decode($string);

		// switch and check possible JSON errors
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				$error = ''; // JSON is valid // No error has occurred
				break;
			case JSON_ERROR_DEPTH:
				$error = 'The maximum stack depth has been exceeded.';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Invalid or malformed JSON.';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error = 'Control character error, possibly incorrectly encoded.';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
				break;
			// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$error = 'One or more recursive references in the value to be encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$error = 'One or more NAN or INF values in the value to be encoded.';
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = 'A value of a type that cannot be encoded was given.';
				break;
			default:
				$error = 'Unknown JSON error occured.';
				break;
		}

		if ($error !== '') {
			// throw the Exception or exit // or whatever :)
			// exit($error);

			return false;
		}

		// everything is OK
		return $result;
	}

    public function call(){
        return $this->next->call();
    }

}