<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'controller.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'basemodel.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'users.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'attributes.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'datatypes.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'tabs.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'attribute_entity.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'baseview.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'edituser'.DS.'view.html.php');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

$controller = new AapuController(array('default_task' => 'editUser'));  // array('default_task' => 'listTabs')
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();

?>
