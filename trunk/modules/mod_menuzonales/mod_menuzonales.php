<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
$helper = new comZonalesHelper();

// parametros del módulo
$zonalesParams = &JComponentHelper::getParams( 'com_zonales' );
$menuPrefix = $params->get('custom_menu_prefix', '') . $zonalesParams->get('menu_tag_prefix');

// menues
$menues = $helper->getMenus($menuPrefix);
// submenues
$menuValues = array();
foreach ($menues as $menu) {
	$menuValues[$menu->id] = $helper->getMenuValues($menu->id);
}

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_menuzonales'));
