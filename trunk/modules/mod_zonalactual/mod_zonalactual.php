<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// parametros
$showLabel = $params->get('show_label', 'show_label');
$labelText = $params->get('label_text', 'label_text');

// lista de zonales, zonal actualmente seleccionado
$helper = new comZonalesHelper();
$zonal = $helper->getZonal();
$zonalName = $zonal ? $zonal->label : $params->get('nozonal_text');

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_zonalactual'));
