<?php

/**
 * @package RK-Softwareentwicklung AllMediaPlay Component
 * @author RK-Softwareentwicklung
 * @copyright (C) 2013 RK-Softwareentwicklung
 * @version 1.0.0
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * HelloWorld component helper.
 */
abstract class AllMediaPlayHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
                JSubMenuHelper::addEntry(JText::_('COM_ALLMEDIAPLAY_SUBMENU_PLAYER'), 'index.php?option=com_allmediaplay', $submenu == 'allmediaplays');
		JSubMenuHelper::addEntry(JText::_('COM_ALLMEDIAPLAY_SUBMENU_TAGS'), 'index.php?option=com_allmediaplay&view=tags', $submenu == 'tags');
                JSubMenuHelper::addEntry(JText::_('COM_ALLMEDIAPLAY_SUBMENU_PLAYLIST'), 'index.php?option=com_allmediaplay&view=playlists', $submenu == 'playlists');
		//JSubMenuHelper::addEntry(JText::_('COM_ALLMEDIAPLAY_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_allmediaplay', $submenu == 'categories');
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-allmediaplay {background-image: url('.JURI::root().'media/com_allmediaplay/images/allmediaplay-48x48.png);}');
		/*if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_ALLMEDIAPLAY_ADMINISTRATION_CATEGORIES'));
		}*/
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($messageId)) {
			$assetName = 'com_allmediaplay';
		}
		else {
			$assetName = 'com_allmediaplay.message.'.(int) $messageId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}

