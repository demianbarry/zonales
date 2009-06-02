<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

//require_once (dirname(__FILE__).DS.'helper.php');
require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// parametros
$showLabel = $params->get('show_label', 'show_label');
$labelText = $params->get('label_text', 'label_text');

// lista de zonales, zonal actualmente seleccionado
$helper = new comZonalesHelper();
$zonal_id = $helper->getZonalActual();
$zonal_name = $zonal_id ? $helper->getZonal($zonal_id) : $params->get('nozonal_text');

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_zonalactual'));
