<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Documentation controller class.
 */
class ServicesControllerDocumentation extends ServicesController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_services.edit.documentation.id');
        $editId = $app->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_services.edit.documentation.id', $editId);

        // Get the model.
        $model = $this->getModel('Documentation', 'ServicesModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId && $previousId !== $editId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_services&view=documentationform&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function publish() {
        // Initialise variables.
        $app = JFactory::getApplication();

        //Checking if the user can remove object
        $user = JFactory::getUser();
        if ($user->authorise('core.edit', 'com_services') || $user->authorise('core.edit.state', 'com_services')) {
            $model = $this->getModel('Documentation', 'ServicesModel');

            // Get the user data.
            $id = $app->input->getInt('id');
            $state = $app->input->getInt('state');

            // Attempt to save the data.
            $return = $model->publish($id, $state);

            // Check for errors.
            if ($return === false) {
                $this->setMessage(JText::sprintf('Save failed: %s', $model->getError()), 'warning');
            }

            // Clear the profile id from the session.
            $app->setUserState('com_services.edit.documentation.id', null);

            // Flush the data from the session.
            $app->setUserState('com_services.edit.documentation.data', null);

            // Redirect to the list screen.
            $this->setMessage(JText::_('COM_SERVICES_ITEM_SAVED_SUCCESSFULLY'));
            $menu = & JSite::getMenu();
            $item = $menu->getActive();
            if (!$item) {
                // If there isn't any menu item active, redirect to list view
                $this->setRedirect(JRoute::_('index.php?option=com_services&view=documentations', false));
            } else {
                $this->setRedirect(JRoute::_($item->link . $menuitemid, false));
            }
        } else {
            throw new Exception(500);
        }
    }

    public function remove() {

        // Initialise variables.
        $app = JFactory::getApplication();

        //Checking if the user can remove object
        $user = JFactory::getUser();
        if ($user->authorise($user->authorise('core.delete', 'com_services'))) {
            $model = $this->getModel('Documentation', 'ServicesModel');

            // Get the user data.
            $id = $app->input->getInt('id', 0);

            // Attempt to save the data.
            $return = $model->delete($id);


            // Check for errors.
            if ($return === false) {
                $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            } else {
                // Check in the profile.
                if ($return) {
                    $model->checkin($return);
                }

                // Clear the profile id from the session.
                $app->setUserState('com_services.edit.documentation.id', null);

                // Flush the data from the session.
                $app->setUserState('com_services.edit.documentation.data', null);

                $this->setMessage(JText::_('COM_SERVICES_ITEM_DELETED_SUCCESSFULLY'));
            }

            // Redirect to the list screen.
            $menu = & JSite::getMenu();
            $item = $menu->getActive();
            $this->setRedirect(JRoute::_($item->link, false));
        } else {
            throw new Exception(500);
        }
    }

}
