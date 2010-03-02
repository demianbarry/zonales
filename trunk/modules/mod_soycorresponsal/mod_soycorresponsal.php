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

// parametros
$showEmail = $params->get('show_email');
$showPhone = $params->get('show_phone');
$captchaTextNew = $params->get('captchaTextNew');
$captchaTextSnd = $params->get('captchaTextSnd');
$captchaTextImg = $params->get('captchaTextImg');
$captchaTextHelp = $params->get('captchaTextHelp');

$zonalesParams = &JComponentHelper::getParams( 'com_zonales' );
$captcha_publickey = $zonalesParams->get('recaptcha_publickey', null);

// lista de zonales, zonal actualmente seleccionado
$helper = new comZonalesHelper();
$zonales =& $helper->getZonales();
$zonal = $helper->getZonal();
$localidades = $helper->getFieldValues($zonal->id);

// crea select de zonales disponibles
$lists['partido_select'] = JHTML::_('select.genericlist', $zonales, 'partidos',
	'size="1" class="required"', 'id', 'label', $zonal->id);

// crea select de zonales disponibles
$lists['localidad_select'] = JHTML::_('select.genericlist', $localidades, 'localidad',
	'size="1" class="required"', 'id', 'label');

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

// valores para los parametros del template
$tparams = new JParameter(JFile::read(JPATH_BASE.DS.'templates'.DS.
		$template.DS.'params.ini'));

$mainColor = $tparams->get('mainColor', null);
if ($mainColor) $mainColor = '_' . $mainColor;

require(JModuleHelper::getLayoutPath('mod_soycorresponsal'));