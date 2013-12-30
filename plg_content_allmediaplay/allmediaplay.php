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

defined('_JEXEC') or die("Direct Access Is Not Allowed");
jimport('joomla.plugin.plugin');
if (version_compare(JVERSION, '1.6.0', 'ge'))
{
    jimport('joomla.html.parameter');
}

class plgContentAllMediaPlay extends JPlugin
{

    var $_version = '1.0.0';
    var $_rev = '$Revision: 1 $';
    // Our standard header
    var $plg_copyrights_start = "\n\n<!-- AllMediaPlay Plugin starts here-->\n";
    // Our standard trailer
    var $plg_copyrights_end = "\n<!--AllMediaPlay Plugin ends here -->\n\n";
    var $plg_name = "allmediaplay";
    var $lwplayer_key="<script type=/"text/javascript/">jwplayer.key=/"ABCDEFGHIJKLMOPQ/";</script>";

    ///// Content plugin API interface starts here

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param object $subject The object to observe
     * @param object $params  The object that holds the plugin parameters
     * @param int    $special Used internally
     * @since 1.5
     */
    function plgContentAllMediaPlay(&$subject, $params)
    {
        parent::__construct($subject, $params);

        // Define the DS constant under Joomla! 3.0
        if (!defined('DS'))
        {
            define('DS', DIRECTORY_SEPARATOR);
        }
        //$this->loadLanguage();
        //$this->_init();
    }

    /**
     * Main prepare content method
     * Method is called by the view
     *
     * @param       object          The article object.  Note $article->text
     *                              is also available
     * @param       object          The article params
     * @param       int             The 'page' number
     */
    // Joomla! 1.5
    function onPrepareContent(&$row, &$params, $page = 0)
    {
        $this->RenderAllVideos($row, $params, $page = 0);
    }

    // Joomla! 2.5+
    function onContentPrepare($context, &$row, &$params, $page = 0)
    {
        //$row->text = $this->_doSubstitution($row->text);
        $this->RenderAllVideos($row, $params, $page = 0);
    }

    /**
     * Local initialization
     */
    function _init()
    {
        // Joomla's plugin-installer does not handle separate language files for
        // backend and frontend if installing a plugin (kind of silly, as it works
        // with components). Instead, it installs the language files always into the
        // JPATH_ADMINISTRATOR/languages. Therefore we have to specify this path
        // explicitely.
        $language_name = 'plg_content_' . $this->plg_name;
        JPlugin::loadLanguage($language_name, JPATH_ADMINISTRATOR);
        //JPlugin::loadLanguage('plg_content_allmediaplay', JPATH_ADMINISTRATOR);
        $mparams = & JComponentHelper::getParams('com_media');
        $this->_mloc = JURI::root() . '/' . $mparams->get('image_path', 'images/stories') . '/';
        $this->_rdir = JPATH_PLUGINS . DS . 'content' . DS . 'allmediaplay' . DS;
        $this->_rlocr = 'plugins/content/allmediaplay/allmediaplay/js/';
        $this->_rloc = JURI::root(true) . '/' . $this->_rlocr;
        // Workaround for "double-slash" prob
        $this->_mloc = preg_replace('#([^:])//#', '\\1/', $this->_mloc);
        $this->_rloc = str_replace("//", "/", $this->_rloc);
        $this->_rlocr = str_replace("//", "/", $this->_rlocr);
        $this->_rdir = str_replace(DS . DS, DS, $this->_rdir);

        $this->_vtag = 'v' . $this->_version . '.' . preg_replace('#\D#', '', $this->_rev);

        $tags = null;
        // Check for our corresponding component which owns the db tables.
        if (JComponentHelper::isEnabled('com_allmediaplay', true))
        {
            $db = &JFactory::getDBO();
            $query = 'SELECT name,player_id,ripper_id,postreplace FROM #__allmediaplay_tags';
            $db->setQuery($query);
            $db->query();
            $tags = $db->loadObjectList();
            $this->_dbok = is_array($tags);
        }
        if (!is_array($tags))
        {
            JError::raiseError(500, JText::_('ERR_TAGS'));
        }
        $this->tags = $tags;
    }

    function RenderAllVideos(&$row, &$params, $page = 0)
    {
        // API
        jimport('joomla.filesystem.file');
        $mainframe = JFactory::getApplication();
        $document = JFactory::getDocument();

        // Assign paths
        $sitePath = JPATH_SITE;
        $siteUrl = JURI::root(true);
        if (version_compare(JVERSION, '1.6.0', 'ge'))
        {
            $pluginLivePath = $siteUrl . '/plugins/content/' . $this->plg_name . '/' . $this->plg_name;
        }
        else
        {
            $pluginLivePath = $siteUrl . '/plugins/content/' . $this->plg_name;
        }

        // Check if plugin is enabled
        if (JPluginHelper::isEnabled('content', $this->plg_name) == false)
            return;

        // Includes
        $helper = dirname(__FILE__) . DS . $this->plg_name . DS . 'includes' . DS . 'helper.php';
        require_once($helper);
        //require(dirname(__FILE__).DS.$this->plg_name.DS.'includes'.DS.'sources.php');
        // Simple performance check to determine whether plugin should process further
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('name,player_id');
        // From the hello table
        $query->from('#__allmediaplay_taglist');
        $db->setQuery((string) $query);
        $messages = $db->loadObjectList();
        $options = array();
        if ($messages)
        {
            foreach ($messages as $message)
            {
                $options[] = array($message->name, $message->player_id);
            }
        }
        $tmp = array();
        foreach ($options as $name)
        {
            $tmp[] = $name[0];
        }
        $grabTags = implode(array_values($tmp), "|");
        if (preg_match("#{(" . $grabTags . ")}#s", $row->text) == false)
            return;

        // ----------------------------------- Get plugin parameters -----------------------------------
        // Get plugin info
        $plugin = JPluginHelper::getPlugin('content', $this->plg_name);

        // Control external parameters and set variable for controlling plugin layout within modules
        if (!$params)
            $params = class_exists('JParameter') ? new JParameter(null) : new JRegistry(null);
        $parsedInModule = $params->get('parsedInModule');

        $pluginParams = class_exists('JParameter') ? new JParameter($plugin->params) : new JRegistry($plugin->params);

        /* Preset Parameters */
        $skin = 'six';
        /* Video Parameters */
        $playerTemplate = ($params->get('playerTemplate')) ? $params->get('playerTemplate') : $pluginParams->get('playerTemplate', 'Classic');
        $vfolder = ($params->get('vfolder')) ? $params->get('vfolder') : $pluginParams->get('vfolder', 'images/stories/videos');
        $vwidth = ($params->get('vwidth')) ? $params->get('vwidth') : $pluginParams->get('vwidth', 400);
        $vheight = ($params->get('vheight')) ? $params->get('vheight') : $pluginParams->get('vheight', 300);
        $transparency = $pluginParams->get('transparency', 'transparent');
        $background = $pluginParams->get('background', '#010101');
        $backgroundQT = $pluginParams->get('backgroundQT', 'black');
        $controlBarLocation = $pluginParams->get('controlBarLocation', 'bottom');
        /* Audio Parameters */
        $afolder = $pluginParams->get('afolder', 'images/stories/audio');
        $awidth = ($params->get('awidth')) ? $params->get('awidth') : $pluginParams->get('awidth', 480);
        $aheight = ($params->get('aheight')) ? $params->get('aheight') : $pluginParams->get('aheight', 24);
        $abackground = $pluginParams->get('abackground', '#010101');
        $afrontcolor = $pluginParams->get('afrontcolor', '#FFFFFF');
        $alightcolor = $pluginParams->get('alightcolor', '#00ADE3');
        $allowAudioDownloading = $pluginParams->get('allowAudioDownloading', 0);
        /* Global Parameters */
        $autoplay = ($params->get('autoplay')) ? $params->get('autoplay') : $pluginParams->get('autoplay', 0);
        /* Performance Parameters */
        $gzipScripts = $pluginParams->get('gzipScripts', 0);

        // Variable cleanups for K2
        if (JRequest::getCmd('format') == 'raw')
        {
            $this->plg_copyrights_start = '';
            $this->plg_copyrights_end = '';
        }

        // Assign the AllVideos helper class
        $AllMediaPlayHelper = new AllMediaPlayHelper;

        // ----------------------------------- Render the output -----------------------------------
        // Append head includes only when the document is in HTML mode
        if (JRequest::getCmd('format') == 'html' || JRequest::getCmd('format') == '')
        {

            // CSS
            $avCSS = $AllMediaPlayHelper->getTemplatePath($this->plg_name, 'css/template.css', $playerTemplate);
            $avCSS = $avCSS->http;
            $document->addStyleSheet($avCSS);

            // JS
            if (version_compare(JVERSION, '1.6.0', 'ge'))
            {
                JHtml::_('behavior.framework');
            }
            else
            {
                JHTML::_('behavior.mootools');
            }

            if ($gzipScripts)
            {
                $document->addScript($pluginLivePath . '/includes/js/allmediaplay.js.php');
            }
            else
            {
                $document->addScript($pluginLivePath . '/includes/js/behaviour.js');
                $document->addScript($pluginLivePath . '/includes/js/mediaplayer/jwplayer.js');
                $document->addScript($pluginLivePath . '/includes/js/wmvplayer/silverlight.js');
                $document->addScript($pluginLivePath . '/includes/js/wmvplayer/wmvplayer.js');
                $document->addScript($pluginLivePath . '/includes/js/quicktimeplayer/AC_QuickTime.js');
            }
        }

        // Loop throught the found tags
        foreach ($options as $plg_tag)
        {

            // expression to search for
            $regex = "#{" . $plg_tag[0] . "}.*?{/" . $plg_tag[0] . "}#s";

            // process tags
            if (preg_match_all($regex, $row->text, $matches, PREG_PATTERN_ORDER))
            {

                // start the replace loop
                foreach ($matches[0] as $key => $match)
                {

                    $tagcontent = preg_replace("/{.+?}/", "", $match);
                    $tagparams = explode('|', $tagcontent);
                    $tagsource = trim(strip_tags($tagparams[0]));

                    // Prepare the HTML
                    $output = new JObject;

                    // Width/height/source folder split per media type
                    if (in_array($plg_tag[0], array(
                                'mp3',
                                'mp3remote',
                                'aac',
                                'aacremote',
                                'm4a',
                                'm4aremote',
                                'ogg',
                                'oggremote',
                                'wma',
                                'wmaremote',
                                'soundcloud'
                            )))
                    {
                        $final_awidth = (@$tagparams[1]) ? $tagparams[1] : $awidth;
                        $final_aheight = (@$tagparams[2]) ? $tagparams[2] : $aheight;

                        $output->playerWidth = $final_awidth;
                        $output->playerHeight = $final_aheight;
                        $output->folder = $afolder;

                        if ($plg_tag[0] == 'soundcloud')
                        {
                            if (strpos($tagsource, '/sets/') !== false)
                            {
                                $output->mediaTypeClass = ' avSoundCloudSet';
                            }
                            else
                            {
                                $output->mediaTypeClass = ' avSoundCloudSong';
                            }
                            $output->mediaType = '';
                        }
                        else
                        {
                            $output->mediaTypeClass = ' avAudio';
                            $output->mediaType = 'audio';
                        }

                        if (in_array($plg_tag[0], array('mp3', 'aac', 'm4a', 'ogg', 'wma')))
                        {
                            $output->source = "$siteUrl/$afolder/$tagsource.$plg_tag[0]";
                        }
                        elseif (in_array($plg_tag[0], array('mp3remote', 'aacremote', 'm4aremote', 'oggremote', 'wmaremote')))
                        {
                            $output->source = $tagsource;
                        }
                        else
                        {
                            $output->source = '';
                        }
                    }
                    else
                    {
                        $final_vwidth = (@$tagparams[1]) ? $tagparams[1] : $vwidth;
                        $final_vheight = (@$tagparams[2]) ? $tagparams[2] : $vheight;

                        $output->playerWidth = $final_vwidth;
                        $output->playerHeight = $final_vheight;
                        $output->folder = $vfolder;
                        $output->mediaType = 'video';
                        $output->mediaTypeClass = ' avVideo';
                    }

                    // Autoplay
                    $final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
                    $final_autoplay = ($final_autoplay) ? 'true' : 'false';

                    // Special treatment for specific video providers
                    if ($plg_tag[0] == "dailymotion")
                    {
                        $tagsource = preg_replace("~(http|https):(.+?)dailymotion.com\/video\/~s", "", $tagsource);
                        $tagsourceDailymotion = explode('_', $tagsource);
                        $tagsource = $tagsourceDailymotion[0];
                        if ($final_autoplay == 'true')
                        {
                            if (strpos($tagsource, '?') !== false)
                            {
                                $tagsource = $tagsource . '&amp;autoPlay=1';
                            }
                            else
                            {
                                $tagsource = $tagsource . '?autoPlay=1';
                            }
                        }
                    }

                    if ($plg_tag[0] == "ku6")
                    {
                        $tagsource = str_replace('.html', '', $tagsource);
                    }

                    if ($plg_tag[0] == "metacafe" && substr($tagsource, -1, 1) == '/')
                    {
                        $tagsource = substr($tagsource, 0, -1);
                    }

                    if ($plg_tag[0] == "tnaondemand")
                    {
                        $tagsource = parse_url($tagsource);
                        $tagsource = explode('&', $tagsource['query']);
                        $tagsource = str_replace('vidid=', '', $tagsource[0]);
                    }

                    if ($plg_tag[0] == "twitvid")
                    {
                        $tagsource = preg_replace("~(http|https):(.+?)twitvid.com\/~s", "", $tagsource);
                        if ($final_autoplay == 'true')
                        {
                            $tagsource = $tagsource . '&amp;autoplay=1';
                        }
                    }

                    if ($plg_tag[0] == "vidiac")
                    {
                        $tagsourceVidiac = explode(';', $tagsource);
                        $tagsource = $tagsourceVidiac[0];
                    }

                    if ($plg_tag[0] == "vimeo")
                    {
                        $tagsource = preg_replace("~(http|https):(.+?)vimeo.com\/~s", "", $tagsource);
                        if (strpos($tagsource, '?') !== false)
                        {
                            $tagsource = $tagsource . '&amp;portrait=0';
                        }
                        else
                        {
                            $tagsource = $tagsource . '?portrait=0';
                        }
                        if ($final_autoplay == 'true')
                        {
                            $tagsource = $tagsource . '&amp;autoplay=1';
                        }
                    }

                    if ($plg_tag[0] == "yahoo")
                    {
                        $tagsourceYahoo = explode('-', str_replace('.html', '', $tagsource));
                        $tagsourceYahoo = array_reverse($tagsourceYahoo);
                        $tagsource = $tagsourceYahoo[0];
                    }

                    if ($plg_tag[0] == "yfrog")
                    {
                        $tagsource = preg_replace("~(http|https):(.+?)yfrog.com\/~s", "", $tagsource);
                    }

                    if ($plg_tag[0] == "youmaker")
                    {
                        $tagsourceYoumaker = explode('-', str_replace('.html', '', $tagsource));
                        $tagsource = $tagsourceYoumaker[1];
                    }

                    if ($plg_tag[0] == "youku")
                    {
                        $tagsource = str_replace('.html', '', $tagsource);
                        $tagsource = substr($tagsource, 3);
                    }

                    if ($plg_tag[0] == "youtube")
                    {
                        $tagsource = preg_replace("~(http|https):(.+?)youtube.com\/watch\?v=~s", "", $tagsource);
                        $tagsourceYoutube = explode('&', $tagsource);
                        $tagsource = $tagsourceYoutube[0];

                        if (strpos($tagsource, '?') !== false)
                        {
                            $tagsource = $tagsource . '&amp;rel=0&amp;fs=1&amp;wmode=transparent';
                        }
                        else
                        {
                            $tagsource = $tagsource . '?rel=0&amp;fs=1&amp;wmode=transparent';
                        }
                        if ($final_autoplay == 'true')
                        {
                            $tagsource = $tagsource . '&amp;autoplay=1';
                        }
                    }

                    // Poster frame
                    $posterFramePath = $sitePath . DS . str_replace('/', DS, $vfolder);
                    if (JFile::exists($posterFramePath . DS . $tagsource . '.jpg'))
                    {
                        $output->posterFrame = $siteUrl . '/' . $vfolder . '/' . $tagsource . '.jpg';
                    }
                    elseif (JFile::exists($posterFramePath . DS . $tagsource . '.png'))
                    {
                        $output->posterFrame = $siteUrl . '/' . $vfolder . '/' . $tagsource . '.png';
                    }
                    elseif (JFile::exists($posterFramePath . DS . $tagsource . '.gif'))
                    {
                        $output->posterFrame = $siteUrl . '/' . $vfolder . '/' . $tagsource . '.gif';
                    }
                    else
                    {
                        $output->posterFrame = '';
                    }

                    // Set a unique ID
                    $output->playerID = 'allmediaplayID_' . substr(md5($tagsource), 1, 8) . '_' . rand();

                    // Placeholder elements
                    $findAVparams = array(
                        "{SOURCE}",
                        "{SOURCEID}",
                        "{FOLDER}",
                        "{WIDTH}",
                        "{HEIGHT}",
                        "{PLAYER_AUTOPLAY}",
                        "{PLAYER_TRANSPARENCY}",
                        "{PLAYER_BACKGROUND}",
                        "{PLAYER_BACKGROUNDQT}",
                        "{PLAYER_CONTROLBAR}",
                        "{SITEURL}",
                        "{SITEURL_ABS}",
                        "{FILE_EXT}",
                        "{PLUGIN_PATH}",
                        "{PLAYER_POSTER_FRAME}",
                        "{PLAYER_SKIN}",
                        "{PLAYER_ABACKGROUND}",
                        "{PLAYER_AFRONTCOLOR}",
                        "{PLAYER_ALIGHTCOLOR}"
                    );

                    // Replacement elements
                    $replaceAVparams = array(
                        $tagsource,
                        $output->playerID,
                        $output->folder,
                        $output->playerWidth,
                        $output->playerHeight,
                        $final_autoplay,
                        $transparency,
                        $background,
                        $backgroundQT,
                        $controlBarLocation,
                        $siteUrl,
                        substr(JURI::root(false), 0, -1),
                        $plg_tag[0],
                        $pluginLivePath,
                        $output->posterFrame,
                        $skin,
                        $abackground,
                        $afrontcolor,
                        $alightcolor
                    );

                    $db = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    // Select some fields
                    $query->select('code');
                    // From the hello table
                    $query->from('#__allmediaplay_playerlist');
                    $tmp = "id = " . $plg_tag[1];
                    $query->where($tmp);
                    $db->setQuery((string) $query);
                    $messages = $db->loadObjectList();
                    $options = array();
                    if ($messages)
                    {
                        foreach ($messages as $message)
                        {
                            $options[] = $message->code;
                        }
                    }



                    // Do the element replace
                    $output->player = JFilterOutput::ampReplace(str_replace($findAVparams, $replaceAVparams, $options[0]));

                    // Fetch the template
                    ob_start();
                    $getTemplatePath = $AllMediaPlayHelper->getTemplatePath($this->plg_name, 'default.php', $playerTemplate);
                    $getTemplatePath = $getTemplatePath->file;
                    include($getTemplatePath);
                    $dum = ob_get_contents();
                    $getTemplate = $this->plg_copyrights_start . ob_get_contents() . $this->plg_copyrights_end;
                    ob_end_clean();

                    // Output
                    $row->text = preg_replace("#{" . $plg_tag[0] . "}" . preg_quote($tagcontent) . "{/" . $plg_tag[0] . "}#s", $getTemplate, $row->text);
                } // End second foreach
            } // End if
        }
    }

}
