<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// obtiene toolbar.reservas.html.php sin hardcodear el nombre del componente
require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

// barra de tareas a mostrar según tarea
switch($task) {
	case 'editMenu':
	case 'addMenu':
		TOOLBAR_reservas_menu::_NEW();
		break;	
	case 'listMenu':
		TOOLBAR_reservas_menu::_DEFAULT();
		break;
    default:
        TOOLBAR_reservas_menu::_DEFAULT();
        break;
}
