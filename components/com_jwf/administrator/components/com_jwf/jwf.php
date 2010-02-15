<?php
/**
* Entry point for JWF Component
*
* @version		$Id: jwf.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.core
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.application.component.controller');

JHTML::addIncludePath(  JPATH_COMPONENT_ADMINISTRATOR.DS.'helper');
JTable::addIncludePath( JPATH_COMPONENT_ADMINISTRATOR.DS.'tables' );
require_once 'globals.php';

//Add Tables path

// Require the base controller
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'libs'.DS.'plugins.php';

/**
 * @global array $_GLOBALS["JWFGlobals"] Component wide global variable
 * @global array $_GLOBALS["JWFGlobals"]["PluginManager"] Plugin manager , 
 * @see JWFPluginManager
 * @see getPluginManager()
*/
$GLOBALS['JWFGlobals'] = array();
$GLOBALS['JWFGlobals']['PluginManager'] = new JWFPluginManager();

$controller = JRequest::getWord('controller');
if($controller == ''){
	if(canManageWorkflows())$controller='workflow';
	else $controller='item';
}

if( $controller != 'workflow' ){
	$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		JError::raiseError( 500, JText::_("Couldn't find controller") );
		die;
	}
}


if(canManageWorkflows()){
	JSubMenuHelper::addEntry(JText::_('Workflow Manager'), 'index.php?option=com_jwf&controller=workflow',$controller=='workflow');
	JSubMenuHelper::addEntry(JText::_('Tasks Manager')   , 'index.php?option=com_jwf&controller=item'    ,$controller=='item');
}

// Create the controller "Backend"

$classname	= 'JWFController'.ucfirst($controller);
$controller = new $classname();

// Perform the Request task
$controller->execute(JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();