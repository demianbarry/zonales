<?php
/**
* Entry point for JWF Component
*
* @version		$Id: jwf.php 1320 2009-08-03 20:47:26Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

define('JWF_FRONTEND_RUNNING',1);

//Only logged in users are allowed
$user =& JFactory::getUser();
if( $user->guest )
	JError::raiseError( 403, JText::_("Access Forbidden") );

jimport('joomla.application.component.controller');

JHTML::addIncludePath (  JPATH_COMPONENT_ADMINISTRATOR.DS.'helper' );
JTable::addIncludePath(  JPATH_COMPONENT_ADMINISTRATOR.DS.'tables' );

require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'globals.php';

// Require the base controller
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'libs'.DS.'plugins.php';

$GLOBALS['JWFGlobals'] = array();
$GLOBALS['JWFGlobals']['PluginManager'] = new JWFPluginManager();

$controller = JRequest::getWord('controller');
if($controller == '')
	$controller='item';

require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$controller.'.php';

$classname	= 'JWFController'.ucfirst($controller);
$controllerObject = new $classname( array( 'base_path' => JWF_BACKEND_PATH) );

// Perform the Request task
$controllerObject->execute(JRequest::getVar('task'));

// Redirect if set by the controller
$controllerObject->redirect();