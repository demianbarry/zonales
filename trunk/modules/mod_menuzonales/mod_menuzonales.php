<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
$helper = new comZonalesHelper();

// menues
$menues = $helper->getMenus();
// submenues
foreach ($menues as $menu) {
	if ($menu->link) {
		$menu->link .= '&Itemid' . $menu->itemid;
	}

	$menu->submenus = $helper->getMenuValues($menu->id);
	foreach ($menu->submenus as $submenu) {
		$submenu->link = $submenu->link . '&Itemid=' . $submenu->menu_id;
	}
}

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_menuzonales'));