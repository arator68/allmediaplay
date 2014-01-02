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

// import Joomla view library
jimport('joomla.application.component.view');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'allmediaplay_generic.php');

/**
 * HelloWorlds View
 */
class AllMediaPlayViewPlaylists extends JView
{

    /**
     * HelloWorlds view display method
     * @return void
     */
    function display($tpl = null)
    {
        $vdir = AllMediaPlayGenericHelper::getVdir();
        $app = JFactory::getApplication();
        $folder = $app->getUserStateFromRequest('com_allmediaplay.playlists_folder', 'folder', $vdir);
        //$folder=$vdir;

        // Get data from the model
        $fselect = $this->_getFolderSelect($vdir, $folder);
        $lists = & $this->_getViewLists($app, $folder);
        $model = & $this->getModel();
        $items = & $model->getData();
        
//        if (count($items)) {
//            JToolBarHelper::deleteList();
//            JToolBarHelper::editListX();
//        }
//        JToolBarHelper::addNewX();
//        JToolBarHelper::help('playlists', true);
//        JToolBarHelper::title(JText::_('COM_ALLMEDIAPLAY_MANAGER_PLAYLIST'), 'allmediaplay');
        $files = '';
        foreach ($items as $i => $item) {
            if ($i > 0) {
                $files .= ',';
            }
            $files .= "'".basename($item->filename)."'";
        }
        $this->assignRef('items', $items);
        $this->assignRef('lists', $lists);
        $this->assignRef('fselect', $fselect);
        $this->assignRef('files', $files);

        //$items = $this->get('Items');
        //$pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign data to the view
        //$this->items = $items;
        //$this->pagination = $pagination;

        // Set the toolbar
        $this->addToolBar(count($items));

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar($itemcount = 0)
    {
        $canDo = AllMediaPlayHelper::getActions();
        JToolBarHelper::title(JText::_('COM_ALLMEDIAPLAY_MANAGER_PLAYLIST'), 'allmediaplay');
        if ($canDo->get('core.create'))
        {
            JToolBarHelper::addNew('playlist.add', 'JTOOLBAR_NEW');
        }
        if ($canDo->get('core.edit'))
        {
            JToolBarHelper::editList('playlist.edit', 'JTOOLBAR_EDIT');
        }
        if ($canDo->get('core.delete'))
        {
            JToolBarHelper::deleteList('', 'playlists.delete', 'JTOOLBAR_DELETE');
        }
        if ($canDo->get('core.admin'))
        {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_allmediaplay');
        }
        JToolBarHelper::help('playlists', true);
        JToolBarHelper::addNewX();
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_ALLMEDIAPLAY_ADMINISTRATION'));
    }

    function _getFolderSelect($top, $current)
    {
        jimport('joomla.filesystem.folder');
        $subdirs = & JFolder::folders($top, '.', true, true);
        $top_displayname = basename($top);
        $list = array(JHTML::_('select.option', htmlspecialchars($top), htmlspecialchars($top_displayname)));
        foreach ($subdirs as $dir)
        {
            $displayname = str_replace($top, $top_displayname, $dir);
            $list[] = JHTML::_('select.option', htmlspecialchars($dir), htmlspecialchars($displayname));
        }
        return JHTML::_('select.genericlist', $list, 'folder', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $current);
    }

    function &_getViewLists(&$app, $folder)
    {
        $filter_order = $app->getUserStateFromRequest("com_allmediaplay.filter_playlists_order", 'filter_order', 'filename', 'cmd');
        $filter_order_Dir = $app->getUserStateFromRequest("com_allmediaplay.filter_playlists_order_Dir", 'filter_order_Dir', 'asc', 'word');
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest('com_allmediaplay.limitstart_playlists', 'limitstart', 0, 'int');
        $m = $this->getModel();
        // Tell the model how to sort
        $m->setState('folder', $folder);
        $m->setState('filter_order', $filter_order);
        $m->setState('filter_order_Dir', $filter_order_Dir);
        $total = $m->getTotal();
        jimport('joomla.html.pagination');
        $page = new JPagination($total, $limitstart, $limit);
        // Tell the model the limits
        $m->setState('limit', $limit);
        $m->setState('limitstart', $limitstart);

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        $lists['page'] = $page;
        $lists['limit'] = $limit;
        $lists['limitstart'] = $limitstart;

        return $lists;
    }

}
