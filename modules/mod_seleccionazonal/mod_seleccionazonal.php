<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// parametros
$showHeader = $params->get('show_header');
$useCustomHeader = $params->get('custom_header');
$customHeaderText = $params->get('header_text');
$useSubmitButton = $params->get('use_submit_button');

// lista de zonales, zonal actualmente seleccionado
$helper = new comZonalesHelper();
$zonales =& $helper->getZonales();
$zonal = $helper->getZonal();

// js
$js = !$useSubmitButton ? 'OnChange="document.setZonalModForm.submit()"' : '';
// crea opcion nula para el select
$blank_option[] = JHTML::_('select.option', '', JText::_('SELECCIONE_ZONAL'), 'name', 'label');
// crea select de zonales disponibles
$zonales_list = array_merge($blank_option, $zonales);
$lists['zonales_select'] = JHTML::_('select.genericlist', $zonales_list, 'zname',
	'class="cmb" size="1" ' . $js, 'name', 'label', $zonal->name);

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

// url de retorno según sección del menu actual
$menu =& JSite::getMenu();
$item = $menu->getActive();
$return = $item ? $item->link . '&Itemid='. $item->id : 'index.php';

require(JModuleHelper::getLayoutPath('mod_seleccionazonal'));
