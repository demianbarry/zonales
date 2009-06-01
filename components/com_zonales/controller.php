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

	function setZonal()
	{
		global $option;

		$zonal = JRequest::getVar('selectZonal', NULL, 'post', 'int');

		$session = JFactory::getSession();
		$session->set('zonales_zonal_id', $zonal);

		$this->setRedirect('index.php');
	}
}
