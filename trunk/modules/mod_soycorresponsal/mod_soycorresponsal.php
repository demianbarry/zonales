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
<<<<<<< .mine
$selection = new stdClass();

=======
$selection = new stdClass();
$selection->id = 0;
$selection->label = "Seleccione un partido";
>>>>>>> .r670
$zonales =& $helper->getZonales();
array_unshift($zonales, $selection);
$zonal = $helper->getZonal();
$selectedOption = 0;
$localidades = array();

if(!$zonal || $zonal->id == 0) {
    $selection->id = 0;
    $selection->label = "Seleccione un partido";
    array_unshift($zonales, $selection);
} else {
    $localidades = $helper->getFieldValues($zonal->id);
    $selectedOption = $zonal->id;
}


// crea select de zonales disponibles
$lists['partido_select'] = JHTML::_('select.genericlist', $zonales, 'partidos',
<<<<<<< .mine
        'size="1" class="required"', 'id', 'label', $selectedOption);
=======
	'size="1" class="required" onchange="this.options[0].value == 0 ? this.remove(0): null; 
                                             $(\'localidad_container\').setStyle(\'display\',\'\');
                                             $(\'loc_label\').setStyle(\'display\',\'\');
                                            "', 'id', 'label', $selection);
>>>>>>> .r670

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