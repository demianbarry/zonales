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

// helper zonales
require_once (JPATH_BASE . DS . 'components' . DS . 'com_zonales' . DS . 'helper.php');
//require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'controller.php');
//$zController = new ZonalesController();
$helper = new comZonalesHelper();
$selection = new stdClass();

$zonal = $helper->getZonal();
$selectedOption = 0;
$localidades = array();
$selectedParent = 0;

if ($zonal != null) {
    if ($zonal || $zonal->id != 0) {
        $selectedOption = $zonal->id;
    }
    $selectedParent = $zonal->parent_id;
    $abuelo_id = $helper->getIdGrandfather($zonal->id);
    if ($abuelo_id == 0) {
        $selectedParent = $zonal->id;
        $selectedOption = 0;
    }
}

// parametros
$root = $helper->getRoot();

// crea select de zonales disponibles
$parents = $helper->getItems($root);
$lists['provincias_select'] = JHTML::_('select.genericlist', $parents, 'provincias', 'size="1" class="provincias_select required"', 'id', 'label', $selectedParent);

//$lists['municipios_select'] = $zController->getItemsAjax($selectedParent, 'zonalid', $selectedOption);

require(JModuleHelper::getLayoutPath('mod_combozona'));