<?php

/**
 * @version		$Id: helloworld.php 74 2010-12-01 22:04:52Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldTag extends JFormFieldList
{

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'Tag';

    /**
     * Method to get a list of options for a list input.
     *
     * @return	array		An array of JHtml options.
     */
    protected function getOptions()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,description');
        $query->from('#__allmediaplay_playerlist');
        //$query->leftJoin('#__categories on catid=#__categories.id');
        $db->setQuery((string) $query);
        $messages = $db->loadObjectList();
        $options = array();
        if ($messages)
        {
            foreach ($messages as $message)
            {
                $options[] = JHtml::_('select.option', $message->id, $message->description . ($message->catid ? ' (' . $message->category . ')' : ''));
            }
        }
        $options = array_merge(parent::getOptions(), $options);
        return $options;
    }

}
