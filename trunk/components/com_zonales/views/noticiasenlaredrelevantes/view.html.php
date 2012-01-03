<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.helper');
jimport('joomla.application.component.view');
JHTML::_("behavior.mootools");

/**
 * User component login view class
 *
 * @package		Joomla
 * @subpackage	Users
 * @since	1.0
 */
class ZonalesViewNoticiasenlaredrelevantes extends JView {

    function display($tpl = null) {
        $option = JRequest::getCMD('option'); 

        $mainframe=JFactory::getApplication();
        $document = &JFactory::getDocument();
        //$document->addScript( '/media/system/js/viewutils.js');
        //$document->addScript( '/media/system/js/relevantes.js');
       // $document->addScript('/templates/z20/js/vistas.js');
        //$document->addStyleSheet('/media/system/css/global.css');
        //$document->addStyleSheet('/media/system/css/content.css');
        $document->addStyleSheet('/media/system/css/ZoneStyle.css');

        $app = & JFactory::getApplication();
        $helper = new comZonalesHelper();
        $zCtx = unserialize($session->get('zCtx'));
        $helper = new comZonalesHelper();

        $this->assignref('zCtx', $zCtx);
        $this->assignRef('template', $app->getTemplate());
        $this->assignRef('zonal_id', ucwords(str_replace("_", "+", $helper->getZonalActual())));

        parent::display($tpl);
    }

}
