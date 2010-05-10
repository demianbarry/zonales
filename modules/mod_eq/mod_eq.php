<?php
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
$ctrlEq->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

// parametros
$description = $params->get('description');

// recupera el usuario
$user =& JFactory::getUser();

$eq = null;

if (!$user->guest) {
    // recupera ecualizador del usuario
    $result = $ctrlEq->retrieveUserEqImpl($user->id);
    if (!is_null($result) && !empty($result)) {
        $eq = $result[0];
    }
}

require(JModuleHelper::getLayoutPath('mod_eq'));