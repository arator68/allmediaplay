<?php
/**
 * @package RK-Softwareentwicklung AllMediaPlay Content Plugin
 * @author RK-Softwareentwicklung
 * @copyright (C) 2013 RK-Softwareentwicklung
 * @version 1.0.0
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * 
 * Inspired by and partially based on:
 *
 *   The "AllVideos" Plugin for Joomla - Version 4.5.0
 *   Authors: JoomlaWorks
 *   Copyright (c) 2006 JoomlaWorks.gr - http://www.joomlaworks.gr
 * */

// no direct access
defined('_JEXEC') or die('Restricted access');

class AllMediaPlayHelper {

	// Path overrides
	public static function getTemplatePath($pluginName, $file, $tmpl)
	{

		$mainframe = JFactory::getApplication();
		$p = new JObject;
		$pluginGroup = 'content';

		if (file_exists(JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$pluginName.DS.$tmpl.DS.str_replace('/', DS, $file)))
		{
			$p->file = JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$pluginName.DS.$tmpl.DS.$file;
			$p->http = JURI::root(true)."/templates/".$mainframe->getTemplate()."/html/{$pluginName}/{$tmpl}/{$file}";
		}
		else
		{
			if (version_compare(JVERSION, '1.6.0', 'ge'))
			{
				// Joomla! 1.6
				$p->file = JPATH_SITE.DS.'plugins'.DS.$pluginGroup.DS.$pluginName.DS.$pluginName.DS.'tmpl'.DS.$tmpl.DS.$file;
				$p->http = JURI::root(true)."/plugins/{$pluginGroup}/{$pluginName}/{$pluginName}/tmpl/{$tmpl}/{$file}";
			}
			else
			{
				// Joomla! 1.5
				$p->file = JPATH_SITE.DS.'plugins'.DS.$pluginGroup.DS.$pluginName.DS.'tmpl'.DS.$tmpl.DS.$file;
				$p->http = JURI::root(true)."/plugins/{$pluginGroup}/{$pluginName}/tmpl/{$tmpl}/{$file}";
			}
		}
		return $p;
	}

} // end class
