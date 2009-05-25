<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');
$helper = new modSeleccionaZonalHelper();
$zonales =& $helper->getZonales();

$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_seleccionazonal'));
