<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
$helper = new comZonalesHelper();

// recupera los menus configurados
$menues = $helper->getMenus();
// recupera los submenús y los asocia el menú correspondiente
foreach ($menues as $menu) {
	if ($menu->link) {
		$menu->link .= '&Itemid=' . $menu->itemid;
	}

        // recupera el usuario
        $user =& JFactory::getUser();

        $menu->submenus = $helper->getMenuValues($menu->id, !$user->guest);
	foreach ($menu->submenus as $submenu) {
		$submenu->link = $submenu->link . '&Itemid=' . $submenu->menu_id;
	}
}

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_menuzonales'));