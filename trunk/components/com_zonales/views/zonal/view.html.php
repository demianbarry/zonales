<?php

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.user.helper');
jimport( 'joomla.application.component.view');

/**
 * User component login view class
 *
 * @package		Joomla
 * @subpackage	Users
 * @since	1.0
 */
class ZonalesViewZonal extends JView
{
	function display($tpl = null)
	{
		$option = JRequest::getCMD('option'); 

                $mainframe=JFactory::getApplication();
		$app =& JFactory::getApplication();
		$helper = new comZonalesHelper();

		$this->assignRef('template', $app->getTemplate());
		$this->assignRef('zonales', $helper->getZonales());
		$this->assignRef('zonal_id', $helper->getZonalActual());

		parent::display($tpl);
	}
}
