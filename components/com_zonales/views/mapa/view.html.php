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
class ZonalesViewMapa extends JView
{

	function display($tpl = null)
	{
		global $mainframe, $option;

		$app =& JFactory::getApplication();
		$helper = new comZonalesHelper();

		// url de retorno según sección del menu actual
		$menu =& JSite::getMenu();
		$item = $menu->getActive();
		$return = $item ? $item->link . '&Itemid='. $item->id : 'index.php';

		// si debe retornarse una respuesta mediante ajax
		$this->ajax = JRequest::getBool('ajax');
		$this->task = JRequest::getBool('ajax') ? 'setZonalAjax' : 'setZonal';
		$this->zonal = $helper->getZonal()->name;

		$this->assignRef('j2f', $helper->getZif2SifMap());
		$this->assignRef('template', $app->getTemplate());
		$this->assignRef('return', $return);

		parent::display($tpl);
	}

}
