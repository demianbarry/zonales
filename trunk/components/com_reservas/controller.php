<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');

/**
 * Controlador
 *
 * @package Zonales
 * @since 1.5
 */
class ReservasController extends JController
{
	/** @var class */
	var $_reservasHelper = null;

	function __construct($default = array())
	{
		parent::__construct($default);
		$this->_reservasHelper = new comZonalesHelper();
	}

	function reserva()
	{
		global $option;
		$document = &JFactory::getDocument();
		$vType = $document->getType();
		$vName = JRequest::getCmd('view','reserva');
		$vLayout = JRequest::getCmd('layout','default');

		$view =& $this->getView($vName, $vType);
		$view->setLayout($vLayout);
		$view->display();
	}
	
}