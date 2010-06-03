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

// no direct access
defined('_JEXEC') or die('Restricted access');

// requiere el controlador base
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helper.php' );

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'models'.DS.'basemodel.php');
require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'views'.DS.'baseview.php');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

$cmd = JRequest::getCmd('task', 'listaEq');

if (strpos($cmd, '.') != false) {
    // Definimos un par controlador/tarea (controller/task) -- los dividimos
    list($controllerName, $task) = explode('.', $cmd);

    // Definimos el nombre del controlador y su path
    $controllerName	= strtolower($controllerName);
    $controllerPath	= JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php';

    // Si el controlador existe, lo incluimos
    if (file_exists($controllerPath)) {
        require_once($controllerPath);
    } else {
        JError::raiseError(500, 'Invalid Controller');
    }
}
else {
    // Controlador base
    $controllerName = null;
    $task = $cmd;
}

// Instancia el controlador
$controllerClass = 'EqZonalesController'.ucfirst($controllerName);
if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
} else {
    JError::raiseError(500, 'Invalid Controller Class');
}

// ejecuta la tarea requerida
$controller->execute($task);

//$controller = new EqZonalesController(array('default_task' => 'listEq'));
//$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();
