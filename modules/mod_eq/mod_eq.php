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

//require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'eq.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'band.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');

// helper
$helper = new comEqZonalesHelper();

// controladores
$ctrlEq = new EqZonalesControllerEq();
$ctrlEq->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );
$ctrlBand = new EqZonalesControllerBand();
$ctrlBand->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

// parametros
$description = $params->get('description');
$error_no_eq = $params->get('error_no_eq');
$use_module_title = $params->get('use_module_title');
$title = $params->get('title');

// recupera el usuario
$user =& JFactory::getUser();

$eq = null;

// recupera ecualizador del usuario
$result = $ctrlEq->retrieveUserEqImpl($user->id);

if (!is_null($result) && !empty($result)) {
    $eqtmp = $result[0];

    if (!empty($eqtmp)) {
        $eq->eq = $eqtmp->eq;
        $eq->fields = array();

        // segmentamos por fields (grupos de bandas)
        foreach ($eqtmp->bands as $band) {
            $eq->fields[$band->field_id]->id = $band->field_id;
            $eq->fields[$band->field_id]->label = $band->group_label;
            $eq->fields[$band->field_id]->bands[] = $band;
        }
    }
}

$addTagsUrl = JRoute::_('index.php?option=com_eqzonales&task=hierarchictagging.display');
$selectTags = 'select v.name as value from jos_custom_properties cp, jos_custom_properties_values v where cp.value_id=v.id';
$db = JFactory::getDBO();
$db->setQuery($selectTags);
$dbTags = $db->loadObjectList();

$aux = array();
foreach ($dbTags as $tag) {
    $aux[] = $tag->value;
}
$tags = implode(', ', $aux);

require(JModuleHelper::getLayoutPath('mod_eq'));