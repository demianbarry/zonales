<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

//require_once (dirname(__FILE__).DS.'helper.php');
require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// lista de zonales, zonal actualmente seleccionado
$helper = new comZonalesHelper();
$zonal_id = $helper->getZonalActual();
$zonal_name = $helper->getZonal($zonal_id);

// parametros
$showLabel = $params->get('show_label');
$labelText = $params->get('label_text');

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_zonalactual'));
