<?php
defined('_JEXEC') or die('Restricted Access');
require_once(JApplicationHelper::getPath( 'toolbar_html') );
switch($task)
{
	case 'edit':
	case 'add':
		TOOLBAR_aardvertiser::_NEW();
		break;
	case 'conf':
		TOOLBAR_aardvertiser::_CONF();
		break;
	case 'categories':
	case 'savecategory':
	case 'removecategory':
		TOOLBAR_aardvertiser_categories::_DEFAULT();
		break;
	case 'editcategory':
	case 'addcategory':
		TOOLBAR_aardvertiser_categories::_NEW();
		break;
	case 'showcss':
		TOOLBAR_aardvertiser::_CSS();
		break;
	case 'email':
		TOOLBAR_aardvertiser::_EMAIL();
		break;
	default:
		TOOLBAR_aardvertiser::_DEFAULT();
		break;
}