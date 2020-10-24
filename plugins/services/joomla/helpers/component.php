<?php
/**
 * @version     1.3.6
 * @package     Annatech.Plugin
 * @subpackage  Services.joomla
 *
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @since       1.3.4
 */

defined('_JEXEC') or die;
JLoader::import('joomla.application.component.model');

/**
 * Class ServicesJoomlaHelpersComponent
 * @since 1.3.4
 */
class ServicesJoomlaHelpersComponent  {

	public function __construct()
	{
		$app = \Slim\Slim::getInstance();

		/**
		 * Component Services
		 */
		$app->group('/component', function () use ($app) {

			/**
			 * @SWG\Get(
			 *     path="/component/list/all",
			 *     summary="Returns list of all components.",
			 *     operationId="getComponentListAll",
			 *     tags={"Component"},
			 *
			 *     @SWG\Parameter(
			 *     description="Application context",
			 *     in="query",
			 *     name="context",
			 *     enum={"administrator","site"},
			 *     default="administrator",
			 *     required=false,
			 *     type="string",
			 * ),
			 *
			 *   @SWG\Response(
			 *     response="200",
			 *     description="List of all components"
			 *   ),
			 *     @SWG\Response(
			 *     response="403",
			 *     description="Forbidden"
			 *   )
			 * )
			 */
			$app->get('/list/all', function () use ($app)
			{
				/**
				 * Ref: https://joomla.stackexchange.com/questions/13760/get-all-installed-joomla-extensions-and-their-version
				 */

				$user = JFactory::getUser();

				/**
				 * Require core.login.admin privileges to access complete components list.
				 */
				if($user->authorise('core.login.admin')) {

					$results = $this->getJoomlaComponents();

					$response = array();

					foreach ($results as $extension)
					{
						$extension->manifest_cache = json_decode($extension->manifest_cache);

						/**
						 * Check for table and model definitions.
						 **/

						/**
						 * Import the folder filesystem library
						 **/
						jimport('joomla.filesystem.folder');

						/**
						 * Component context path, defaults to ADMINISTRATOR
						**/
						if($app->request->params('context') === 'site'){
							$comPath = JPATH_SITE .'/components/' . $extension->element;
						}else{
							$comPath = JPATH_SITE.'/administrator/components/' . $extension->element;
						}

						$jclassArray = array('tables','models');
						foreach($jclassArray as $jclass)
						{

							if (JFolder::exists($comPath . DS . $jclass))
							{
								/**
								 * Import the file filesystem library
								 **/
								jimport('joomla.filesystem.file');

								$files = JFolder::files($comPath . DS . $jclass,'.php');

								if(count($files) > 0)
								{
									/**
									 * Declaring in order to comply with error reporting standards.
									 */
									$extension->$jclass = new \stdClass();

									foreach ($files as $file)
									{
										$name = JFile::stripExt($file);
										$extension->$jclass->$name = $this->get_class_from_file($comPath . DS . $jclass . DS . $file);

									}
								}
							}
						}
						array_push($response,(array)$extension);
					}

					$app->render(200,array(
						'components' => $response
						)
					);
				}
				$app->render(403, array(
						'msg' => 'Forbidden',
					)
				);
			})->name('getComponentListAll');

			/**
			 * @SWG\Get(
			 *     path="/component/table/fields",
			 *     summary="Returns component table fields",
			 *     operationId="getComponentTableFields",
			 *     tags={"Component"},
			 *
			 *     @SWG\Parameter(
			 *     description="Component Name",
			 *     in="query",
			 *     name="option",
			 *     required=true,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Table Type",
			 *     in="query",
			 *     name="type",
			 *     required=true,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Table Prefix",
			 *     in="query",
			 *     name="prefix",
			 *     required=false,
			 *     type="string",
			 * ),
			 *
			 *   @SWG\Response(
			 *     response="200",
			 *     description="List of all components"
			 *   ),
			 *     @SWG\Response(
			 *     response="403",
			 *     description="Forbidden"
			 *   )
			 * )
			 */
			$app->get('/table/fields', function () use ($app)
			{
				$user = JFactory::getUser();
				$component  = $app->request->params('option');
				$type       = $app->request->params('type');
				$prefix     = $app->request->params('prefix');

				if($user->authorise('core.edit','com_'.$component.'.component')) {

					JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_' . strtolower($component) . '/tables');
					$instance = JTable::getInstance($type, $prefix);

					if($instance)
					{
						$app->render(200, array(
								'fields'        => $instance->getFields()
							)
						);
					}else{
						$app->render(404, array(
								'msg' => 'Not Found'
							)
						);
					}
				}
				$app->render(403, array(
						'msg' => 'Forbidden',
					)
				);
			})->name('getComponentTableFields');

			/**
			 * @SWG\Get(
			 *     path="/component/table/data/{id}",
			 *     summary="Returns component table row by ID",
			 *     operationId="getComponentTableDataById",
			 *     tags={"Component"},
			 *
			 *     @SWG\Parameter(
			 *     description="ID",
			 *     in="path",
			 *     name="id",
			 *     required=true,
			 *     type="integer",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Name",
			 *     in="query",
			 *     name="option",
			 *     required=true,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Table Type",
			 *     in="query",
			 *     name="type",
			 *     required=true,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Table Prefix",
			 *     in="query",
			 *     name="prefix",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Get Class Methods",
			 *     in="query",
			 *     name="get_class_methods",
			 *     default="0",
			 *     enum={0,1},
			 *     required=false,
			 *     type="integer",
			 * ),
			 *
			 *   @SWG\Response(
			 *     response="200",
			 *     description="Component table row"
			 *   ),
			 *     @SWG\Response(
			 *     response="403",
			 *     description="Forbidden"
			 *   ),
			 *     @SWG\Response(
			 *     response="404",
			 *     description="Not Found"
			 *   ),
			 * )
			 */
			$app->get('/table/data/:id', function ($id) use ($app)
			{
				$user = JFactory::getUser();
				$component  = strtolower($app->request->params('option'));
				$type       = $app->request->params('type');
				$prefix     = $app->request->params('prefix');

				$jclass = 'tables';

				/**
				 * Component context path, defaults to ADMINISTRATOR
				 **/
				$comPath = JPATH_SITE.'/administrator/components/' . 'com_'.$component;

				JTable::addIncludePath($comPath . '/'.$jclass);
				$instance = JTable::getInstance($type, $prefix);

				/**
				 * Validate getInstance() parameters
				 */
				if($instance)
				{
					$instance->load($id);
				}else{
					$app->render(404, array(
							'msg' => 'Not Found'
						)
					);
				}

				/**
				 * Check access
				 * TODO: Improve access control check compatibility with various security modes for core and 3rd party extensions.
				 */
				if (($user->block !== '1' && in_array($instance->access, $user->getAuthorisedViewLevels())) || $user->authorise('core.edit','com_'.$component.'.component')) {
					/**
					 * Recursively json_decode any json encoded fields
					 */
					foreach($instance as $field => $value){
						if($this->json_validator($value)){
							$instance->$field = json_decode($value);
						}
					}

					/**
					 * Get component class methods and arguments
					 */
					if($app->request->params('get_class_methods') && $instance->id !== null && $user->authorise('core.edit','com_'.$component.'.component')){
						$classMethods = get_class_methods($instance);
						$className = get_class($instance);

						/**
						 * Declaring in order to comply with error reporting standards.
						 */
						$instance->class_name->$className = new \stdClass();

						foreach ($classMethods as $method){
							$classMethodArguments = $this->get_class_method_arguments($instance, $method);
							$instance->class_name->$className->$method = $classMethodArguments;
						}
					}

					if($instance->id !== null)
					{
						$app->render(200, array(
								$component => $instance
							)
						);
					}else{
						$app->render(404, array(
								'msg' => 'Not Found'
							)
						);
					}
				}
				$app->render(403, array(
						'msg' => 'Forbidden',
					)
				);
			})->name('getComponentTableDataById');

			/**
			 * @SWG\Get(
			 *     path="/component/model/data",
			 *     summary="Returns component model data",
			 *     operationId="getComponentModelData",
			 *     tags={"Component"},
			 *
			 *     @SWG\Parameter(
			 *     description="Component Name",
			 *     in="query",
			 *     name="option",
			 *     required=true,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Model Type",
			 *     in="query",
			 *     name="type",
			 *     required=true,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Model Prefix",
			 *     in="query",
			 *     name="prefix",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Model Method",
			 *     in="query",
			 *     name="modelMethod",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Model Method Arguments",
			 *     in="query",
			 *     name="modelMethodArguments",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Filter parameter",
			 *     in="query",
			 *     name="filter_param",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Filter parameter value",
			 *     in="query",
			 *     name="filter_value",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Application context",
			 *     in="query",
			 *     name="context",
			 *     enum={"administrator","site"},
			 *     default="administrator",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Get Class Methods",
			 *     in="query",
			 *     name="get_class_methods",
			 *     default="0",
			 *     enum={0,1},
			 *     required=false,
			 *     type="integer",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Return getActiveFilters",
			 *     in="query",
			 *     name="activeFilters",
			 *     default="0",
			 *     enum={0,1},
			 *     required=false,
			 *     type="integer",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Return getProperties",
			 *     in="query",
			 *     name="properties",
			 *     default="0",
			 *     enum={0,1},
			 *     required=false,
			 *     type="integer",
			 * ),
			 *
			 *   @SWG\Response(
			 *     response="200",
			 *     description="Results returned"
			 *   ),
			 *     @SWG\Response(
			 *     response="400",
			 *     description="Invalid request"
			 *   ),
			 *     @SWG\Response(
			 *     response="403",
			 *     description="Forbidden"
			 *   ),
			 *     @SWG\Response(
			 *     response="404",
			 *     description="Not Found"
			 *   ),
			 * )
			 */
			$app->get('/model/data', function () use ($app)
			{
				$user = JFactory::getUser();
				$component  = strtolower($app->request->params('option'));
				$type       = $app->request->params('type');
				$prefix     = $app->request->params('prefix');

				$jclass = 'models';

				/**
				 * Component context path, defaults to ADMINISTRATOR
				 **/
				if($app->request->params('context') === 'site'){
					$comPath = JPATH_SITE .'/components/' . 'com_'.$component;
				}else{
					$comPath = JPATH_SITE.'/administrator/components/'. 'com_'.$component;
				}

				$config = array(
					'ignore_request' => true,
					);

				JModelLegacy::addIncludePath($comPath . '/'.$jclass);

				$instance = new stdClass();
				try {
					$instance = JModelLegacy::getInstance($type, $prefix, $config);
				} catch (Exception $e) {
					$app->render(400, array(
							'msg' => $e->getMessage()
						)
					);
				}

				/**
				 * Validate getInstance() parameters
				 */
				if($instance === false || $instance === null)
				{
					$app->render(404, array(
							'msg' => 'Not Found'
						)
					);
				}

				$appParams = JComponentHelper::getParams($component);
				$instance->setState('params', $appParams);
				if($app->request->params('filter_param')){
					$instance->setState($filter_param = $app->request->params('filter_param'), $app->request->params('filter_value'));
				}

				/**
				 * Check access
				 */
				if ($user->id !== 0) {
					/**
					 * Recursively json_decode any json encoded fields
					 */
					foreach($instance as $field => $value){
						if ($this->json_validator($value))
						{
							$instance->$field = json_decode($value);
						}
					}

					/**
					 * Get component class methods and arguments
					 */
					if($app->request->params('get_class_methods') && $instance !== null && $user->authorise('core.edit','com_'.$component.'.component')){
						$classMethods = get_class_methods($instance);
						$className = get_class($instance);

						/**
						 * Declaring in order to comply with error reporting standards.
						 */
						$instance->class_name->$className = new \stdClass();

						foreach ($classMethods as $method){
							$classMethodArguments = $this->get_class_method_arguments($instance, $method);
							$instance->class_name->$className->$method = $classMethodArguments;
						}
					}

					/**
					 * Item access is granted under the following conditions:
					 * - core.edit privilege on associated component
					 * - core.edit privilege on associsated item asset
					 * - matching access-level for user and each model item
					 */
					$instance->items = array();

					/**
					 * Set default Model method
					 */
					$modelMethod = 'getItems';

					if($app->request->params('modelMethod')){
						$modelMethod = (string)$app->request->params('modelMethod');

						/**
						 * Validate $modelMethod
						 */
						if(substr($modelMethod, 0, 3) !== 'get'){
							$app->render(403, array(
									'msg' => 'Forbidden Model method "'.$modelMethod.'". Only getter methods are allowed here.'
								)
							);
						}
					}

					/**
					 * Get Model Method arguments if they have been supplied by the requester
					 */
					$modelMethodArguments = array();
					if($app->request->params('modelMethodArguments') !== null && $this->json_validator($app->request->params('modelMethodArguments'))){
						$modelMethodArguments = json_decode($app->request->params('modelMethodArguments'));
					}

					/**
					 * Get Model item list
					 * ref: https://stackoverflow.com/questions/980708/calling-method-of-object-of-object-with-call-user-func
					 */
					foreach( call_user_func_array(array($instance,$modelMethod), $modelMethodArguments) as $item)
					{
						if ($user->authorise('core.edit', 'com_' . $component . '.component')
							|| $user->authorise('core.edit','com_'.$component.'.'.rtrim($type,"s").'.'.$item->id)
							|| in_array($item->access, JAccess::getAuthorisedViewLevels($user->id))
						)
						{
							array_push($instance->items, (array) $item);
						}
					}

					/**
					 * getActiveFilters
					 */
					if($app->request->params('activeFilters')){
						$instance->activeFilters = $instance->getActiveFilters();
					}

					/**
					 * getProperties
					 */
					if($app->request->params('properties')){
						$properties = $instance->getProperties();
						$instance->properties->context = $properties['context'];
						$instance->properties->query = $properties['query'];
						$instance->properties->filterBlacklist = $properties['filterBlacklist'];
						$instance->properties->listBlacklist = $properties['listBlacklist'];
						$instance->properties->name = $properties['name'];
						$instance->properties->option = $properties['option'];

						/**
						 * Get usable filter fields
						 */

						// Import the folder system library
						jimport('joomla.filesystem.folder');
						jimport('joomla.document.document');

						if (JFolder::exists($comPath . '/models/forms'))
						{
							$files = JFolder::files($comPath . '/models/forms',
								$filter = '.xml',
								false,
								false,
								''
							);

							foreach ($files as $file)
							{
								if(substr( $file, 0, 7 ) === "filter_"){
									$xml = simplexml_load_file($comPath . '/models/forms/'.$file,'SimpleXMLElement');

									foreach($xml->children() as $xmlFields){
										if( (string)$xmlFields['name'] === 'filter')
										{
											$filter_field_names_array = array();
											foreach ($xmlFields as $filter_field_names)
											{
												array_push($filter_field_names_array, (string)$filter_field_names['name']);

											}
											$instance->properties->filter_fields->$file->name = $filter_field_names_array;
										}
									}
								}
							}
						}
					}

					if($instance)
					{
						$app->render(200, array(
								$component => $instance
							)
						);
					}else{
						$app->render(404, array(
								'msg' => 'Not Found'
							)
						);
					}
				}
				$app->render(403, array(
						'msg' => 'Forbidden',
					)
				);
			})->name('getComponentModelData');

			/**
			 * @SWG\Get(
			 *     path="/component/model",
			 *     summary="Returns component model information",
			 *     operationId="getComponentModel",
			 *     tags={"Component"},
			 *
			 *     @SWG\Parameter(
			 *     description="Component Name",
			 *     in="query",
			 *     name="option",
			 *     required=true,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Model Type",
			 *     in="query",
			 *     name="type",
			 *     required=true,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Component Model Prefix",
			 *     in="query",
			 *     name="prefix",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Application context",
			 *     in="query",
			 *     name="context",
			 *     enum={"administrator","site"},
			 *     default="administrator",
			 *     required=false,
			 *     type="string",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Get Class Methods",
			 *     in="query",
			 *     name="get_class_methods",
			 *     default="0",
			 *     enum={0,1},
			 *     required=false,
			 *     type="integer",
			 * ),
			 *     @SWG\Parameter(
			 *     description="Return getProperties",
			 *     in="query",
			 *     name="properties",
			 *     default="0",
			 *     enum={0,1},
			 *     required=false,
			 *     type="integer",
			 * ),
			 *
			 *   @SWG\Response(
			 *     response="200",
			 *     description="Model information returned"
			 *   ),
			 *     @SWG\Response(
			 *     response="400",
			 *     description="Invalid request"
			 *   ),
			 *     @SWG\Response(
			 *     response="403",
			 *     description="Forbidden"
			 *   ),
			 *     @SWG\Response(
			 *     response="404",
			 *     description="Not Found"
			 *   ),
			 * )
			 */
			$app->get('/model', function () use ($app)
			{
				$user = JFactory::getUser();
				$component  = strtolower($app->request->params('option'));
				$type       = $app->request->params('type');
				$prefix     = $app->request->params('prefix');

				$jclass = 'models';

				/**
				 * Component context path, defaults to ADMINISTRATOR
				 **/
				if($app->request->params('context') === 'site'){
					$comPath = JPATH_SITE .'/components/' . 'com_'.$component;
				}else{
					$comPath = JPATH_SITE.'/administrator/components/'. 'com_'.$component;
				}

				$config = array(
					'ignore_request' => true,
				);

				JModelLegacy::addIncludePath($comPath . '/'.$jclass);

				$instance = new stdClass();
				try {
					$instance = JModelLegacy::getInstance($type, $prefix, $config);
				} catch (Exception $e) {
					$app->render(400, array(
							'msg' => $e->getMessage()
						)
					);
				}

				/**
				 * Validate getInstance() parameters
				 */
				if($instance === false || $instance === null)
				{
					$app->render(404, array(
							'msg' => 'Not Found'
						)
					);
				}

				/**
				 * Check access
				 */
				if ($user->authorise('core.login.admin')) {
					/**
					 * Recursively json_decode any json encoded fields
					 */
					foreach($instance as $field => $value){
						if ($this->json_validator($value))
						{
							$instance->$field = json_decode($value);
						}
					}

					/**
					 * Get component class methods and arguments
					 */
					if($app->request->params('get_class_methods') && $instance !== null && $user->authorise('core.edit','com_'.$component.'.component')){
						$classMethods = get_class_methods($instance);
						$className = get_class($instance);

						/**
						 * Declaring in order to comply with error reporting standards.
						 */
						$instance->class_name->$className = new \stdClass();

						foreach ($classMethods as $method){
							$classMethodArguments = $this->get_class_method_arguments($instance, $method);
							$instance->class_name->$className->$method = $classMethodArguments;
						}
					}

					/**
					 * getProperties
					 */
					if($app->request->params('properties')){
						$properties = $instance->getProperties();
						$instance->properties->context = $properties['context'];
						$instance->properties->query = $properties['query'];
						$instance->properties->filterBlacklist = $properties['filterBlacklist'];
						$instance->properties->listBlacklist = $properties['listBlacklist'];
						$instance->properties->name = $properties['name'];
						$instance->properties->option = $properties['option'];

						/**
						 * Get usable filter fields
						 */

						// Import the folder system library
						jimport('joomla.filesystem.folder');
						jimport('joomla.document.document');

						if (JFolder::exists($comPath . '/models/forms'))
						{
							$files = JFolder::files($comPath . '/models/forms',
								$filter = '.xml',
								false,
								false,
								''
							);

							foreach ($files as $file)
							{
								if(substr( $file, 0, 7 ) === "filter_"){
									$xml = simplexml_load_file($comPath . '/models/forms/'.$file,'SimpleXMLElement');

									foreach($xml->children() as $xmlFields){
										if( (string)$xmlFields['name'] === 'filter')
										{
											$filter_field_names_array = array();
											foreach ($xmlFields as $filter_field_names)
											{
												array_push($filter_field_names_array, (string)$filter_field_names['name']);

											}
											$instance->properties->filter_fields->$file->name = $filter_field_names_array;
										}
									}
								}
							}
						}
					}

					if($instance)
					{
						$app->render(200, array(
								$component => $instance
							)
						);
					}else{
						$app->render(404, array(
								'msg' => 'Not Found'
							)
						);
					}
				}
				$app->render(403, array(
						'msg' => 'Forbidden',
					)
				);
			})->name('getComponentModel');

		});

	}

	/**
	 * SQL query to return list of all joomla components from extensions table.
	 *
	 * @return mixed
	 * @since 1.3.4
	 */
	private function getJoomlaComponents() {
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		/**
		$query->select($db->quoteName(array('name', 'manifest_cache')))
		->from($db->quoteName('#__extensions'));
		 */

		$fields = array(
			'extension_id',
			'package_id',
			'name',
			'type',
			'element',
			'enabled',
			'manifest_cache',
			//'params'
		);

		$query->select($db->quoteName($fields))
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('type') . ' = ' . $db->quote('component'));

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get
	 * ref: http://jarretbyrne.com/2015/06/197/
	 *
	 * @param $path_to_file
	 *
	 * @return mixed|string
	 * @since 1.3.4
	 */
	private function get_class_from_file($path_to_file)
	{
		//Grab the contents of the file
		$contents = file_get_contents($path_to_file);

		//Start with a blank namespace and class
		$namespace = $class = '';

		//Set helper values to know that we have found the namespace/class token and need to collect the string values after them
		$getting_namespace = $getting_class = false;

		//Go through each token and evaluate it as necessary
		foreach (token_get_all($contents) as $token) {

			//If this token is the namespace declaring, then flag that the next tokens will be the namespace name
			if (is_array($token) && $token[0] == T_NAMESPACE) {
				$getting_namespace = true;
			}

			//If this token is the class declaring, then flag that the next tokens will be the class name
			if (is_array($token) && $token[0] == T_CLASS) {
				$getting_class = true;
			}

			//While we're grabbing the namespace name...
			if ($getting_namespace === true) {

				//If the token is a string or the namespace separator...
				if(is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {

					//Append the token's value to the name of the namespace
					$namespace .= $token[1];

				}
				else if ($token === ';') {

					//If the token is the semicolon, then we're done with the namespace declaration
					$getting_namespace = false;

				}
			}

			//While we're grabbing the class name...
			if ($getting_class === true) {

				//If the token is a string, it's the name of the class
				if(is_array($token) && $token[0] == T_STRING) {

					//Store the token's value as the class name
					$class = $token[1];

					//Got what we need, stope here
					break;
				}
			}
		}

		//Build the fully-qualified class name and return it
		return $namespace ? $namespace . '\\' . $class : $class;

	}

	/**
	 * Get class method arguments
	 * ref: https://stackoverflow.com/a/3387672/5361267
	 *
	 * @param $className
	 * @param $methodName
	 *
	 * @return ReflectionParameter[]
	 * @since 1.3.4
	 */
	protected function get_class_method_arguments($className, $methodName){
		$r = new ReflectionMethod($className, $methodName);
		return $r->getParameters();
	}

	/**
	 * JSON Validator
	 * @param null $data
	 *
	 * @return bool
	 * @since 1.3.4
	 */
	protected function json_validator($data=NULL) {
		if (!empty($data)) {
			@json_decode($data);
			return (json_last_error() === JSON_ERROR_NONE);
		}
		return false;
	}

	public function call(){
		return $this->next->call();
	}

}