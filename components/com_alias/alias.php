<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

#require_once 'alias.html.view.php';
require_once 'controller.php';

// Create the controller
$controller = new AliasController();

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();


