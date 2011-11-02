<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//JPATH contiene la ruta donde el componente tiene los archivos base de ejecucion y DS representa el separador de ruta
//traducido a lo que es conveniente para su sistema operativo, siendo que Windows o Linux.
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'controller.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'basemodel.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'baseview.php');

//Agrega una ruta de acceso del sistema de archivos
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

$controller = new AapuController(array('default_task' => 'listUsers'));  // array('default_task' => 'listTabs')
$controller->execute( JRequest::getVar( 'task' ) );                       // Ejecuta la solicitud de la tarea definida en GET
$controller->redirect();



?>
