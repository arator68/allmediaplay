<?php

/**
 * @version		$Id: player.php 980 2008-06-23 18:54:52Z Fritz Elfert $
 * @copyright	Copyright (C) 2008 Fritz Elfert. All rights reserved.
 * @license		GNU/GPLv2
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * AllVideos Reloaded Player Model
 */
class AllMediaPlayModelPlayer extends JModelAdmin
{

    protected function allowEdit($data = array(), $key = 'id')
    {
        // Check specific edit permission then general edit permission.
        return JFactory::getUser()->authorise('core.edit', 'com_allmediaplay.message.' . ((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
    }

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_allmediaplay.player', 'player', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }
        return $form;
    }
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
    public function getScript()
    {
        return 'administrator/components/com_allmediaplay/models/forms/player.js';
    }
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_allmediaplay.edit.player.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }
        return $data;
    }

    public function getTable($type = 'Player', $prefix = 'AllMediaPlayTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

}
