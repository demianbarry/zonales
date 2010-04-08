<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// obtiene toolbar.zonales.html.php sin hardcodear el nombre del componente
require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

// barra de tareas a mostrar según tarea
switch($task) {

    case 'editUser':
    case 'addUser':
        TOOLBAR_aapu_users::_NEW();
        break;

    case 'listUsers':
        TOOLBAR_aapu_users::_DEFAULT();
        break;

    case 'editTab':
    case 'addTab':
        TOOLBAR_aapu_tabs::_NEW();
        break;

    case 'listTabs':
        TOOLBAR_aapu_tabs::_DEFAULT();
        break;

    case 'editAttribute':
    case 'addAttribute':
        TOOLBAR_aapu_attributes::_NEW();
        break;

    case 'listAttributes':
        TOOLBAR_aapu_attributes::_DEFAULT();
        break;

    case 'editDataType':
    case 'addDataType':
        TOOLBAR_aapu_datatypes::_NEW();
        break;

    case 'listDataTypes':
        TOOLBAR_aapu_datatypes::_DEFAULT();
        break;

    default:
        TOOLBAR_aapu_users::_DEFAULT();
        break;
}
