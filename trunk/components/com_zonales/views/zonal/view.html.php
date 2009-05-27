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
class UserZonalesViewZonal extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		$app =& JFactory::getApplication();

		$this->assignRef('template', $app->getTemplate());

		parent::display($tpl);
	}
}
