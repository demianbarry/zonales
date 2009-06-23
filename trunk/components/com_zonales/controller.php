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

		// al utilizar flashbar & para separar variables el url puede estar divido
		$view = JRequest::getVar('view', NULL, 'post', 'string');
		$item = JRequest::getVar('Itemid', NULL, 'post', 'int');
		if ($view && $item)
			$return .= '&view=' . $view . '&Itemid=' . $item;

		$session = JFactory::getSession();
		if ($zname) {
			$zonal = $this->_zonalesHelper->getZonal($zname);
			if ($zonal) {
				$session->set('zonales_zonal_name', $zonal->name);
			}
		}
		else {
			$session->set('zonales_zonal_name', NULL);
		}

		$this->setRedirect($return);
	}

	/**
	 * Setea o actualiza la variable de sesión con el zonal actualmente
	 * seleccionado por el usuario, y retorna mensaje de confirmación.
	 * Este mètodo debe ser utilizado en operaciones tipo ajax.
	 */
	function setZonalAjax()
	{
		$zname	= JRequest::getVar('zname', NULL, 'post', 'string');
		$sesid  = JRequest::getVar('PHPSESSID', NULL, 'post', 'string');

		if ($sesid) session_id($sesid);

		$result = "failure";
		$message = "Zonal desconocido";

		if ($zname) {
			$zonal = $this->_zonalesHelper->getZonal($zname);

			if ($zonal) {
				$session = JFactory::getSession();
				$session->set('zonales_zonal_name', ($zonal ? $zonal->name : NULL));

				$result = "success";
				$message = $zonal->label;
			}
		}

		echo "result=$result&message=$message";
		return;
	}
}