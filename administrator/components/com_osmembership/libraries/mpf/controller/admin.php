<?php
/**
 * @package     MPF
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2016 Ossolution Team, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Base class for a Joomla Administrator Controller. It handles add, edit, delete, publish, unpublish records....
 *
 * @package       MPF
 * @subpackage    Controller
 * @since         2.0
 */
class MPFControllerAdmin extends MPFController
{
	/**
	 * The URL view item variable.
	 *
	 * @var string
	 */
	protected $viewItem;

	/**
	 * The URL view list variable.
	 *
	 * @var string
	 */
	protected $viewList;

	/**
	 * Constructor.
	 *
	 * @param array $config An optional associative array of configuration settings.
	 *
	 * @see MPFControlleAdmin
	 */
	public function __construct(MPFInput $input = null, array $config = array())
	{
		parent::__construct($input, $config);

		if (isset($config['view_item']))
		{
			$this->viewItem = $config['view_item'];
		}
		else
		{
			$this->viewItem = $this->name;
		}

		if (isset($config['view_list']))
		{
			$this->viewList = $config['view_list'];
		}
		else
		{
			$this->viewList = MPFInflector::pluralize($this->viewItem);
		}

		// Register tasks mapping
		$this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('save2copy', 'save');
		$this->registerTask('unpublish', 'publish');
		$this->registerTask('orderup', 'reorder');
		$this->registerTask('orderdown', 'reorder');
	}

	/**
	 * Display Form allows adding a new record
	 */
	public function add()
	{
		if ($this->allowAdd())
		{
			$this->input->set('cid', []);
			$this->input->set('id', 0);
			$this->input->set('view', $this->viewItem);
			$this->input->set('edit', false);
			$this->display();
		}
		else
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'), 'error');
			$this->setRedirect(JRoute::_($this->getViewListUrl(), false));
		}
	}

	/**
	 * Display Form allows editing record
	 */
	public function edit()
	{
		$cid = $this->input->get('cid', array(), 'array');

		if (count($cid))
		{
			$this->input->set('id', 0);
		}

		if ($this->allowEdit(array('id' => $this->input->getInt('id'))))
		{
			$this->input->set('view', $this->viewItem);
			$this->input->set('edit', false);
			$this->display();
		}
		else
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'), 'error');
			$this->setRedirect(JRoute::_($this->getViewListUrl(), false));
		}
	}

	/**
	 * Method to save a record.
	 *
	 * @return boolean True if successful, false otherwise.
	 */
	public function save()
	{
		$this->csrfProtection();
		$input = $this->input;
		$task  = $this->getTask();

		if ($task == 'save2copy')
		{
			$input->set('id', 0);
			$task = 'apply';
		}

		$id = $input->getInt('id', 0);

		if ($this->allowSave(array('id' => $id)))
		{
			try
			{
				$model = $this->getModel($this->name, array('default_model_class' => 'MPFModelAdmin'));
				$model->store($this->input);

				if ($this->app->isSite() && $id == 0)
				{
					$langSuffix = '_SUBMIT_SAVE_SUCCESS';
				}
				else
				{
					$langSuffix = '_SAVE_SUCCESS';
				}

				$languagePrefix = $this->config['language_prefix'];
				$msg            = JText::_((JFactory::getLanguage()->hasKey($languagePrefix . $langSuffix) ? $languagePrefix : 'JLIB_APPLICATION') . $langSuffix);

				switch ($task)
				{
					case 'apply':
						$url = JRoute::_($this->getViewItemUrl($input->getInt('id', 0)), false);
						break;
					case 'save2new':
						$url = JRoute::_($this->getViewItemUrl(), false);
						break;
					default:
						$url = JRoute::_($this->getViewListUrl(), false);
						break;
				}

				$this->setRedirect($url, $msg);
			}
			catch (Exception $e)
			{
				$this->setMessage($e->getMessage(), 'error');
				$this->setRedirect(JRoute::_($this->getViewItemUrl($id), false));
			}
		}
		else
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'), 'error');
			$this->setRedirect(JRoute::_($this->getViewListUrl(), false));
		}
	}

	/**
	 * Method to cancel an add/edit. We simply redirect users to view which display list of records
	 */
	public function cancel()
	{
		$this->setRedirect(JRoute::_($this->getViewListUrl(), false));
	}

	/**
	 * Method to save the submitted ordering values for records.
	 *
	 * @return boolean True on success
	 */
	public function saveorder()
	{
		// Check for request forgeries.
		$this->csrfProtection();
		$order = $this->input->get('order', array(), 'array');
		$cid   = $this->input->get('cid', array(), 'array');

		// Sanitize the data
		$order = ArrayHelper::toInteger($order);
		$cid   = ArrayHelper::toInteger($cid);

		for ($i = 0, $n = count($cid); $i < $n; $i++)
		{
			if (!$this->allowEditState($cid[$i]))
			{
				unset($cid[$i]);
			}
		}

		if (count($cid))
		{
			try
			{
				/* @var MPFModelAdmin $model */
				$model = $this->getModel($this->name, array('default_model_class' => 'MPFModelAdmin', 'ignore_request' => true));
				$model->saveorder($cid, $order);
				$this->setMessage(JText::_('JLIB_APPLICATION_SUCCESS_ORDERING_SAVED'));
			}
			catch (Exception $e)
			{
				$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $e->getMessage()), 'error');
			}
		}
		else
		{
			$languagePrefix = $this->config['language_prefix'];
			$this->setMessage($languagePrefix . '_NO_ITEM_SELECTED', 'warning');
		}

		$this->setRedirect(JRoute::_($this->getViewListUrl(), false));
	}

	/**
	 * Changes the order of one or more records.
	 *
	 * @return boolean True on success
	 */
	public function reorder()
	{
		// Check for request forgeries.
		$this->csrfProtection();
		$cid = $this->input->post->get('cid', array(), 'array');

		// Sanitize the data
		$cid = ArrayHelper::toInteger($cid);

		if (count($cid) && $this->allowEditState($cid[0]))
		{
			try
			{
				$task = $this->getTask();
				$inc  = ($task == 'orderup' ? -1 : 1);

				/* @var MPFModelAdmin $model */
				$model = $this->getModel($this->name, array('default_model_class' => 'MPFModelAdmin', 'ignore_request' => true));
				$model->reorder($cid, $inc);
				$this->setMessage(JText::_('JLIB_APPLICATION_SUCCESS_ITEM_REORDERED'), 'message');
			}
			catch (Exception $e)
			{
				$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $e->getMessage()), 'error');
			}
		}
		else
		{
			$languagePrefix = $this->config['language_prefix'];
			$this->setMessage($languagePrefix . '_NO_ITEM_SELECTED', 'warning');
		}

		$this->setRedirect(JRoute::_($this->getViewListUrl(), false));
	}

	/**
	 * Delete selected items
	 *
	 * @return void
	 */
	public function delete()
	{
		// Check for request forgeries
		$this->csrfProtection();

		// Get items to remove from the request.
		$cid = $this->input->get('cid', array(), 'array');

		// Sanitize the data
		$cid = ArrayHelper::toInteger($cid);

		for ($i = 0, $n = count($cid); $i < $n; $i++)
		{
			if (!$this->allowDelete($cid[$i]))
			{
				unset($cid[$i]);
			}
		}

		$languagePrefix = $this->config['language_prefix'];

		if (count($cid))
		{
			try
			{
				/* @var MPFModelAdmin $model */
				$model = $this->getModel($this->name, array('default_model_class' => 'MPFModelAdmin', 'ignore_request' => true));
				$model->delete($cid);
				$this->setMessage(JText::plural($languagePrefix . '_N_ITEMS_DELETED', count($cid)));
			}
			catch (Exception $e)
			{
				$this->setMessage($e->getMessage(), 'error');
			}
		}
		else
		{
			$this->setMessage($languagePrefix . '_NO_ITEM_SELECTED', 'warning');
		}

		$this->setRedirect(JRoute::_($this->getViewListUrl(), false));
	}

	/**
	 * Method to publish a list of items
	 *
	 * @return void
	 */
	public function publish()
	{
		// Check for request forgeries
		$this->csrfProtection();

		// Get items to publish from the request.
		$cid  = $this->input->get('cid', array(), 'array');
		$data = array('publish' => 1, 'unpublish' => 0, 'archive' => 2);
		$task = $this->getTask();

		$published = ArrayHelper::getValue($data, $task, 0, 'int');
		$cid       = ArrayHelper::toInteger($cid);

		for ($i = 0, $n = count($cid); $i < $n; $i++)
		{
			if (!$this->allowEditState($cid[$i]))
			{
				unset($cid[$i]);
			}
		}

		$languagePrefix = $this->config['language_prefix'];

		if (count($cid))
		{
			try
			{
				/* @var MPFModelAdmin $model */
				$model = $this->getModel($this->name, array('default_model_class' => 'MPFModelAdmin', 'ignore_request' => true));
				$model->publish($cid, $published);

				switch ($published)
				{
					case 0:
						$ntext = $languagePrefix . '_N_ITEMS_UNPUBLISHED';
						break;
					case 1:
						$ntext = $languagePrefix . '_N_ITEMS_PUBLISHED';
						break;
					case 2:
						$ntext = $languagePrefix . '_N_ITEMS_ARCHIVED';
						break;
				}

				$this->setMessage(JText::plural($ntext, count($cid)));
			}
			catch (Exception $e)
			{
				$msg = null;
				$this->setMessage($e->getMessage(), 'error');
			}
		}
		else
		{
			$this->setMessage($languagePrefix . '_NO_ITEM_SELECTED', 'warning');
		}

		$this->setRedirect(JRoute::_($this->getViewListUrl(), false));
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   2.0
	 */
	public function save_order_ajax()
	{
		// Get the input
		$pks   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		$pks   = ArrayHelper::toInteger($pks);
		$order = ArrayHelper::toInteger($order);

		// Get the model
		/* @var MPFModelAdmin $model */
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		$this->app->close();
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array $data An array of input data.
	 *
	 * @return  boolean
	 */
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();

		return $user->authorise('core.create', $this->option);
	}

	/**
	 * Method to check if you can edit a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array  $data An array of input data.
	 * @param   string $key  The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		return JFactory::getUser()->authorise('core.edit', $this->option);
	}

	/**
	 * Method to check if you can save a new or existing record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array  $data An array of input data.
	 * @param   string $key  The name of the key for the primary key.
	 *
	 * @return  boolean
	 */
	protected function allowSave($data, $key = 'id')
	{
		$recordId = isset($data[$key]) ? $data[$key] : '0';

		if ($recordId)
		{
			return $this->allowEdit($data, $key);
		}

		return $this->allowAdd($data);
	}

	/**
	 * Method to check whether the current user is allowed to delete a record
	 *
	 * @param   int $id Record ID
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
	 */
	protected function allowDelete($id)
	{
		return JFactory::getUser()->authorise('core.delete', $this->option);
	}

	/**
	 * Method to check whether the current user can change status (publish, unpublish of a record)
	 *
	 * @param   int $id Id of the record
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
	 */
	protected function allowEditState($id)
	{
		return JFactory::getUser()->authorise('core.edit.state', $this->option);
	}

	/**
	 * Get url of the page which display list of records
	 *
	 * @return string
	 */
	protected function getViewListUrl()
	{
		return 'index.php?option=' . $this->option . '&view=' . $this->viewList;
	}

	/**
	 * Get url of the page which allow adding/editing a record
	 *
	 * @param int $recordId
	 *
	 * @return string
	 */
	protected function getViewItemUrl($recordId = null)
	{
		$url = 'index.php?option=' . $this->option . '&view=' . $this->viewItem;

		if ($recordId)
		{
			$url .= '&id=' . $recordId;
		}

		return $url;
	}
}
