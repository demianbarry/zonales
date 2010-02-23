<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// obtiene toolbar.zonales.html.php sin hardcodear el nombre del componente
require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

// barra de tareas a mostrar según tarea
switch($task) {
	default:
		TOOLBAR_eqzonales_default::_DEFAULT();
		break;
}
