<?php
/**
 * Custom Properties for Joomla! 1.5.x
 * @package Custom Properties
 * @subpackage Component
 * @version 1.98
 * @revision $Revision: 1.4 $
 * @author Andrea Forghieri
 * @copyright (C) Andrea Forghieri, www.solidsystem.it
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


// Load component helper
require_once( JPATH_COMPONENT.DS.'helper.php' );
// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Create the controller
//$classname    = 'CustompropertiesController';
//$controller   = new $classname( );

$controllerName = JRequest::getWord('controller','CustompropertiesController'); // default controller
$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_customproperties'.DS.'controllers'.DS.$controllerName.'.php';
if(file_exists($path)) {
    require_once($path);
    $classname    = 'CustompropertiesController'.$controllerName;
    $controller   = new $classname( );
}
else {
    $path = JPATH_COMPONENT.DS.$controllerName.'.php';
    if(file_exists($path)) {
        require_once($path);
        $classname    = "CustompropertiesController";
        $controller   = new $classname( );
    } else {
        JError::raiseError( 500, JText::_( 'Missing controller: '.$path.DS.$controllerName ) );
    }
}

// Perform the Request task
$task = JRequest::getVar( 'task' );
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();