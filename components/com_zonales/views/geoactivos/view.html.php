<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.helper');
jimport('joomla.application.component.view');
//JHTML::_("behavior.mootools");

/**
 * User component login view class
 *
 * @package		Joomla
 * @subpackage	Users
 * @since	1.0
 */
class ZonalesViewGeoactivos extends JView {

    function display($tpl = null) {
        $option = JRequest::getCMD('option'); 

        $mainframe=JFactory::getApplication();
        $document = &JFactory::getDocument();
        //$document->addScript( '/media/system/js/viewutils.js');
        //$document->addScript('/media/system/js/mootools1.js');
        //$document->addScript('/media/system/js/OpenLayers.js');
        //$document->addScript('http://maps.google.com/maps/api/js?sensor=false');
        //$document->addStyleSheet('/media/system/css/global.css');
        //$document->addStyleSheet('/media/system/css/content.css');
        //$document->addStyleSheet('/media/system/css/ZoneStyle.css');

        $app = & JFactory::getApplication();
        //$helper = new comZonalesHelper();

        $this->assignRef('template', $app->getTemplate());
        //$this->assignRef('zonal_id', ucwords(str_replace("_", "+", $helper->getZonalActual())));

        parent::display($tpl);
    }

}
