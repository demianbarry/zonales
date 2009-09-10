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

// parametros - alto y ancho
		$zonalesParams = &JComponentHelper::getParams( 'com_zonales' );
		$this->width = $zonalesParams->get('width_javafx', '');
		$this->height = $zonalesParams->get('height_javafx', '');
		$this->javafxfile = $zonalesParams->get('javafxfile', '');
                $this->draggable = $zonalesParams->get('draggable', '');
                $this->code = $zonalesParams->get('code', '');
                $this->name = $zonalesParams->get('name', '');
                $this->id = $zonalesParams->get('id', '');
                

		parent::display($tpl);
	}
}
