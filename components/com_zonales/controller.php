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

	/**
	 * Almacena la noticia enviada por el usuario a traves de una instancia
	 * del módulo mod_soycorresponsal.
	 */
	function saveCorresponsalContent()
	{
		global $mainframe;

		// chequea irregularidades en el request
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// titulo del modulo que envio el request
		$moduleTitle = JRequest::getVar('module', NULL, 'post', 'string' );

		// chequea que el modulo especificado en el request sea valido
		if (!$moduleTitle) {
			jexit( 'No module name' );
		} else {
			jimport('joomla.application.module.helper');
			$module = JModuleHelper::getModule('soycorresponsal', $moduleTitle);
			if ($module->id == 0) {
				jexit( 'Invalid module' );
			}
		}

		// recupera parametros del módulo
		$modparams = new JParameter($module->params);

		// inicializa variables a utilizar
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser($modparams->get('user'));
		$catid = $modparams->get('category', 0);

		$nullDate = $db->getNullDate();

		// tabla de contenidos joomla
		$row = & JTable::getInstance('content');

		if ($catid == 0) {
			jexit( 'No section id' );
		} else {
			$category =& JTable::getInstance('category');
			$category->load($catid);
			$sectionid = $category->section;
		}

		$createdate =& JFactory::getDate();

		$row->title = JRequest::getVar('title', NULL, 'post', 'string');
		$row->sectionid = $sectionid;
		$row->catid = $catid;
		$row->version = 0;
		$row->state = 0;
		$row->ordering = 0;
		$row->images = array ();
		$row->publish_down = $nullDate;
		$row->created_by = $user->get('id');
		$row->modified = $nullDate;

		// corrección de la fecha
		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');
		$date =& JFactory::getDate('now', $tzoffset);
		$row->created = $date->toMySQL();
		$row->publish_up = $date->toMysQL();

		// se redondea timestamp de creación
		if ($row->created && strlen(trim( $row->created )) <= 10) {
			$row->created 	.= ' 00:00:00';
		}

		// Prepare the content for saving to the database
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_content'.DS.'helper.php');
		ContentHelper::saveContentPrep( $row );

		// Se agregan nombre de usuario, correo y telefono
		$enviaNombre = JRequest::getVar('nombre', NULL, 'post', 'string');
		$enviaEmail = JRequest::getVar('email', NULL, 'post', 'string');
		$enviaTel = JRequest::getVar('telefono', NULL, 'post', 'string');
		$row->introtext .= "<p>Envio esta noticia:</p><p>Nombre: $enviaNombre<br/>Email: $enviaEmail<br/>Telefono: $enviaTel</p>";

		// Make sure the data is valid
		if (!$row->check()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}

		// Store the content to the database
		if (!$row->store()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}

		// Check the article and update item order
		$row->checkin();
		$row->reorder('catid = '.(int) $row->catid.' AND state >= 0');

		// Asignamos los tags de Custom Properties según los valores de zonal y localidad
		$fieldId = JRequest::getVar('partidos', NULL, 'post', 'int');
		$valueId = JRequest::getVar('localidad', NULL, 'post', 'int');
		
		$query = "
			REPLACE INTO #__custom_properties (ref_table, content_id,field_id,value_id)
			SELECT 'content','$row->id',f.id AS field, v.id AS value
			FROM #__custom_properties_fields f
			  INNER JOIN  #__custom_properties_values v
			  ON(f.id = v.field_id)
			WHERE f.id = $fieldId
			AND v.id = $valueId ";
		$database = JFactory::getDBO();
		$database->setQuery($query);
		$database->query();

		$mainframe->redirect('index.php');
	}
}