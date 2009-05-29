<?php

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

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
		global $mainframe, $option;

		$app =& JFactory::getApplication();
		$helper = new comZonalesHelper();

		$this->assignRef('template', $app->getTemplate());
		$this->assignRef('zonales', $helper->getZonales());
		$this->assignRef('zonal_id', $helper->getZonalActual());

		parent::display($tpl);
	}
}
