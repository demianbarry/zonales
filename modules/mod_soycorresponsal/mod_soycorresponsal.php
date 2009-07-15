<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// parametros
$showEmail = $params->get('show_email');
$showPhone = $params->get('show_phone');

// lista de zonales, zonal actualmente seleccionado
$helper = new comZonalesHelper();
$zonales =& $helper->getZonales();
$zonal = $helper->getZonal();
$localidades = $helper->getFieldValues($zonal->id);

// crea opcion nula para el select
$blank_option[] = JHTML::_('select.option', '', JText::_('SELECCIONE_PARTIDO'), 'name', 'label');
// crea select de zonales disponibles
$zonales_list = array_merge($blank_option, $zonales);
$lists['partido_select'] = JHTML::_('select.genericlist', $zonales_list, 'partidos',
	'size="1"', 'name', 'label', $zonal->name);

// crea opcion nula para el select
unset($blank_option);
$blank_option[] = JHTML::_('select.option', '', JText::_('SELECCIONE_LOCALIDAD'), 'name', 'label');
// crea select de zonales disponibles
$localidades_list = array_merge($blank_option, $localidades);
$lists['localidad_select'] = JHTML::_('select.genericlist', $localidades_list, 'localidad',
	'size="1"', 'name', 'label');

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

// editor
$editor =& JFactory::getEditor();
$editorParams = array ( 
		'theme' => 'simple',
		'toolbar' => 'top'
	);

// info de usuario
$user =& JFactory::getUser();

require(JModuleHelper::getLayoutPath('mod_soycorresponsal'));