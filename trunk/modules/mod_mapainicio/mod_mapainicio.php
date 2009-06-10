<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

$helper = new comZonalesHelper();
$j2f = $helper->getZif2SifMap();

require(JModuleHelper::getLayoutPath('mod_mapainicio'));