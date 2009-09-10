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
class ReservasViewReserva extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		$app =& JFactory::getApplication();
		$helper = new comReservasHelper();

		$this->assignRef('template', $app->getTemplate());
		$this->assignRef('javafx_file', $helper->getJavaFX());
                

		parent::display($tpl);
	}
}
