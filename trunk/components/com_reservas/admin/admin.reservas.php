<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helper.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'basemodel.php');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'baseview.php');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

$controller = new ReservasController( array('default_task') );
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();
