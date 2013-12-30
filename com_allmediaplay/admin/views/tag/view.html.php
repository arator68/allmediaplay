<?php

/**
 * @version		$Id: view.html.php 1027 2008-07-06 22:46:07Z Fritz Elfert $
 * @copyright	Copyright (C) 2008 Fritz Elfert. All rights reserved.
 * @license		GNU/GPLv2
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Player View
 *
 */
class AllMediaPlayViewTag extends JView
{

    /**
     * display method of Player view
     * @return void
     * */
    public function display($tpl = null)
    {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');
        $script = $this->get('Script');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign the Data
        $this->form = $form;
        $this->item = $item;
        $this->script = $script;

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        JRequest::setVar('hidemainmenu', true);
        $user = JFactory::getUser();
        $userId = $user->id;
        $isNew = $this->item->id == 0;
        $canDo = AllMediaPlayHelper::getActions($this->item->id);
        JToolBarHelper::title($isNew ? JText::_('COM_ALLMEDIAPLAY_MANAGER_ALLMEDIAPLAY_EDIT') : JText::_('COM_ALLMEDIAPLAY_MANAGER_ALLMEDIAPLAY_EDIT'), 'allmediaplay');
        // Built the actions for new and existing records.
        if ($isNew)
        {
            // For new records, check the create permission.
            if ($canDo->get('core.create'))
            {
                JToolBarHelper::apply('tag.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('tag.save', 'JTOOLBAR_SAVE');
                JToolBarHelper::custom('tag.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
            JToolBarHelper::cancel('tag.cancel', 'JTOOLBAR_CANCEL');
        }
        else
        {
            if ($canDo->get('core.edit'))
            {
                // We can save the new record
                JToolBarHelper::apply('tag.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('tag.save', 'JTOOLBAR_SAVE');

                // We can save this record, but check the create permission to see if we can return to make a new one.
                if ($canDo->get('core.create'))
                {
                    JToolBarHelper::custom('tag.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
                }
            }
            if ($canDo->get('core.create'))
            {
                JToolBarHelper::custom('tag.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
            JToolBarHelper::cancel('tag.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $isNew = $this->item->id == 0;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_ALLMEDIAPLAY_FORM_PLAYER_NEW') : JText::_('COM_ALLMEDIAPLAY_FORM_PLAYER_EDIT'));
        $document->addScript(JURI::root() . $this->script);
        $document->addScript(JURI::root() . "/administrator/components/com_allmediaplay/views/tag/submitbutton.js");
        JText::script('COM_HELLOWORLD_HELLOWORLD_ERROR_UNACCEPTABLE');
    }

}
