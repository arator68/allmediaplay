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
jimport('joomla.application.component.controlleradmin');

/**
 * HelloWorlds Controller
 */
class AllMediaPlayControllerTags extends JControllerAdmin
{

    var $_mylink = 'index.php?option=com_allmediaplay&view=tags';

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'tags', $prefix = 'AllMediaPlayModel')
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }
    
    public function delete()
    {
        parent::delete();
    }
    
}
