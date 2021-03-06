<?php

/**
 * @package RK-Softwareentwicklung AllMediaPlay Component
 * @author RK-Softwareentwicklung
 * @copyright (C) 2013 RK-Softwareentwicklung
 * @version 1.0.0
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controllerform');

/**
 * Player Controller
 */
class AllMediaPlayControllerPlayer extends JControllerForm
{
    public function getModel($name = 'player', $prefix = 'AllMediaPlayModel')
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }
    
    var $_mylink = 'index.php?option=com_allmediaplay&view=allmediaplays';
    
    public function cancel($key = NULL)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        
        $msg = JText::_('COM_ALLMEDIAPLAY_MESSAGE_CANCEL');
        $this->setRedirect($this->_mylink, $msg);
    }
    
    public function save($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $task = $this->getTask();
        if ($task=="save")
        {
            $this->view_list = "allmediaplays";
        }
        parent::save($key, $urlVar);
    }
    
    public function delete()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel();
        $model->delete();
        $msg = JText::_('COM_ALLMEDIAPLAY_MESSAGE_CANCEL');
        $this->setRedirect($this->_mylink, $msg);
    }
    
}
