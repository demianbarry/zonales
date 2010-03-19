<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'controller.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helper.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'basemodel.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'baseview.php');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

$controller = new AapuController();  // array('default_task' => 'listTabs') 
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();



?>
