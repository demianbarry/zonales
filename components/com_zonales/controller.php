<?php
/**
 * @version		$Id: controller.php 11215 2008-10-26 02:25:51Z ian $
 * @package		Zonales
 * @copyright		Mediabit	
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');

/**
 * User Zonales
 *
 * @package		Zonales
 * @since 1.5
 */
class ZonalesController extends JController
{
	/** @var class */
	var $_zonalesHelper = null;

	function __construct($default = array())
	{
		parent::__construct($default);
		$this->_zonalesHelper = new comZonalesHelper();
	}

	function zonal()
	{
		global $option;
		$document = &JFactory::getDocument();
		$vType = $document->getType();
		$vName = JRequest::getCmd('view','zonal');
		$vLayout = JRequest::getCmd('layout','default');

		$view =& $this->getView($vName, $vType);
		$view->setLayout($vLayout);
		$view->display();
	}

	function mapa()
	{
		global $option;

		$document =& JFactory::getDocument();

		$vType = $document->getType();
		$vName = JRequest::getCmd('view','mapa');
		$vLayout = JRequest::getCmd('layout','default');

		$view =& $this->getView($vName, $vType);
		$view->setLayout($vLayout);
		$view->display();
	}

	/**
	 * Setea o actualiza la variable de sesión con el zonal actualmente
	 * seleccionado por el usuario, y redirecciona a la URL de retorno
	 * especificada.
	 */
	function setZonal()
	{
		global $option;

		// parametros
		$zname	= JRequest::getVar('zname', NULL, 'post', 'string');
		$return	= JRequest::getVar('return', 'index.php', 'post', 'string');

		$zonal = $this->helper->getZonalByName($zname);

		// debido a que flashvar utiliza & para separar las
		// variables, el url de retorno se encuentra dividido
		$view = JRequest::getVar('view', NULL, 'post', 'string');
		$item = JRequest::getVar('Itemid', NULL, 'post', 'int');
		if ($view && $item)
			$return .= '&view=' . $view . '&Itemid=' . $item;

		if (!$zonal) $zonal = NULL;
		$session = JFactory::getSession();
		$session->set('zonales_zonal_name', $zonal);

		$this->setRedirect($return);
	}
}