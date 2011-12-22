<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
JHTML::_("behavior.mootools");

/**
 * User component login view class
 *
 * @package		Joomla
 * @subpackage	Users
 * @since	1.0
 */
class ZonalesViewEditorList extends JView {

    function display($tpl = null) {
        $option = JRequest::getCMD('option');
        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();

        if ($user->get('guest')) {
            // Redirect to login
            $uri = JFactory::getURI();
            $mainframe->redirect('index.php?option=com_users&view=login&return=' . base64_encode($uri), null);
            return;
        } else {
            $groups = JUserHelper::getUserGroups($user->get('id'));
            if (!in_array(4, $groups)) {
                JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
                return;
            }
        }


        $document = &JFactory::getDocument();
        //$document->addScript( '/media/system/js/viewutils.js');
        //$document->addScript('/media/system/js/mootools1.js');
        //$document->addStyleSheet('/media/system/css/global.css');
        //$document->addStyleSheet('/media/system/css/content.css');
        //$document->addStyleSheet('/media/system/css/ZoneStyle.css');

        $helper = new comZonalesHelper();

        $this->assignRef('template', $mainframe->getTemplate());
        $this->assignRef('user', $user);
        $this->assignRef('tomcat_host', 'localhost');
        $this->assignRef('tomcat_port', '38080');
        $this->assignRef('zonal_id', ucwords(str_replace("_", "+", $helper->getZonalActual())));

        parent::display($tpl);
    }

}
