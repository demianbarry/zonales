<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
$helper = new comZonalesHelper();

// menues
$menues = $helper->getMenus();
// submenues
$menuValues = array();
foreach ($menues as $menu) {
	$menuValues[$menu->id] = $helper->getMenuValues($menu->id);
}

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_menuzonales'));