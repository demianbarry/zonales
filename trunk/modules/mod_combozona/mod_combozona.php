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
require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
$helper = new comZonalesHelper();

// parametros
$root = $params->get('root_value');

// crea select de zonales disponibles
$parents = $helper->getItems($root);
$lists['provincias_select'] = JHTML::_('select.genericlist', $parents, 'provincias',
        'size="1" class="required"', 'id', 'label');

require(JModuleHelper::getLayoutPath('mod_combozona'));