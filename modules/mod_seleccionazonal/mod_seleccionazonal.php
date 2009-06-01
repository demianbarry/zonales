<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

// lista de zonales, zonal actualmente seleccionado
$helper = new modSeleccionaZonalHelper();
$zonales =& $helper->getZonales();
$zonal_id = $helper->getZonalActual();

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

// url actual
$uri = JURI::getInstance();
$query = $uri->getQuery();

// parametros
$showHeader = $params->get('show_header');
$useCustomHeader = $params->get('custom_header');
$customHeaderText = $params->get('header_text');
$useSubmitButton = $params->get('use_submit_button');

require(JModuleHelper::getLayoutPath('mod_seleccionazonal'));
