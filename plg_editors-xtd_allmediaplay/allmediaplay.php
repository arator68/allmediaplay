<?php

/**
 * @package RK-Softwareentwicklung AllMediaPlay Button
 * @author RK-Softwareentwicklung
 * @copyright (C) 2013 RK-Softwareentwicklung
 * @version 1.0.0
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
// No direct access allowed to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');

//The Button plugin HTML Media
class plgButtonAllMediaPlay extends JPlugin
{

    //$done=0;
    /**
     * Constructor
     *
     * @access      protected
     * @param       object  $subject The object to observe
     * @param       array   $config  An array that holds the plugin configuration
     * @since       1.5
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    function plgButtonAllMediaPlay(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /**
     * Display the button
     *
     * @return array A two element array of (imageName, textToInsert)
     */
    public function onDisplay($name)
    {
        $app = JFactory::getApplication();
        //$document = JFactory::getDocument();
        $this->loadLanguage();

        if ($this->params->get('enable_frontend', 1) == 0 && !$app->isAdmin())
        {
            return null;
        }

        JHTML::_('behavior.modal');
        $link = 'index.php?option=com_allmediaplay&amp;view=insert&amp;tmpl=component&amp;e_name=' . $name;

        JHTML::stylesheet('plugins/editors-xtd/allmediaplay/assets/css/allmediaplay.css');

        $button = new JObject;
        $button->set('text', '<i class="icon-play-2"></i> ' . JText::_('PLG_EDITOR_XLD_ALLVIDEOS_BUTTON_TEXT'));
        $button->set('link', $link);
        $button->set('modal', true);
        $button->set('name', 'allmediaplay');
        $button->set('icon', 'play-2');
        $button->set('title', $name);
        $button->set('options', "{handler: 'iframe', size: {x: 600, y: 650}}");

        return $button;
    }

}
