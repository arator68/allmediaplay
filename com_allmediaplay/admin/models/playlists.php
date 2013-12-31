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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'helper_playlists.php');

/**
 * HelloWorlds Model
 */
class AllMediaPlayModelPlaylists extends JModel
{

    function getTotal()
    {
        $folder = $this->getState('folder');
        $h = & $this->getHelper();
        return count($h->getPlaylists($folder));
    }
    
    function &getHelper() {
        if ($this->_helper == null) {
            $this->_helper = new AllMediaPlayPlaylistsHelper();
        }
        return $this->_helper;
    }
    
    function _cmpname(&$o1, &$o2) {
        $a = $o1->filename;
        $b = $o2->filename;
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    function _cmptitle(&$o1, &$o2) {
        $a = $o1->title;
        $b = $o2->title;
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    /**
     * Retrieves the players
     * @return array Array of objects containing the data from the database
     */
    function &getData() {
        $folder = $this->getState('folder');
        $filter_order = $this->getState('filter_order');
        $filter_order_Dir = $this->getState('filter_order_Dir');
        $h =& $this->getHelper();
        $data =& $h->getPlaylists($folder);
        usort($data, array('AllMediaPlayModelPlaylists',
            ($filter_order == 'filename') ? '_cmpname' : '_cmptitle'));
        if (strtolower($filter_order_Dir) != 'asc') {
            $data = array_reverse($data);
        }
        $limit = $this->getState('limit');
        $limit = ($limit == 'all') ? 9999 : $limit;
        $limitstart = $this->getState('limitstart');
        $data = array_slice($data, $limitstart, $limit);
        return $data;
    }

    function delete() {
        $cids = JRequest::getVar('cid', array(),'post','array');
        foreach ($cids as $cid) {
            if (!unlink($cid))
                return false;
        }
        return true;
    }

}
