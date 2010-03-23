<?php
/**
* @version	$Id$
* @package	Zonales
* @subpackage	Eq
* @copyright	Copyright (C) 2005 - 2008. Mediabit. All rights reserved.
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

// Controlador base
require_once(JPATH_COMPONENT.DS.'helper.php' );
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');

// Controlador base administrador
//require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php' );
//require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helper.php' );

require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_zonales'.DS.'models'.DS.'basemodel.php');
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_zonales'.DS.'views'.DS.'baseview.php');

JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');

$cmd = JRequest::getCmd('task', null);

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
$controller->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );
$controller->execute($task);
$controller->redirect();