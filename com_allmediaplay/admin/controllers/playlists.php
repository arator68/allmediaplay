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
class AllMediaPlayControllerPlayLists extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Playlists', $prefix = 'AllMediaPlayModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}

