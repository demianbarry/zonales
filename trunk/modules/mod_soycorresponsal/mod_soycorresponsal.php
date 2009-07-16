<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

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

// crea select de zonales disponibles
$lists['partido_select'] = JHTML::_('select.genericlist', $zonales, 'partidos',
	'size="1"', 'name', 'label', $zonal->name);

// crea opcion nula para el select
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