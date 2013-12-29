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

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of AllMediaPlay component
 */
class AllMediaPlayController extends JController
{

    /**
     * display task
     *
     * @return void
     */
    function display($cachable = false)
    {
//        $view = JRequest::getCmd('view', null);
//        if (empty($view))
//        {
//            // set default view if not set
//            JRequest::setVar('view', JRequest::getCmd('view', 'AllMediaPlays'));  
//        }
//        else
//        {
//            $view = $this->getView(JRequest::getVar('view'), 'html');
//            $view->setModel($this->getModel('tags'));
//        }
//        // call parent behavior
//        parent::display($cachable);
//
//        // Set the submenu
//        $actview = $this->getView(JRequest::getVar('view'), 'html');
//        AllMediaPlayHelper::addSubmenu($actview->getName());
        
       // set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'AllMediaPlays'));

		// call parent behavior
		parent::display($cachable);

		// Set the submenu
		AllMediaPlayHelper::addSubmenu('messages'); 
    }

}
