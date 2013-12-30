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

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

header("Content-type: text/javascript; charset: UTF-8");
header("Cache-Control: must-revalidate");
header("Expires: ".gmdate("D, d M Y H:i:s", time() + 60 * 60)." GMT");

ob_start("ob_gzhandler");

// Includes
echo "/* behaviour.js */\n";
include(dirname( __FILE__ ).DS."behaviour.js");
echo "/* jwplayer.js */\n";
include(dirname( __FILE__ ).DS."mediaplayer".DS."jwplayer.js");
echo "\n\n";
echo "/* silverlight.js */\n";
include(dirname( __FILE__ ).DS."wmvplayer".DS."silverlight.js");
echo "\n\n";
echo "/* wmvplayer.js */\n";
include(dirname( __FILE__ ).DS."wmvplayer".DS."wmvplayer.js");
echo "\n\n";
echo "/* AC_QuickTime.js */\n";
include(dirname( __FILE__ ).DS."quicktimeplayer".DS."AC_QuickTime.js");
echo "\n\n";

ob_end_flush();
